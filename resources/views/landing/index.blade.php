@extends('layouts.app_pembeli')

@section('content')
<div class="bg-slate-50 text-slate-800 antialiased">

    {{-- ==================== 1. HERO SECTION ==================== --}}
    <section class="relative min-h-[85vh] lg:min-h-[90vh] flex items-center overflow-hidden">
        {{-- Background Image dengan Fallback Unsplash HD --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ file_exists(public_path('assets/images/hero_campify.jpg')) ? asset('assets/images/hero_campify.jpg') : 'https://images.unsplash.com/photo-1510312305653-8ed496efae75?auto=format&fit=crop&w=1920&q=80' }}" 
                 alt="Petualangan Alam Bebas Campify" 
                 class="w-full h-full object-cover object-center brightness-90">
            {{-- Gradient overlay lembut untuk keterbacaan teks maksimal --}}
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-slate-950/40"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-28 pb-16">
            <div class="max-w-2xl">
                {{-- Badge Tagline --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-emerald-300 text-xs font-semibold mb-6 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Platform Sewa & Jual Alat Camping #1</span>
                </div>

                {{-- Headline --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-tight mb-5 drop-shadow-sm">
                    Petualangan Nyaman, <br>
                    <span class="text-emerald-400">Tanpa Beban.</span>
                </h1>

                {{-- Subheadline --}}
                <p class="text-base sm:text-lg text-slate-200 leading-relaxed mb-8 font-normal drop-shadow">
                    Lengkapi persiapan mendaki dan campingmu dengan perlengkapan terbaik. <strong class="text-white">Sewa hemat harian</strong> bergaransi proteksi <span class="text-emerald-300 font-semibold underline decoration-emerald-400/50 underline-offset-4">escrow</span>, atau <strong class="text-white">beli perlengkapan original</strong> dari mitra toko terpercaya.
                </p>

                {{-- Dual CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-10">
                    <a href="{{ route('produk.rental') }}" 
                       class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-lg shadow-emerald-900/30 transition-all hover:scale-105 active:scale-95">
                        <i data-lucide="tent" class="w-4 h-4"></i>
                        <span>Mulai Sewa Gear</span>
                    </a>

                    <a href="{{ route('produk.index') }}" 
                       class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-white/15 hover:bg-white/25 border border-white/30 text-white font-semibold text-sm backdrop-blur-md transition-all hover:scale-105 active:scale-95">
                        <i data-lucide="shopping-bag" class="w-4 h-4 text-emerald-300"></i>
                        <span>Beli Perlengkapan</span>
                    </a>
                </div>

                {{-- Trust Pillars Strip --}}
                <div class="grid grid-cols-3 gap-3 pt-6 border-t border-white/20">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-emerald-400 shrink-0">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white leading-tight">Escrow Aman</div>
                            <div class="text-[10px] text-slate-300">Jaminan kembali 100%</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-emerald-400 shrink-0">
                            <i data-lucide="sparkles" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white leading-tight">Gear Terawat</div>
                            <div class="text-[10px] text-slate-300">Bersih & siap pakai</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-emerald-400 shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white leading-tight">Toko Terverifikasi</div>
                            <div class="text-[10px] text-slate-300">Mitra rental resmi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== 2. STATS STRIP ==================== --}}
    <section class="bg-white border-b border-slate-100 py-6 relative z-10 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center divide-y md:divide-y-0 md:divide-x divide-slate-100">
                <div class="pt-2 md:pt-0">
                    <div class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ max($totalProductsCount ?? 0, 50) }}<span class="text-emerald-600">+</span>
                    </div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">Peralatan Outdoor Tersedia</div>
                </div>

                <div class="pt-4 md:pt-0">
                    <div class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ max($totalStoresCount ?? 0, 10) }}<span class="text-emerald-600">+</span>
                    </div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">Mitra Toko & Rental Resmi</div>
                </div>

                <div class="pt-4 md:pt-0">
                    <div class="text-3xl font-black text-emerald-600 tracking-tight">
                        100%
                    </div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">Garansi Deposit Escrow Aman</div>
                </div>

                <div class="pt-4 md:pt-0">
                    <div class="text-3xl font-black text-slate-900 tracking-tight flex items-center justify-center gap-1">
                        <span>4.9</span>
                        <span class="text-amber-400 text-xl">★</span>
                    </div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">Rating Kepuasan Petualang</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== 3. DUAL EXPERIENCE (Sewa vs Beli) ==================== --}}
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3.5 py-1.5 rounded-full inline-block mb-3 border border-emerald-100">
                    DUA CARA PETUALANGAN
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Sewa Hemat atau Beli Permanen?
                </h2>
                <p class="mt-3 text-sm sm:text-base text-slate-500">
                    Pilih opsi yang paling pas dengan jadwal camping dan kebutuhan petualanganmu.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                {{-- CARD SEWA --}}
                <div class="bg-white rounded-3xl border border-emerald-100 p-8 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold mb-4">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            <span>OPSI PALING HEMAT & FLEKSIBEL</span>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Sewa Alat Camping</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">
                            Pilihan cerdas untuk pendakian musiman, camping akhir pekan, atau acara kelompok. Nikmati gear terbaik tanpa beban beli mahal.
                        </p>

                        <ul class="space-y-3 mb-8 text-sm text-slate-600">
                            <li class="flex items-start gap-3">
                                <span class="text-emerald-600 font-bold">✓</span>
                                <span><strong>Bayar harian hemat</strong> sesuai lama waktu camping</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-emerald-600 font-bold">✓</span>
                                <span>Tanpa repot mencuci, merawat, dan menyimpan gear di rumah</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-emerald-600 font-bold">✓</span>
                                <span><strong>Dana jaminan (escrow)</strong> aman dan dikembalikan utuh</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-emerald-600 font-bold">✓</span>
                                <span>Kondisi gear teruji, bersih, wangi, dan siap langsung pakai</span>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('produk.rental') }}" 
                       class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-100 transition-all">
                        <span>Lihat Katalog Sewa</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                {{-- CARD BELI --}}
                <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold mb-4">
                            <i data-lucide="award" class="w-3.5 h-3.5"></i>
                            <span>INVESTASI HOBI PETUALANG</span>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Beli Perlengkapan Baru</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">
                            Khusus untuk kamu yang rutin mendaki setiap bulan atau hobi solo camping. Miliki gear impian original milik sendiri.
                        </p>

                        <ul class="space-y-3 mb-8 text-sm text-slate-600">
                            <li class="flex items-start gap-3">
                                <span class="text-slate-700 font-bold">✓</span>
                                <span><strong>100% Produk Original</strong> dari brand outdoor terkemuka</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-slate-700 font-bold">✓</span>
                                <span>Siap digunakan kapan pun tanpa batas durasi penyewaan</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-slate-700 font-bold">✓</span>
                                <span>Bebas kustomisasi dan penyesuaian gear sesukamu</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-slate-700 font-bold">✓</span>
                                <span>Pengiriman aman ke seluruh Indonesia dengan proteksi retur</span>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('produk.index') }}" 
                       class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm shadow-md transition-all">
                        <span>Beli Perlengkapan Baru</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== 4. KEUNGGULAN UTAMA ==================== --}}
    <section class="py-16 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3.5 py-1.5 rounded-full inline-block mb-3 border border-emerald-100">
                    KEUNGGULAN UTAMA
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    Mengapa Memilih Campify?
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Solusi sewa dan jual beli alat outdoor yang transparan, aman, dan tanpa rasa cemas.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-1.5">Proteksi Dana Escrow</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Dana deposit sewa disimpan aman di sistem penjamin dan otomatis dicairkan kembali saat barang kembali dalam kondisi baik.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                        <i data-lucide="sparkles" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-1.5">Bersih & Higienis</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Semua tenda, sleeping bag, dan alat masak dipastikan telah dicuci bersih dan diinspeksi fungsinya sebelum diserahkan.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                        <i data-lucide="map-pin" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-1.5">Toko Rental Terdekat</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Temukan mitra rental di kotamu untuk kemudahan pickup langsung atau pengiriman hemat biaya via kurir lokal.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                        <i data-lucide="box" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-1.5">Stok Real-Time</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Ketersediaan unit tampil transparan dan akurat. Tidak ada lagi drama kehabisan perlengkapan di hari keberangkatan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== 5. KATEGORI POPULER ==================== --}}
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3.5 py-1.5 rounded-full inline-block mb-3 border border-emerald-100">
                        KATALOG PERLENGKAPAN
                    </span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        Eksplorasi Kategori Perlengkapan
                    </h2>
                </div>
                <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-emerald-600 hover:text-emerald-700 transition">
                    <span>Lihat Semua Produk</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredCategories as $cat)
                <a href="{{ route('produk.category', $cat['key']) }}" 
                   class="group relative overflow-hidden rounded-3xl h-64 border border-slate-200/80 shadow-sm hover:shadow-lg transition-all duration-300">
                    <img src="{{ $cat['image'] }}" 
                         alt="{{ $cat['name'] }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/40 to-transparent"></div>

                    <div class="absolute inset-0 p-6 flex flex-col justify-end">
                        <div class="flex items-center justify-between mb-1.5">
                            <h4 class="text-xl font-bold text-white group-hover:text-emerald-300 transition-colors">
                                {{ $cat['name'] }}
                            </h4>
                            <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white group-hover:bg-emerald-600 transition-colors">
                                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                            </div>
                        </div>
                        <p class="text-xs text-slate-300 line-clamp-2 leading-relaxed">
                            {{ $cat['desc'] }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== 6. CARA KERJA (3 LANGKAH) ==================== --}}
    <section class="py-20 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3.5 py-1.5 rounded-full inline-block mb-3 border border-emerald-100">
                    ALUR MUDAH
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    3 Langkah Sewa Alat Camping
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Praktis dan cepat dari ponsel sebelum jadwal campingmu tiba.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 text-center flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white font-black text-xl flex items-center justify-center mb-5 shadow-md shadow-emerald-100">
                        1
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Pilih Gear & Durasi</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Tentukan tenda, carrier, atau alat masak yang kamu butuhkan. Atur tanggal mulai dan durasi sewa harian.
                    </p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 text-center flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white font-black text-xl flex items-center justify-center mb-5 shadow-md shadow-emerald-100">
                        2
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Titip Jaminan Escrow</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Bayar sewa dan titip uang deposit jaminan dengan aman melalui sistem penjamin Campify. Toko menyiapkan gear pesananmu.
                    </p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 text-center flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white font-black text-xl flex items-center justify-center mb-5 shadow-md shadow-emerald-100">
                        3
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Kembalikan & Jaminan Cair</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Nikmati alam bebas! Setelah camping selesai, kembalikan barang dan uang depositmu langsung dikembalikan utuh 100%.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== 7. PRODUK PILIHAN ==================== --}}
    @php
        $highlightedProducts = $rentalProducts->take(2)->concat($buyProducts->take(2));
    @endphp
    @if($highlightedProducts->isNotEmpty())
    <section class="py-20 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3.5 py-1.5 rounded-full inline-block mb-3 border border-emerald-100">
                        PRODUK PILIHAN
                    </span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        Gear Siap Menemani Petualanganmu
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('produk.rental') }}" class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold hover:bg-emerald-100 transition border border-emerald-200">
                        Katalog Sewa
                    </a>
                    <a href="{{ route('produk.index') }}" class="px-4 py-2 rounded-xl bg-white text-slate-700 text-xs font-bold hover:bg-slate-100 transition border border-slate-200">
                        Katalog Beli
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($highlightedProducts as $product)
                    @include('components.product-card', [
                        'product' => $product,
                        'isWishlisted' => false
                    ])
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ==================== 8. SELLER CALLOUT ==================== --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-3xl text-white p-8 sm:p-12 lg:p-14 relative overflow-hidden shadow-xl">
                <div class="max-w-2xl relative z-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-4 border border-white/15">
                        <i data-lucide="store" class="w-3.5 h-3.5"></i>
                        <span>MITRA PENJUAL & RENTAL</span>
                    </span>

                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mb-3">
                        Punya Alat Camping yang Menganggur di Rumah?
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-6">
                        Buka toko rentalmu sendiri di Campify! Jangkau ratusan pendaki di kotamu. Nikmati sistem escrow anti-rugi, verifikasi KTP penyewa, dan pencairan dana langsung ke rekeningmu.
                    </p>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-lg shadow-emerald-900/40 transition-all hover:scale-105 active:scale-95">
                            <span>Buka Toko Gratis di Campify</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                        <span class="text-xs text-slate-400 text-center sm:text-left">
                            ✓ Pendaftaran instan tanpa biaya langganan
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== 9. FAQ SECTION ==================== --}}
    <section class="py-16 bg-slate-50 border-t border-slate-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-xs font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3.5 py-1.5 rounded-full inline-block mb-3 border border-emerald-100">
                    TANYA JAWAB
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Pertanyaan yang Sering Diajukan
                </h2>
            </div>

            <div class="space-y-4">
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                    <h4 class="text-base font-bold text-slate-900 mb-2 flex items-center justify-between">
                        <span>Apa itu uang jaminan (escrow) saat menyewa alat?</span>
                        <i data-lucide="help-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    </h4>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Uang jaminan (escrow deposit) adalah dana titipan keamanan yang disetor penyewa untuk menjamin keutuhan barang rental. Uang ini disimpan aman di sistem rekening bersama Campify dan akan dikembalikan 100% setelah barang dikembalikan dalam kondisi baik.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                    <h4 class="text-base font-bold text-slate-900 mb-2 flex items-center justify-between">
                        <span>Apakah saya perlu memverifikasi KTP untuk menyewa alat?</span>
                        <i data-lucide="help-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    </h4>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Ya. Demi menjaga keamanan bersama dan melindungi toko mitra, penyewa wajib mengunggah verifikasi KTP sebelum pesanan sewa diproses.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                    <h4 class="text-base font-bold text-slate-900 mb-2 flex items-center justify-between">
                        <span>Bagaimana jika barang yang saya sewa mengalami kerusakan atau telat?</span>
                        <i data-lucide="help-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    </h4>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Biaya perbaikan atau denda keterlambatan dihitung secara transparan dan dipotong langsung dari deposit jaminan. Sisa dana jaminan akan langsung dicairkan ke rekeningmu.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== 10. FINAL BOTTOM CALL TO ACTION ==================== --}}
    <section class="py-16 bg-slate-900 text-center relative overflow-hidden">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight mb-4">
                Siap Memulai Petualanganmu?
            </h2>
            <p class="text-slate-300 text-sm sm:text-base mb-8 max-w-lg mx-auto">
                Ratusan perlengkapan camping berkualitas menantimu. Sewa hemat sekarang atau lengkapi perlengkapan impianmu.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('produk.rental') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-lg shadow-emerald-900/40 transition hover:scale-105 active:scale-95">
                    Jelajahi Alat Sewa 🏕️
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-sm transition hover:scale-105 active:scale-95">
                    Masuk ke Akun
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
