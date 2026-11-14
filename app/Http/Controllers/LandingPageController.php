<?php

namespace App\Http\Controllers;

use App\Models\Pembeli\Product_pembeli;
use App\Models\Pembeli\Rental_pembeli;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    /**
     * Tampilkan Landing Page Utama Campify.
     */
    public function index()
    {
        // Jika sudah login sebagai seller atau admin, arahkan langsung ke dashboard masing-masing
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'seller') {
                return redirect()->route('seller.dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }

        // Produk Unggulan Sewa (Rent)
        $rentalProducts = Product_pembeli::where(function ($q) {
                $q->where('jenis_produk', 'sewa')
                  ->orWhere('rent_price', '>', 0);
            })
            ->where('status', 'active')
            ->orderBy('rating', 'desc')
            ->latest()
            ->take(4)
            ->get();

        // Jika produk aktif belum banyak, ambil fallback teratas
        if ($rentalProducts->isEmpty()) {
            $rentalProducts = Product_pembeli::where('jenis_produk', 'sewa')
                ->latest()
                ->take(4)
                ->get();
        }

        // Produk Unggulan Beli (Buy)
        $buyProducts = Product_pembeli::where(function ($q) {
                $q->where('jenis_produk', 'jual')
                  ->orWhere('buy_price', '>', 0);
            })
            ->where('status', 'active')
            ->orderBy('rating', 'desc')
            ->latest()
            ->take(4)
            ->get();

        if ($buyProducts->isEmpty()) {
            $buyProducts = Product_pembeli::where('jenis_produk', 'jual')
                ->latest()
                ->take(4)
                ->get();
        }

        // Statistik Ekosistem Campify
        $totalProductsCount = Product_pembeli::count();
        $totalStoresCount = Store::count();
        $totalRentalsCount = Rental_pembeli::count();

        // Curated Categories dengan fotografi petualangan alam yang harmonis
        $featuredCategories = [
            [
                'key' => 'tenda',
                'name' => 'Tenda & Shelter',
                'icon' => 'tent',
                'desc' => 'Dome tent kapasitas 2-6 orang, flysheet tahan angin & pasak kokoh.',
                'image' => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'key' => 'tas_gunung',
                'name' => 'Carrier & Tas Gunung',
                'icon' => 'backpack',
                'desc' => 'Tas carrier 40L - 75L dengan backsystem empuk & raincover waterproof.',
                'image' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'key' => 'alat_masak',
                'name' => 'Alat Masak & Kompor',
                'icon' => 'flame',
                'desc' => 'Kompor windproof portable, nesting teflon, dan perlengkapan makan alam.',
                'image' => 'https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'key' => 'penerangan',
                'name' => 'Penerangan & Lentera',
                'icon' => 'flashlight',
                'desc' => 'Headlamp waterproof berdaya tahan tinggi, lentera gantung, & emergency gear.',
                'image' => 'https://images.unsplash.com/photo-1517824806704-9040b037703b?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'key' => 'sepatu',
                'name' => 'Sepatu & Tracking Boots',
                'icon' => 'footprints',
                'desc' => 'Sepatu hiking grip kuat di medan basah & bebatuan terjal.',
                'image' => 'https://images.unsplash.com/photo-1520639888713-7851133b1ed0?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'key' => 'aksesoris',
                'name' => 'Sleeping Gear & Matras',
                'icon' => 'bed',
                'desc' => 'Sleeping bag hangat, matras aluminium foil, & bantal angin praktis.',
                'image' => 'https://images.unsplash.com/photo-1517411032315-54ef2cb783bb?auto=format&fit=crop&w=600&q=80'
            ],
        ];

        return view('landing.index', compact(
            'rentalProducts',
            'buyProducts',
            'totalProductsCount',
            'totalStoresCount',
            'totalRentalsCount',
            'featuredCategories'
        ));
    }
}
