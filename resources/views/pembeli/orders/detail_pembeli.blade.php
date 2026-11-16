@extends('layouts.app_pembeli')

@section('extra_css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #tracking-map { 
        height: 380px; 
        width: 100%;
        border-radius: 20px; 
        z-index: 10; 
        overflow: hidden;
    }
    .custom-map-pin {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        color: #0f172a;
        padding: 5px 12px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 800;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 4px 10px -2px rgba(0, 0, 0, 0.08);
        border: 2px solid #059669;
        white-space: nowrap;
        pointer-events: auto;
    }
    .custom-map-pin.buyer {
        border-color: #2563eb;
        color: #1e3a8a;
    }
    .custom-map-pin.courier {
        background: #0f172a;
        color: #f8fafc;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.3);
        animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse-ring {
        0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5); }
        50% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    }
    .leaflet-control-zoom {
        border: none !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
        border-radius: 12px !important;
        overflow: hidden !important;
    }
    .leaflet-control-zoom a {
        background-color: #ffffff !important;
        color: #1e293b !important;
        border: none !important;
        font-size: 14px !important;
        width: 32px !important;
        height: 32px !important;
        line-height: 32px !important;
    }
    .leaflet-control-zoom a:hover {
        background-color: #f1f5f9 !important;
        color: #059669 !important;
    }
</style>
@endsection

@section('content')
<div class="pt-28 pb-20 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4">

        <!-- BACK BUTTON -->
        @php
            $hasRental = false;
            if (isset($pesanan) && $pesanan->details->isNotEmpty()) {
                $hasRental = $pesanan->details->contains('type', 'rent');
            }
            $backUrl = $hasRental ? route('profile', ['tab' => 'rentals']) : route('profile', ['tab' => 'purchases']);
            $backLabel = $hasRental ? 'Kembali ke Penyewaan Saya' : 'Kembali ke Pembelian Saya';
        @endphp
        <div class="mb-6">
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-sm font-semibold text-green-600 hover:text-green-800 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> {{ $backLabel }}
            </a>
        </div>

        <!-- KYC WARNING FOR BUYER -->
        @if(!auth()->user()->ktp_verified_at)
            <div class="mb-6 p-5 bg-amber-50 border-2 border-amber-200 rounded-3xl flex gap-5 items-start">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                    <i data-lucide="shield-alert" class="w-6 h-6"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-black text-amber-900 uppercase tracking-widest text-sm mb-1">Verifikasi Identitas Diperlukan</h3>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Pesanan Anda saat ini <strong>tertahan sementara</strong> karena identitas (KTP) Anda belum diverifikasi. 
                        Penjual hanya dapat memproses pesanan setelah identitas Anda dikonfirmasi demi keamanan bersama.
                    </p>
                    <a href="{{ route('profile') }}" class="mt-3 inline-block text-xs font-bold text-amber-900 border-b-2 border-amber-900 hover:text-amber-700 transition-colors">Cek Status KTP Saya →</a>
                </div>
            </div>
        @endif
        <!-- RETURN STATUS ALERTS -->
        @if($pesanan->returnRequest)
            @php
                $rStatus = $pesanan->returnRequest->status;
            @endphp

            @if($rStatus === 'checking')
                <div class="mb-6 p-5 bg-amber-50 border-2 border-amber-200 rounded-3xl flex gap-5 items-start shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                        <i data-lucide="search" class="w-6 h-6"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-black text-amber-900 uppercase tracking-widest text-sm mb-1">Pemeriksaan Barang</h3>
                        <p class="text-xs text-amber-800 leading-relaxed">
                            Barang yang Anda kembalikan sedang diperiksa oleh penjual/admin untuk memastikan kondisinya. 
                            Status akan diperbarui segera setelah pemeriksaan selesai.
                        </p>
                    </div>
                </div>
            @elseif($rStatus === 'pending')
                <div class="mb-6 p-5 bg-blue-50 border-2 border-blue-200 rounded-3xl flex gap-5 items-start shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-black text-blue-900 uppercase tracking-widest text-sm mb-1">Menunggu Penjual</h3>
                        <p class="text-xs text-blue-800 leading-relaxed">
                            Permintaan pengembalian Anda telah dikirim. Menunggu konfirmasi atau respon dari pihak penjual.
                        </p>
                    </div>
                </div>
            @endif
        @endif

        <!-- HEADER -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-800">
                {{ isset($detailId) ? 'Detail Barang' : 'Detail Pesanan' }} #{{ $pesanan->order_number ?? $pesanan->id }}
            </h1>

            @php
                // Jika sedang melihat barang spesifik, ambil info rental khusus barang itu
                if (isset($detailId)) {
                    $item = $pesanan->details->first();
                    $rental = \App\Models\Pembeli\Rental_pembeli::where('order_id', $pesanan->id)
                                ->where('product_id', $item->product_id)
                                ->first();
                } else {
                    $rental = \App\Models\Pembeli\Rental_pembeli::where('order_id', $pesanan->id)->first();
                }

                $displayStatus = $pesanan->status;
                $statusColor = 'bg-amber-100 text-amber-700';

                if ($rental) {
                    $displayStatus = $rental->status;
                    $statusColor = match($rental->status) {
                        'active' => 'bg-blue-100 text-blue-700',
                        'returned' => 'bg-indigo-100 text-indigo-700',
                        'completed' => 'bg-emerald-100 text-emerald-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                        default => 'bg-amber-100 text-amber-700'
                    };
                } else {
                    $statusColor = match($pesanan->status) {
                        'diproses' => 'bg-yellow-100 text-yellow-700',
                        'selesai' => 'bg-green-100 text-green-700',
                        'menunggu' => 'bg-amber-100 text-amber-700',
                        'dibatalkan' => 'bg-red-100 text-red-700',
                        default => 'bg-slate-100 text-slate-700'
                    };
                }
            @endphp

            <div class="flex items-center gap-2">
                @if(isset($detailId))
                    @php $specificItem = $pesanan->details->first(); @endphp
                    <span class="px-3 py-1 {{ $specificItem->type === 'rent' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }} rounded-full text-[10px] font-black uppercase tracking-widest border">
                        {{ $specificItem->type === 'rent' ? 'Penyewaan' : 'Pembelian' }}
                    </span>
                @elseif($rental)
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-100">Penyewaan</span>
                @endif
                <span class="px-4 py-1 rounded-full text-sm font-bold uppercase {{ $statusColor }}">
                    {{ ucfirst($displayStatus) }}
                </span>
            </div>
        </div>

        <!-- CARD -->
        <div class="bg-white rounded-3xl shadow-lg p-6 space-y-6">

            <!-- INFO PENGIRIMAN & PEMBAYARAN -->
            <div class="grid md:grid-cols-2 gap-8 border-b pb-6">
                <div class="space-y-3">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Informasi Pengiriman</p>
                    <div class="space-y-1">
                        <p class="font-bold text-slate-800">{{ $pesanan->receiver_name }}</p>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            {{ $pesanan->shipping_address }}<br>
                            {{ $pesanan->shipping_district }}, {{ $pesanan->shipping_city }}<br>
                            {{ $pesanan->shipping_postal_code }}
                        </p>
                        <p class="text-sm text-slate-600 mt-2">
                            <span class="font-semibold text-slate-800">Telepon:</span> {{ $pesanan->shipping_phone }}
                        </p>
                    </div>
                    <div class="pt-2 flex flex-wrap items-center gap-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 rounded-lg">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Kurir:</span>
                            <span class="text-xs font-bold text-slate-800 uppercase">{{ $pesanan->kurir ?? '-' }}</span>
                        </div>
                        @php
                            $realResi = $pesanan->no_resi;
                            if (!$realResi && $pesanan->sellerOrders && $pesanan->sellerOrders->isNotEmpty()) {
                                $realResi = $pesanan->sellerOrders->first(fn($so) => !empty($so->no_resi))?->no_resi;
                            }
                        @endphp
                        @if($realResi)
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-200 rounded-lg">
                                <span class="text-[10px] font-bold text-emerald-700 uppercase">No. Resi:</span>
                                <span class="text-xs font-mono font-black text-emerald-900" id="noResiText">{{ $realResi }}</span>
                                <button type="button" onclick="copyResi()" class="text-emerald-700 hover:text-emerald-900 text-[10px] font-bold underline ml-1 cursor-pointer">Salin</button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Rincian Pembayaran</p>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Metode Pembayaran</span>
                            <span class="font-bold text-slate-800 uppercase">{{ $pesanan->metode_pembayaran }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Status Pembayaran</span>
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold uppercase">Lunas</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t">
                            <span class="font-bold text-slate-800">Total Transaksi {{ isset($detailId) ? '(Barang Ini)' : '' }}</span>
                            <span class="text-xl font-black text-emerald-600">
                                Rp {{ number_format(isset($detailId) ? ($pesanan->details->first()->harga * $pesanan->details->first()->qty + ($pesanan->details->first()->type === 'rent' ? ($pesanan->details->first()->product->escrow_amount > 0 ? $pesanan->details->first()->product->escrow_amount : ($pesanan->details->first()->product->buy_price * 0.25)) : 0)) : ($pesanan->total ?? 0)) }}
                            </span>
                        </div>
                        @if($pesanan->bukti_pembayaran)
                        <div class="pt-4 mt-4 border-t border-dashed border-slate-200">
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Bukti Pembayaran</p>
                            <a href="{{ asset($pesanan->bukti_pembayaran) }}" target="_blank" class="group block relative rounded-xl overflow-hidden border-2 border-slate-100 hover:border-emerald-500 transition-all">
                                <img src="{{ asset($pesanan->bukti_pembayaran) }}" class="w-full h-32 object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-xs font-bold px-3 py-1 bg-black/50 rounded-full">Lihat Full Image</span>
                                </div>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- MAP TRACKING & LOGISTICS SECTION -->
            @php
                $orderStatus = strtolower($pesanan->status ?? '');
                $stepIndex = 1;
                if (in_array($orderStatus, ['diproses', 'menunggu_pengiriman'])) {
                    $stepIndex = 2;
                } elseif (in_array($orderStatus, ['dikirim', 'shipping', 'otw'])) {
                    $stepIndex = 3;
                } elseif (in_array($orderStatus, ['selesai', 'delivered', 'completed'])) {
                    $stepIndex = 4;
                }
                $storeData = $pesanan->details->first()?->product?->store;
            @endphp
            <div class="pt-6 border-b pb-6" id="tracking-section">
                <!-- Header Pelacakan -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                Live Logistics
                            </span>
                            <span class="text-xs font-bold text-slate-400">#{{ $pesanan->order_number ?? $pesanan->id }}</span>
                        </div>
                        <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <span>Lacak Pengiriman Pesanan</span>
                        </h2>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($orderStatus === 'dikirim')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-sm">
                                <i data-lucide="truck" class="w-3.5 h-3.5 animate-bounce"></i> Dalam Pengiriman
                            </span>
                        @elseif($orderStatus === 'diproses')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                <i data-lucide="package" class="w-3.5 h-3.5"></i> Sedang Dikemas
                            </span>
                        @elseif($orderStatus === 'selesai')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Paket Telah Diterima
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i> Menunggu Konfirmasi
                            </span>
                        @endif

                        <button type="button" id="toggleMapBtn" class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold text-slate-600 hover:text-emerald-700 bg-slate-100 hover:bg-slate-200 rounded-full transition-all">
                            <span id="toggleMapText">Sembunyikan Peta</span>
                        </button>
                    </div>
                </div>

                <!-- 4-Stage Milestone Stepper -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-5 mb-5">
                    <div class="grid grid-cols-4 gap-2 sm:gap-4 relative">
                        <!-- Progress Line -->
                        <div class="absolute top-4 left-6 right-6 h-1 bg-slate-200 -z-0">
                            <div class="h-full bg-emerald-500 transition-all duration-700" style="width: {{ $stepIndex == 1 ? '15%' : ($stepIndex == 2 ? '45%' : ($stepIndex == 3 ? '78%' : '100%')) }};"></div>
                        </div>

                        <!-- Step 1 -->
                        <div class="text-center relative z-10">
                            <div class="w-8 h-8 mx-auto mb-2 rounded-full flex items-center justify-center font-bold text-xs transition-all {{ $stepIndex >= 1 ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200 ring-4 ring-emerald-50' : 'bg-slate-200 text-slate-500' }}">
                                ✓
                            </div>
                            <p class="text-xs font-bold text-slate-800 leading-tight">Dibuat</p>
                            <p class="text-[10px] text-slate-400 hidden sm:block mt-0.5">Pesanan masuk</p>
                        </div>

                        <!-- Step 2 -->
                        <div class="text-center relative z-10">
                            <div class="w-8 h-8 mx-auto mb-2 rounded-full flex items-center justify-center font-bold text-xs transition-all {{ $stepIndex >= 2 ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200 ring-4 ring-emerald-50' : 'bg-slate-200 text-slate-500' }}">
                                @if($stepIndex > 2) ✓ @else 2 @endif
                            </div>
                            <p class="text-xs font-bold {{ $stepIndex >= 2 ? 'text-slate-800' : 'text-slate-400' }} leading-tight">Dikemas</p>
                            <p class="text-[10px] text-slate-400 hidden sm:block mt-0.5">Disiapkan toko</p>
                        </div>

                        <!-- Step 3 -->
                        <div class="text-center relative z-10">
                            <div class="w-8 h-8 mx-auto mb-2 rounded-full flex items-center justify-center font-bold text-xs transition-all {{ $stepIndex >= 3 ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200 ring-4 ring-emerald-50' : 'bg-slate-200 text-slate-500' }}">
                                @if($stepIndex > 3) ✓ @else 3 @endif
                            </div>
                            <p class="text-xs font-bold {{ $stepIndex >= 3 ? 'text-slate-800' : 'text-slate-400' }} leading-tight">Dikirim</p>
                            <p class="text-[10px] text-slate-400 hidden sm:block mt-0.5">Kurir perjalanan</p>
                        </div>

                        <!-- Step 4 -->
                        <div class="text-center relative z-10">
                            <div class="w-8 h-8 mx-auto mb-2 rounded-full flex items-center justify-center font-bold text-xs transition-all {{ $stepIndex >= 4 ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200 ring-4 ring-emerald-50' : 'bg-slate-200 text-slate-500' }}">
                                @if($stepIndex >= 4) ✓ @else 4 @endif
                            </div>
                            <p class="text-xs font-bold {{ $stepIndex >= 4 ? 'text-slate-800' : 'text-slate-400' }} leading-tight">Selesai</p>
                            <p class="text-[10px] text-slate-400 hidden sm:block mt-0.5">Paket sampai</p>
                        </div>
                    </div>
                </div>


                <!-- Leaflet Interactive Map Container -->
                <div id="mapWrapper" class="relative group rounded-2xl overflow-hidden border border-slate-200 shadow-sm transition-all duration-300">
                    <div id="tracking-map"></div>

                    <!-- Bottom floating status overlay -->
                    <div class="absolute bottom-3 left-3 right-3 z-30 pointer-events-none">
                        <div class="pointer-events-auto bg-white/95 backdrop-blur-md px-4 py-3 rounded-2xl shadow-lg border border-slate-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0 border border-emerald-100">
                                    @if($orderStatus === 'dikirim') 🚚 @elseif($orderStatus === 'selesai') 📦 @else 🏪 @endif
                                </div>
                                <div>
                                    <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider">Status Terkini</p>
                                    <p class="text-xs font-extrabold text-slate-800 leading-snug">
                                        @if($orderStatus == 'diproses')
                                            Paket sedang disiapkan dan dicek oleh {{ $storeData->nama_toko ?? 'Mitra Toko' }}
                                        @elseif($orderStatus == 'dikirim')
                                            Paket sedang dalam perjalanan kurir menuju alamat Anda
                                        @elseif($orderStatus == 'selesai')
                                            Paket telah tiba dan diterima di alamat tujuan
                                        @else
                                            Menunggu konfirmasi pembayaran pesanan
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right shrink-0">
                                <span class="text-[10px] text-slate-400 block font-medium">Lokasi Asal:</span>
                                <span class="text-xs font-bold text-slate-700">{{ $storeData->nama_toko ?? 'Mitra Campify' }} ({{ $storeData->kota ?? 'Jawa Barat' }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DAFTAR ITEM -->
            <div>
                <h2 class="font-semibold text-lg mb-4 text-slate-800">Daftar Item</h2>

                <div class="space-y-6">
                    @foreach($pesanan->details as $item)
                        @php
                            $produk = $item->product;
                            $sellerOrderForItem = $pesanan->sellerOrders
                                ->first(fn($sellerOrder) => $sellerOrder->items->contains('order_detail_id', $item->id));
                            $itemDelivered = $sellerOrderForItem?->status === 'delivered' || $pesanan->status === 'selesai';
                            // Cek tipe: jika di DB 'buy' tapi nama ada '(Sewa)', anggap sewa agar tampilan benar
                            $isBuy = ($item->type === 'buy' && !str_contains(strtolower($produk->name ?? $produk->nama_produk), '(sewa)'));
                        @endphp

                        <div class="border rounded-[24px] p-5 bg-slate-50/50 hover:bg-white hover:shadow-md transition-all duration-300">
                            <div class="flex flex-col md:flex-row gap-6">
                                <!-- FOTO PRODUK -->
                                <div class="w-full md:w-32 h-32 flex-shrink-0">
                                    @if($produk->image_url)
                                        <img src="{{ $produk->image_url }}" class="w-full h-full object-cover rounded-2xl shadow-sm" alt="{{ $produk->name ?? $produk->nama_produk }}">
                                    @else
                                        <div class="w-full h-full bg-slate-100 rounded-2xl flex items-center justify-center text-3xl">📦</div>
                                    @endif
                                </div>

                                <!-- INFO UTAMA -->
                                <div class="flex-1 space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            bg-emerald-100 text-emerald-700">
                                            {{ $isBuy ? 'Pembelian' : 'Penyewaan' }}
                                        </span>
                                        <h3 class="font-bold text-slate-900 text-lg">
                                            {{ $produk->name ?? 'Produk tidak ditemukan' }}
                                        </h3>
                                    </div>

                                    @if($isBuy)
                                        {{-- LAYOUT PEMBELIAN --}}
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-2">
                                            <div>
                                                <p class="text-xs text-slate-500 uppercase font-semibold">Jumlah</p>
                                                <p class="font-medium">{{ $item->qty }} Item</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 uppercase font-semibold">Harga Satuan</p>
                                                <p class="font-medium text-emerald-600">Rp {{ number_format($item->harga) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 uppercase font-semibold">Nomor Resi</p>
                                                <p class="font-medium text-emerald-600">{{ $sellerOrderForItem?->no_resi ?? $pesanan->no_resi ?? 'Menunggu Pengiriman' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 uppercase font-semibold">Subtotal</p>
                                                <p class="font-bold text-slate-900">Rp {{ number_format($item->harga * $item->qty) }}</p>
                                            </div>
                                        </div>

                                        {{-- Retur Section for Buy --}}
                                        @php
                                            $buyReturn = \App\Models\Pembeli\Return_pembeli::where('order_id', $pesanan->id)
                                                ->where('type', 'jual_beli')
                                                ->first();
                                        @endphp
                                        <div class="mt-4 pt-4 border-t border-slate-200">
                                            @if(!$buyReturn)
                                                @if($itemDelivered)
                                                    <a href="{{ route('orders.return', $item->id) }}" class="inline-flex items-center justify-center rounded-xl border-2 border-red-200 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition">
                                                        <i data-lucide="refresh-ccw" class="w-3 h-3 me-2"></i> AJUKAN RETUR BARANG
                                                    </a>
                                                @endif
                                            @else
                                                <div class="p-4 bg-red-50 rounded-2xl border border-red-100 flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                                                        <span class="text-[10px] font-black text-red-700 uppercase tracking-widest">Status Retur: {{ $buyReturn->status }}</span>
                                                    </div>
                                                    <a href="{{ route('orders.return', $item->id) }}" class="text-[10px] font-bold text-red-900 underline">Lihat Detail</a>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Rating & Review Section --}}
                                        @if($itemDelivered)
                                        <div class="mt-4 pt-4 border-t border-slate-200" id="review-section">
                                            <p class="text-sm font-bold text-slate-800 mb-3">Rating & Ulasan</p>
                                            @include('pembeli.produk.partials.review_pembeli', ['produk' => $produk])
                                        </div>
                                        @endif
                                    @else
                                        {{-- LAYOUT PENYEWAAN --}}
                                        @php
                                            $returnInfo = \App\Models\Pembeli\Return_pembeli::where('order_id', $pesanan->id)->where('type', 'sewa')->first();
                                            $rentalInfo = \App\Models\Pembeli\Rental_pembeli::where('order_id', $pesanan->id)->first();
                                            $canReturnRental = in_array($pesanan->status, ['selesai', 'sampai']) || ($rentalInfo && in_array($rentalInfo->status, ['active', 'selesai', 'completed']));
                                        @endphp
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 py-2">
                                            <div>
                                                <p class="text-xs text-slate-500 uppercase font-semibold">Durasi</p>
                                                <p class="font-medium">{{ $item->duration ?? '3' }} Hari</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 uppercase font-semibold">Mulai Sewa</p>
                                                <p class="font-medium">{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d M Y') : \Carbon\Carbon::parse($pesanan->created_at)->format('d M Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 uppercase font-semibold">Selesai Sewa</p>
                                                <p class="font-medium">{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->addDays($item->duration ?? 3)->format('d M Y') : \Carbon\Carbon::parse($pesanan->created_at)->addDays(3)->format('d M Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 uppercase font-semibold">Harga Sewa</p>
                                                <p class="font-medium text-emerald-600">Rp {{ number_format($item->harga) }}</p>
                                            </div>
                                            <div>
                                                @php
                                                    // Ambil deposit dari record return jika sudah ada, atau hitung manual
                                                    $deposit = ($returnInfo && $returnInfo->deposit_amount > 0) 
                                                        ? $returnInfo->deposit_amount 
                                                        : ($produk->escrow_amount > 0 ? $produk->escrow_amount : (($produk->buy_price ?? 0) * 0.25));
                                                    
                                                    // Jika tetap 0 tapi ada escrow_total di return, gunakan itu
                                                    if ($deposit <= 0 && $returnInfo && $returnInfo->escrow_total > 0) {
                                                        $deposit = $returnInfo->escrow_total;
                                                    }
                                                @endphp
                                                <p class="text-xs text-slate-500 uppercase font-semibold">Dana Jaminan (Escrow)</p>
                                                <p class="font-bold text-blue-600">Rp {{ number_format($deposit) }}</p>
                                                <p class="text-[8px] text-slate-400 mt-0.5 leading-tight">Akan dikembalikan utuh jika barang kembali aman.</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 uppercase font-semibold">Total Dibayar</p>
                                                <p class="font-bold text-slate-900">Rp {{ number_format($item->harga + $deposit) }}</p>
                                            </div>
                                        </div>

                                        {{-- Return & Review Section for Rental --}}
                                        <div class="mt-4 pt-4 border-t border-slate-200 space-y-4">
                                            @if($rentalInfo && $rentalInfo->condition_photo_handover)
                                                <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100">
                                                    <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest mb-2"><i class="bi bi-camera me-1"></i> Foto Kondisi Barang Saat Mulai Sewa</p>
                                                    <a href="{{ asset($rentalInfo->condition_photo_handover) }}" target="_blank" class="inline-block relative rounded-xl overflow-hidden border border-emerald-200 max-w-xs hover:opacity-95 transition shadow-sm">
                                                        <img src="{{ asset($rentalInfo->condition_photo_handover) }}" class="max-h-44 object-cover" style="max-width: 100%;">
                                                    </a>
                                                </div>
                                            @endif
                                            <div class="flex flex-col gap-3">
                                                @if(!$returnInfo)
                                                    @if($canReturnRental)
                                                        <a href="{{ route('orders.return', $item->id) }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                                                            <i data-lucide="rotate-ccw" class="w-3 h-3 me-2"></i> AJUKAN PENGEMBALIAN SEKARANG
                                                        </a>
                                                    @elseif($rentalInfo && $rentalInfo->status === 'completed')
                                                        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                            </div>
                                                            <p class="text-[10px] text-emerald-800 font-bold uppercase tracking-widest">Penyewaan Selesai</p>
                                                        </div>
                                                    @else
                                                        <div class="p-4 bg-slate-100 rounded-2xl border border-slate-200">
                                                            <p class="text-[10px] text-slate-500 font-bold leading-relaxed uppercase tracking-widest text-center">
                                                                <i data-lucide="clock" class="w-3 h-3 inline me-1"></i> Form pengembalian muncul setelah status "AKTIF"
                                                            </p>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-3">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center gap-2">
                                                                <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Pengembalian</span>
                                                            </div>
                                                            <span class="px-2 py-0.5 {{ $returnInfo->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($returnInfo->status === 'waiting_refund' ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-700') }} rounded text-[9px] font-bold uppercase">{{ str_replace('_', ' ', $returnInfo->status) }}</span>
                                                        </div>
                                                        
                                                        @if($returnInfo->status === 'completed')
                                                            <div class="space-y-2 pt-2 border-t border-dashed border-slate-200">
                                                                <div class="flex justify-between text-xs">
                                                                    <span class="text-slate-500">Dana Jaminan Awal</span>
                                                                    <span class="font-bold text-slate-800">Rp {{ number_format($returnInfo->deposit_amount) }}</span>
                                                                </div>
                                                                <div class="flex justify-between text-xs">
                                                                    <span class="text-slate-500">Total Denda (Telat + Rusak)</span>
                                                                    <span class="font-bold text-red-600">- Rp {{ number_format($returnInfo->total_fines) }}</span>
                                                                </div>
                                                                <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                                                                    <span class="text-sm font-bold text-emerald-700">Refund Cair ke Anda</span>
                                                                    <span class="text-lg font-black text-emerald-600">Rp {{ number_format($returnInfo->to_buyer) }}</span>
                                                                </div>
                                                                <p class="text-[9px] text-slate-400 text-center italic mt-2">Dana telah dikembalikan ke saldo/rekening Anda.</p>
                                                            </div>
                                                        @elseif($returnInfo->status === 'waiting_refund')
                                                            <div class="space-y-2 pt-2 border-t border-dashed border-slate-200">
                                                                <p class="text-[10px] text-slate-600 font-medium">Barang diterima seller. <strong>Menunggu proses transfer dana refund dari Admin.</strong></p>
                                                                <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                                                                    <span class="text-xs font-bold text-amber-700">Estimasi Refund</span>
                                                                    <span class="text-md font-black text-amber-600">Rp {{ number_format($returnInfo->to_buyer) }}</span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <p class="text-[10px] text-slate-500 italic">Barang dalam proses pengecekan oleh Admin & Penjual. Harap tunggu konfirmasi refund.</p>
                                                        @endif
                                                        <a href="{{ route('orders.return', $item->id) }}" class="mt-2 w-full inline-flex items-center justify-center rounded-xl border-2 border-slate-200 py-2 text-[10px] font-black text-slate-600 hover:bg-slate-50 transition uppercase tracking-widest">
                                                            Lihat Detail Pengembalian →
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            @if($pesanan->status == 'selesai')
                                            <div id="review-section">
                                                <p class="text-sm font-bold text-slate-800 mb-3">Rating & Ulasan Penyewaan</p>
                                                @include('pembeli.produk.partials.review_pembeli', ['produk' => $produk])
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- REVIEW TOKO -->
            @if($pesanan->status == 'selesai' && $pesanan->details->isNotEmpty())
                @php
                    $firstItem = $pesanan->details->first();
                    // Gunakan properti yang sesuai untuk mendapat store_id/seller_id, contoh sellerUserId()
                    $storeId = $firstItem->product->sellerUserId() ?? $firstItem->product->seller_id ?? $firstItem->product->user_id;
                    
                    $existingStoreReview = \App\Models\Pembeli\StoreRating_pembeli::where('user_id', \Illuminate\Support\Facades\Auth::id())
                        ->where('store_id', $storeId)
                        ->first();
                @endphp

                <div class="mt-8 pt-6 border-t border-slate-200">
                    <h2 class="font-semibold text-lg mb-4 text-slate-800">Rating & Ulasan Toko</h2>
                    @if($existingStoreReview)
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <div class="flex text-yellow-400 text-lg">
                                        @for($i=1; $i<=5; $i++)
                                            <span>{!! $i <= $existingStoreReview->rating ? '★' : '☆' !!}</span>
                                        @endfor
                                    </div>
                                    <span class="text-xs font-bold text-slate-400">{{ $existingStoreReview->created_at->format('d M Y') }}</span>
                                </div>
                                <p class="text-sm text-slate-700 font-medium">"{{ $existingStoreReview->comment }}"</p>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                            <form action="{{ route('store.review.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $pesanan->id }}">
                                <input type="hidden" name="store_id" value="{{ $storeId }}">
                                <input type="hidden" name="rating" id="store-rating-value" value="5">
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Berikan Rating Untuk Toko</label>
                                    <div class="flex gap-2 text-3xl" id="store-star-container">
                                        @for($i=1; $i<=5; $i++)
                                            <button type="button" 
                                                    onclick="setStoreRating({{ $i }})" 
                                                    class="store-star-btn text-yellow-400 transition-transform hover:scale-110 focus:outline-none"
                                                    data-value="{{ $i }}">
                                                ★
                                            </button>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase">Tulis Ulasan Toko</label>
                                    <textarea name="comment" rows="3" class="w-full rounded-2xl border-slate-200 text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Bagaimana pelayanan dari toko ini?" required></textarea>
                                </div>
                                
                                <button type="submit" class="w-full md:w-auto bg-emerald-600 text-white px-8 py-3 rounded-xl text-sm font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-100 transition-all">
                                    Kirim Ulasan Toko
                                </button>
                            </form>

                            <script>
                                function setStoreRating(value) {
                                    document.getElementById('store-rating-value').value = value;
                                    const stars = document.querySelectorAll('.store-star-btn');
                                    stars.forEach((star, index) => {
                                        if (index < value) {
                                            star.innerHTML = '★';
                                            star.classList.remove('text-slate-300');
                                            star.classList.add('text-yellow-400');
                                        } else {
                                            star.innerHTML = '☆';
                                            star.classList.remove('text-yellow-400');
                                            star.classList.add('text-slate-300');
                                        }
                                    });
                                }
                            </script>
                        </div>
                    @endif
                </div>
            @endif
            </div>

            <!-- ACTION -->
            <div class="flex flex-col gap-3">
                @if($pesanan->sellerOrders->isNotEmpty())
                    @foreach($pesanan->sellerOrders->where('status', 'shipped') as $sellerOrder)
                        <form action="{{ route('orders.confirm', $pesanan->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="seller_order_id" value="{{ $sellerOrder->id }}">
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-lg shadow-emerald-100 flex items-center justify-center gap-3">
                                <i data-lucide="package-check" class="w-5 h-5"></i>
                                Konfirmasi {{ $sellerOrder->seller_order_number ?? 'Seller Order' }} Diterima
                            </button>
                        </form>
                    @endforeach
                @elseif($pesanan->status == 'dikirim')
                    <form action="{{ route('orders.confirm', $pesanan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-lg shadow-emerald-100 flex items-center justify-center gap-3">
                            <i data-lucide="package-check" class="w-5 h-5"></i>
                            Konfirmasi Pesanan Diterima
                        </button>
                    </form>
                @endif

                @if(in_array($pesanan->status, ['menunggu', 'diproses']))
                    <form action="{{ route('orders.cancel', $pesanan->id) }}" method="POST">
                        @csrf
                        <button class="w-full bg-red-50 hover:bg-red-100 text-red-600 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition border border-red-100">
                            Batalkan Pesanan
                        </button>
                    </form>
                @endif
            </div>

        </div>


    </div>
</div>
@endsection

@section('extra_js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    function copyResi() {
        const el = document.getElementById('noResiText');
        if (!el) return;
        const text = el.innerText.trim();
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Nomor resi berhasil disalin: ' + text);
            }).catch(() => {
                prompt('Salin nomor resi:', text);
            });
        } else {
            prompt('Salin nomor resi:', text);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Data dari PHP
        let storeLat = {{ $pesanan->details->first()->product->store->latitude ?? 'null' }};
        let storeLng = {{ $pesanan->details->first()->product->store->longitude ?? 'null' }};
        let destLat = {{ $pesanan->latitude ?? 'null' }};
        let destLng = {{ $pesanan->longitude ?? 'null' }};
        
        const storeName = "{{ addslashes($pesanan->details->first()->product->store->nama_toko ?? 'Toko Campify') }}";
        const storeAddr = "{{ addslashes($pesanan->details->first()->product->store->alamat ?? '') }}";
        const buyerAddr = "{{ addslashes($pesanan->shipping_address . ', ' . $pesanan->shipping_district . ', ' . $pesanan->shipping_city) }}";
        const status = "{{ strtolower($pesanan->status ?? '') }}";

        const mapEl = document.getElementById('tracking-map');
        if (!mapEl) return;

        // Inisialisasi Map (Default Jawa Barat / Bandung - Jakarta)
        const map = L.map('tracking-map', {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView([storeLat || -6.9175, storeLng || 107.6191], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Toggle Map View
        const toggleBtn = document.getElementById('toggleMapBtn');
        const mapWrapper = document.getElementById('mapWrapper');
        const toggleText = document.getElementById('toggleMapText');
        let isMapOpen = true;

        if (toggleBtn && mapWrapper) {
            toggleBtn.addEventListener('click', function() {
                isMapOpen = !isMapOpen;
                if (isMapOpen) {
                    mapWrapper.style.display = 'block';
                    if (toggleText) toggleText.innerText = 'Sembunyikan Peta';
                    setTimeout(() => { map.invalidateSize(); }, 200);
                } else {
                    mapWrapper.style.display = 'none';
                    if (toggleText) toggleText.innerText = 'Tampilkan Peta';
                }
            });
        }

        // Custom Marker Icons
        const storeIcon = L.divIcon({ 
            className: 'custom-div-store', 
            html: `<div class="custom-map-pin"><span>🏪</span> <span>${storeName}</span></div>`, 
            iconSize: [null, null], 
            iconAnchor: [40, 20] 
        });

        const buyerIcon = L.divIcon({ 
            className: 'custom-div-buyer', 
            html: '<div class="custom-map-pin buyer"><span>📍</span> <span>Lokasi Anda</span></div>', 
            iconSize: [null, null], 
            iconAnchor: [40, 20] 
        });

        const courierIcon = L.divIcon({
            className: 'custom-div-courier',
            html: '<div class="custom-map-pin courier"><span>🚚</span> <span>Kurir Antar</span></div>',
            iconSize: [null, null],
            iconAnchor: [38, 20]
        });

        async function geocode(address) {
            if (!address) return null;
            try {
                let response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`);
                let data = await response.json();
                
                if (data.length === 0) {
                    const parts = address.split(',');
                    if (parts.length > 1) {
                        const simplified = parts.slice(-2).join(',').trim();
                        response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(simplified)}&limit=1`);
                        data = await response.json();
                    }
                }

                if (data.length > 0) {
                    return { lat: parseFloat(data[0].lat), lon: parseFloat(data[0].lon) };
                }
            } catch (e) { console.error("Geocoding error:", e); }
            return null;
        }

        async function initTracking() {
            // Geocoding fallback jika koordinat kosong di database
            if (!storeLat || !storeLng) {
                const geoStore = await geocode(storeAddr);
                if (geoStore) { storeLat = geoStore.lat; storeLng = geoStore.lon; }
            }
            if (!destLat || !destLng) {
                const geoDest = await geocode(buyerAddr);
                if (geoDest) { destLat = geoDest.lat; destLng = geoDest.lon; }
            }

            // Fallback default coordinates jika benar-benar kosong
            storeLat = storeLat || -6.9034;
            storeLng = storeLng || 107.6186;
            destLat = destLat || -6.9389;
            destLng = destLng || 107.6542;

            // Markers Toko & Pembeli
            const storeMarker = L.marker([storeLat, storeLng], {icon: storeIcon}).addTo(map);
            const buyerMarker = L.marker([destLat, destLng], {icon: buyerIcon}).addTo(map);

            // Rute Visual dengan efek glow ganda
            L.polyline([[storeLat, storeLng], [destLat, destLng]], {
                color: '#059669',
                weight: 8,
                opacity: 0.25
            }).addTo(map);

            const routeLine = L.polyline([[storeLat, storeLng], [destLat, destLng]], {
                color: '#10b981',
                weight: 3.5,
                dashArray: '8, 8',
                opacity: 0.95
            }).addTo(map);

            // Tentukan Posisi Paket berdasarkan status aktual
            let packagePos = [storeLat, storeLng];
            if (status === 'dikirim' || status === 'shipping') {
                packagePos = [(storeLat + destLat) / 2, (storeLng + destLng) / 2];
                L.marker(packagePos, {icon: courierIcon}).addTo(map);
            } else if (status === 'selesai' || status === 'delivered') {
                packagePos = [destLat, destLng];
                L.marker(packagePos, {icon: courierIcon}).addTo(map);
            } else {
                // Status diproses / menunggu: paket masih di toko
                L.marker(packagePos, {icon: courierIcon}).addTo(map);
            }
            
            // Zoom & fit bounds rute
            map.fitBounds(routeLine.getBounds(), { padding: [60, 60], maxZoom: 14 });
        }

        initTracking();
    });
</script>
@endsection
