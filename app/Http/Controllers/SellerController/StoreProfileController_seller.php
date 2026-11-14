<?php

namespace App\Http\Controllers\SellerController;

use App\Http\Controllers\Controller;
use App\Models\SellerModels\StoreProfile_seller;
use App\Models\SellerModels\Product_seller;
use App\Models\SellerModels\ProductRating_seller;
use App\Models\SellerModels\StoreRating_seller;
use Illuminate\Http\Request;


class StoreProfileController_seller extends Controller
{
    /**
     * Tampilan profil toko utama (Overview/Show).
     */
    public function index()
    {
        return $this->show();
    }

    /**
     * Detail tinjauan profil toko.
     */
    public function show()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $profile = StoreProfile_seller::where('user_id', $userId)->first();
        
        // Statistik toko
        $products = Product_seller::where('user_id', $userId)->get();
        $totalProducts = $products->count();
        $rentalProducts = $products->where('jenis_produk', 'sewa')->count();
        
        // Rating toko
        $avgRating = StoreRating_seller::getAverageRating($userId);
        $ratingCount = StoreRating_seller::getRatingCount($userId);
        
        return view('SellerView.store_profile.show_seller', compact('profile', 'totalProducts', 'rentalProducts', 'avgRating', 'ratingCount'));
    }

    /**
     * Halaman form edit profil toko.
     */
    public function edit()
    {
        $profile = StoreProfile_seller::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        return view('SellerView.store_profile.index_seller', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = StoreProfile_seller::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();

        $data = $request->validate([
            'nama_toko' => 'required',
            'deskripsi' => 'nullable',
            'alamat' => 'nullable',
            'no_telp' => 'nullable',
            'bank_name' => 'nullable|string',
            'bank_account_number' => 'nullable|string',
            'bank_account_name' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload Foto / Logo Toko
        if ($request->hasFile('logo')) {
            if ($profile && $profile->logo) {
                $oldFile = public_path($profile->logo);
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            $destinationPath = public_path('assets/images/stores');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file = $request->file('logo');
            $filename = 'store_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);
            $data['logo'] = 'assets/images/stores/' . $filename;
        }

        // Otomatis Geocoding Alamat Toko dengan Smart Fallback
        if (!empty($data['alamat'])) {
            $coords = $this->resolveCoordinates($data['alamat']);
            if ($coords) {
                $data['latitude'] = $coords['latitude'];
                $data['longitude'] = $coords['longitude'];
            } elseif ($profile && !empty($profile->latitude) && !empty($profile->longitude)) {
                // Pertahankan koordinat yang sudah ada jika alamat baru tidak dapat di-resolve otomatis
                $data['latitude'] = $profile->latitude;
                $data['longitude'] = $profile->longitude;
            }
        }

        if ($profile) {
            $profile->update($data);
        } else {
            $data['user_id'] = \Illuminate\Support\Facades\Auth::id();
            $data['status'] = 'active';
            StoreProfile_seller::create($data);
        }

        return redirect()->route('seller.store-profile.index')->with('success', 'Profil toko berhasil diperbarui.');
    }

    /**
     * Helper geocoding alamat dengan smart fallback.
     */
    private function resolveCoordinates(string $address): ?array
    {
        $address = trim($address);
        if (empty($address)) {
            return null;
        }

        $queries = [$address];

        // Format alternatif 1: Hilangkan detail nomor jalan / RT / RW / Gang
        $simplified = preg_replace('/(rt|rw|gang|gg|no|blok)\s*[\d\w\/\.-]+/i', '', $address);
        $simplified = trim(preg_replace('/\s+/', ' ', $simplified));
        if ($simplified !== $address && strlen($simplified) > 5) {
            $queries[] = $simplified;
        }

        // Format alternatif 2: Ambil bagian kecamatan / kota / kabupaten jika dipisahkan tanda koma
        $parts = array_filter(array_map('trim', explode(',', $address)));
        if (count($parts) >= 2) {
            $queries[] = implode(', ', array_slice($parts, -3));
            $queries[] = implode(', ', array_slice($parts, -2));
        }

        $queries = array_unique(array_filter($queries));

        foreach ($queries as $q) {
            try {
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()->withHeaders([
                    'User-Agent' => 'CampifyApp/1.0'
                ])->timeout(3)->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $q,
                    'format' => 'json',
                    'limit' => 1
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    if (!empty($json[0]['lat']) && !empty($json[0]['lon'])) {
                        return [
                            'latitude' => $json[0]['lat'],
                            'longitude' => $json[0]['lon']
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // Abaikan kesalahan koneksi geocoding agar proses simpan tidak terganggu
            }
        }

        return null;
    }

    /**
     * Seller mengirimkan klarifikasi ke admin setelah toko di-ban/suspend.
     * Disimpan ke catatan_admin agar admin bisa review dan membuka ban.
     */
    public function submitKlarifikasi(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'klarifikasi' => 'required|string|min:20|max:1000',
        ], [
            'klarifikasi.required' => 'Klarifikasi tidak boleh kosong.',
            'klarifikasi.min'      => 'Klarifikasi minimal 20 karakter.',
        ]);

        $store = \App\Models\Store::where('user_id', \Illuminate\Support\Facades\Auth::id())->firstOrFail();

        $store->update([
            'catatan_admin' => 'Klarifikasi Seller: ' . trim($request->klarifikasi),
        ]);

        return back()->with('success', 'Klarifikasi berhasil dikirim! Tim admin akan meninjau dan menghubungi Anda.');
    }
}
