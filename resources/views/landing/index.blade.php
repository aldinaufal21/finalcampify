@extends('layouts.app_pembeli')

@section('extra_css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .bg-emerald-gradient { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
    .bg-dark-gradient { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); }
    .bg-emerald-soft { background-color: #ecfdf5; border: 1px solid #d1fae5; color: #059669; }
    .card-modern {
        border-radius: 24px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease;
    }
    .card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 30px -10px rgba(16, 185, 129, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.03);
    }
</style>
@endsection

@section('content')
<div class="font-outfit bg-[#f8fafc] text-[#1e293b] selection:bg-[#10B981] selection:text-white antialiased">

    {{-- ==================== 1. HERO SECTION (Harmonis dengan Foto Danau Alpin & Alam) ==================== --}}
    <section class="relative min-h-[90vh] overflow-hidden">
        {{-- Hero Background Photography (Foto Danau Alpin Pilihan Pengguna) --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/images/hero_campify.jpg') }}" 
                 alt="Petualangan Alam Bebas Campify" 
                 class="w-full h-full object-cover object-center scale-100 transition-transform duration-1000 ease-out">
            {{-- Deep Forest / Slate Gradient Overlays for 100% Text Readability & Header Clarity --}}
            <div class="absolute inset-0 bg-gradient-to-r from-[#071a14]/95 via-[#071a14]/80 to-[#071a14]/20"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-[#071a14]/70"></div>
        </div>

        {{-- Container dengan Padding Top Aman agar Header Transparan Tidak Menutupi Teks --}}
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-36 sm:pt-40 lg:pt-44 pb-16 sm:pb-20">
            <div class="max-w-3xl">
                {{-- Tagline Badge --}}
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/15 backdrop-blur-md border border-white/25 text-emerald-300 text-xs sm:text-sm font-semibold mb-5 shadow-sm">
                    <span class="flex h-2 w-2 rounded-full bg-[#10B981] animate-pulse"></span>
                    <span>Platform Sewa & Jual Alat Camping #1 di Indonesia</span>
                </div>

                {{-- Hero Headline --}}
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-[1.12] mb-6 drop-shadow-sm">
                    Petualangan Nyaman, <br>
                    <span class="text-[#10B981]">
                        Tanpa Beban.
                    </span>
                </h1>

                {{-- Subheadline --}}
                <p class="text-base sm:text-xl text-slate-200 leading-relaxed mb-10 max-w-2xl font-normal drop-shadow-sm">
                    Lengkapi camping dan pendakianmu dengan gear terbaik. <strong class="text-white font-semibold">Sewa hemat harian</strong> bergaransi proteksi <span class="text-emerald-300 font-semibold underline decoration-emerald-400/50 underline-offset-4">escrow</span>, atau <strong class="text-white font-semibold">beli perlengkapan original</strong> dari mitra toko terpercaya.
                </p>

                {{-- Harmonious Dual CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-12">
                    <a href="{{ route('produk.rental') }}" 
                       class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-full bg-emerald-gradient text-white font-bold text-base shadow-lg shadow-emerald-500/25 transition-all duration-300 hover:scale-[1.02] active:scale-100">
                        <i data-lucide="tent" class="w-5 h-5"></i>
                        <span>Mulai Sewa Gear</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>

                    <a href="{{ route('produk.index') }}" 
                       class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-full bg-white/15 hover:bg-white/25 border border-white/30 text-white font-semibold text-base backdrop-blur-md transition-all duration-300 hover:border-white/50">
                        <i data-lucide="shopping-bag" class="w-5 h-5 text-emerald-300"></i>
                        <span>Beli Perlengkapan</span>
                    </a>
                </div>

                {{-- Trust Badges Strip --}}
                <div class="grid grid-cols-3 gap-4 sm:gap-6 pt-6 border-t border-white/20 max-w-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/15 backdrop-blur-sm border border-white/25 flex items-center justify-center text-emerald-300 flex-shrink-0">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs sm:text-sm font-bold text-white">Escrow Aman</div>
                            <div class="text-[11px] text-slate-300">Jaminan kembali 100%</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/15 backdrop-blur-sm border border-white/25 flex items-center justify-center text-emerald-300 flex-shrink-0">
                            <i data-lucide="sparkles" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs sm:text-sm font-bold text-white">Gear Terawat</div>
                            <div class="text-[11px] text-slate-300">Bersih & siap pakai</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/15 backdrop-blur-sm border border-white/25 flex items-center justify-center text-emerald-300 flex-shrink-0">
                            <i data-lucide="box" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs sm:text-sm font-bold text-white">Stok Real-time</div>
                            <div class="text-[11px] text-slate-300">Cek langsung di web</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ==================== 2. STATS STRIP (Clean & Modern) ==================== --}}
    <section class="bg-white border-b border-slate-100 py-7 relative z-20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center divide-y md:divide-y-0 md:divide-x divide-slate-100">
                <div class="pt-2 md:pt-0 flex flex-col items-center">
                    <div class="text-3xl sm:text-4xl font-black text-[#1e293b] tracking-tight">
                        {{ max($totalProductsCount, 50) }}<span class="text-emerald-500 font-extrabold">+</span>
                    </div>
                    <div class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Peralatan Outdoor Tersedia</div>
                </div>

                <div class="pt-4 md:pt-0 flex flex-col items-center">
                    <div class="text-3xl sm:text-4xl font-black text-[#1e293b] tracking-tight">
                        {{ max($totalStoresCount, 12) }}<span class="text-emerald-500 font-extrabold">+</span>
                    </div>
                    <div class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Mitra Toko & Rental Resmi</div>
                </div>

                <div class="pt-4 md:pt-0 flex flex-col items-center">
                    <div class="text-3xl sm:text-4xl font-black text-[#10B981] tracking-tight">
                        100%
                    </div>
                    <div class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Garansi Deposit Escrow Aman</div>
                </div>

                <div class="pt-4 md:pt-0 flex flex-col items-center">
                    <div class="text-3xl sm:text-4xl font-black text-[#1e293b] tracking-tight flex items-center justify-center gap-1">
                        <span>4.9</span>
                        <span class="text-amber-400 text-2xl leading-none">★</span>
                    </div>
                    <div class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Rating Kepuasan Petualang</div>
                </div>
            </div>
        </div>
    </section>


    {{-- ==================== 3. DUAL EXPERIENCE (Sewa vs Beli) ==================== --}}
    <section class="py-24 bg-[#f8fafc] relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-widest bg-emerald-soft px-4 py-1.5 rounded-full inline-block mb-3">
                    DUA CARA PETUALANGAN
                </span>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-[#1e293b] tracking-tight">
                    Sewa Hemat atau Beli Permanen?
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-500">
                    Pilih opsi yang paling pas dengan frekuensi pendakian dan kebutuhan petualanganmu.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 lg:gap-10">
                {{-- CARD 1: SEWA ALAT (Emerald Accent) --}}
                <div class="card-modern bg-white border border-emerald-100 p-8 lg:p-10 flex flex-col">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-soft text-xs font-bold mb-6 w-fit">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#10B981]"></i>
                        <span>OPSI PALING FLEKSIBEL</span>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#1e293b] mb-3">Sewa Alat Camping</h3>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed mb-6">
                        Ideal untuk camping musiman, acara kampus, atau liburan keluarga. Nikmati gear terbaik tanpa perlu biaya beli yang mahal.
                    </p>

                    <ul class="space-y-3.5 mb-8 text-sm text-slate-600 flex-grow">
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 text-[#10B981] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </div>
                            <span><strong>Bayar harian hemat</strong> sesuai lama waktu petualangan</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 text-[#10B981] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </div>
                            <span>Tanpa pusing mencuci, merawat, dan menyimpan gear di rumah</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 text-[#10B981] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </div>
                            <span><strong>Uang jaminan (escrow)</strong> aman dan dikembalikan utuh</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 text-[#10B981] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </div>
                            <span>Kondisi gear terinspeksi, bersih, wangi, dan siap langsung pakai</span>
                        </li>
                    </ul>

                    <a href="{{ route('produk.rental') }}" 
                       class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-full bg-emerald-gradient text-white font-bold text-sm shadow-md shadow-emerald-500/20 transition-all hover:scale-[1.01]">
                        <span>Lihat Katalog Sewa</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                {{-- CARD 2: BELI ALAT (Slate Dark Accent) --}}
                <div class="card-modern bg-white border border-slate-200/80 p-8 lg:p-10 flex flex-col">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold mb-6 w-fit">
                        <i data-lucide="award" class="w-3.5 h-3.5 text-slate-700"></i>
                        <span>INVESTASI HOBI PETUALANG</span>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#1e293b] mb-3">Beli Perlengkapan Baru</h3>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed mb-6">
                        Khusus untuk kamu yang rutin mendaki setiap bulan atau hobi solo camping. Miliki gear impian original milik sendiri.
                    </p>

                    <ul class="space-y-3.5 mb-8 text-sm text-slate-600 flex-grow">
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </div>
                            <span><strong>100% Produk Original</strong> dari brand outdoor terkemuka</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </div>
                            <span>Siap digunakan kapan pun tanpa batas durasi penyewaan</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </div>
                            <span>Bebas kustomisasi dan modifikasi perlengkapan sesukamu</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </div>
                            <span>Pengiriman cepat dengan proteksi barang rusak & retur</span>
                        </li>
                    </ul>

                    <a href="{{ route('produk.index') }}" 
                       class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-full bg-[#1e293b] hover:bg-[#0f172a] text-white font-bold text-sm shadow-md transition-all hover:scale-[1.01]">
                        <span>Beli Perlengkapan Baru</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>


    {{-- ==================== 4. WHY CAMPIFY (4 VALUE PILLARS) ==================== --}}
    <section class="py-20 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-widest bg-emerald-soft px-4 py-1.5 rounded-full inline-block mb-3">
                    KEUNGGULAN UTAMA
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1e293b] tracking-tight">
                    Mengapa Harus di Campify?
                </h2>
                <p class="mt-3 text-slate-500 text-sm sm:text-base">
                    Platform yang dibuat khusus untuk menyelesaikan keraguan sewa outdoor: jaminan aman, alat bersih, dan stok pasti.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Feature 1 --}}
                <div class="card-modern p-6 bg-[#f8fafc] border border-slate-100 hover:border-emerald-200">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#10B981] flex items-center justify-center mb-5 shadow-sm">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-[#1e293b] mb-2">Proteksi Jaminan Escrow</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Uang jaminan sewa ditampung sistem rekening penjamin dan otomatis dikembalikan saat barang kembali tanpa kerusakan.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="card-modern p-6 bg-[#f8fafc] border border-slate-100 hover:border-emerald-200">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#10B981] flex items-center justify-center mb-5 shadow-sm">
                        <i data-lucide="sparkles" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-[#1e293b] mb-2">Bersih & Siap Pakai</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Tenda, carrier, dan peralatan masak dipastikan dicuci bersih, wangi, kering, dan lolos uji fungsi sebelum diserahkan.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="card-modern p-6 bg-[#f8fafc] border border-slate-100 hover:border-emerald-200">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#10B981] flex items-center justify-center mb-5 shadow-sm">
                        <i data-lucide="map-pin" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-[#1e293b] mb-2">Toko Rental Terdekat</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Temukan toko rental terdekat di kotamu dengan integrasi geocoding koordinat agar pengambilan barang lebih cepat dan hemat ongkir.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="card-modern p-6 bg-[#f8fafc] border border-slate-100 hover:border-emerald-200">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#10B981] flex items-center justify-center mb-5 shadow-sm">
                        <i data-lucide="box" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-[#1e293b] mb-2">Stok Real-Time</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Jumlah stok produk ditampilkan transparan. Tidak ada lagi drama kehabisan tenda saat sudah tiba di toko sewa.
                    </p>
                </div>
            </div>
        </div>
    </section>


    {{-- ==================== 5. CATEGORIES SHOWCASE ==================== --}}
    <section class="py-24 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest bg-emerald-soft px-4 py-1.5 rounded-full inline-block mb-3">
                        KATALOG PERLENGKAPAN
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1e293b] tracking-tight">
                        Eksplorasi Berdasarkan Kategori
                    </h2>
                </div>
                <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#10B981] hover:text-[#059669] transition-colors">
                    <span>Lihat Semua Produk</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredCategories as $cat)
                <a href="{{ route('produk.category', $cat['key']) }}" 
                   class="card-modern group relative overflow-hidden h-72 border border-slate-200/80 shadow-sm hover:shadow-lg">
                    {{-- Background Photo Produk Asli --}}
                    <img src="{{ $cat['image'] }}" 
                         alt="{{ $cat['name'] }}" 
                         class="w-full h-full object-cover brightness-90 group-hover:scale-105 group-hover:brightness-100 transition-all duration-500"
                         onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=Produk+Outdoor';">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a]/95 via-[#0f172a]/45 to-transparent"></div>

                    {{-- Content Bottom --}}
                    <div class="absolute inset-0 p-6 flex flex-col justify-end">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-xl font-bold text-white group-hover:text-emerald-300 transition-colors">
                                {{ $cat['name'] }}
                            </h4>
                            <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white group-hover:bg-[#10B981] transition-colors">
                                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
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


    {{-- ==================== 6. HOW IT WORKS (3 SIMPLE STEPS) ==================== --}}
    <section class="py-24 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-widest bg-emerald-soft px-4 py-1.5 rounded-full inline-block mb-3">
                    ALUR SEWA MUDAH
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1e293b] tracking-tight">
                    3 Langkah Sewa Alat Camping
                </h2>
                <p class="mt-3 text-slate-500 text-sm sm:text-base">
                    Praktis dan cepat dari ponsel sebelum jadwal campingmu tiba.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Step 1 --}}
                <div class="card-modern p-8 bg-[#f8fafc] border border-slate-100 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-gradient text-white font-black text-xl flex items-center justify-center mx-auto mb-6 shadow-md shadow-emerald-500/20">
                        1
                    </div>
                    <h4 class="text-lg font-bold text-[#1e293b] mb-3">Pilih Gear & Atur Tanggal</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Tentukan tenda, carrier, atau alat masak yang kamu butuhkan. Tentukan tanggal mulai dan berapa hari durasi sewamu.
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="card-modern p-8 bg-[#f8fafc] border border-slate-100 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-gradient text-white font-black text-xl flex items-center justify-center mx-auto mb-6 shadow-md shadow-emerald-500/20">
                        2
                    </div>
                    <h4 class="text-lg font-bold text-[#1e293b] mb-3">Titip Jaminan Escrow</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Lakukan pembayaran sewa dan titip uang deposit jaminan dengan aman di rekening penjamin Campify. Toko menyiapkan barangmu.
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="card-modern p-8 bg-[#f8fafc] border border-slate-100 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-gradient text-white font-black text-xl flex items-center justify-center mx-auto mb-6 shadow-md shadow-emerald-500/20">
                        3
                    </div>
                    <h4 class="text-lg font-bold text-[#1e293b] mb-3">Kembalikan & Jaminan Cair</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Nikmati alam bebas! Setelah camping selesai, kembalikan barang ke toko dan uang jaminanmu langsung dikembalikan utuh 100%.
                    </p>
                </div>
            </div>
        </div>
    </section>


    {{-- ==================== 7. FEATURED PRODUCTS (SEWA & BELI) ==================== --}}
    @if($rentalProducts->isNotEmpty() || $buyProducts->isNotEmpty())
    <section class="py-24 bg-[#f8fafc] border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest bg-emerald-soft px-4 py-1.5 rounded-full inline-block mb-3">
                        PERALATAN PILIHAN
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1e293b] tracking-tight">
                        Gear Siap Menemani Petualanganmu
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('produk.rental') }}" class="px-5 py-2.5 rounded-full bg-emerald-soft text-xs font-bold hover:bg-emerald-100 transition">
                        Semua Sewa
                    </a>
                    <a href="{{ route('produk.index') }}" class="px-5 py-2.5 rounded-full bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                        Semua Beli
                    </a>
                </div>
            </div>

            {{-- Grid Produk --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($rentalProducts->take(2)->concat($buyProducts->take(2)) as $item)
                @php
                    $isRent = $item->jenis_produk === 'sewa' || $item->rent_price > 0;
                    $detailUrl = $isRent ? route('produk.detail.rent', $item->id) : route('produk.detail.buy', $item->id);
                    $priceDisplay = $isRent 
                        ? 'Rp ' . number_format($item->rent_price ?: $item->harga, 0, ',', '.') . ' /hari'
                        : 'Rp ' . number_format($item->buy_price ?: $item->harga, 0, ',', '.');
                @endphp
                <div class="card-modern bg-white border border-slate-100 overflow-hidden flex flex-col">
                    {{-- Image --}}
                    <div class="relative h-48 bg-slate-100 overflow-hidden">
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl opacity-30">🏕️</div>
                        @endif

                        {{-- Type Badge --}}
                        <div class="absolute top-3 left-3">
                            @if($isRent)
                                <span class="px-3 py-1 rounded-full bg-emerald-gradient text-white text-[11px] font-bold shadow-sm">
                                    🏕️ SEWA
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-slate-800 text-white text-[11px] font-bold shadow-sm">
                                    📦 BELI
                                </span>
                            @endif
                        </div>

                        {{-- Stock Badge --}}
                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 rounded-full bg-white/95 backdrop-blur-md text-slate-700 text-[10px] font-bold shadow-sm border border-slate-100">
                                Stok: {{ $item->stok }}
                            </span>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="text-[11px] text-[#10B981] font-bold uppercase tracking-wider mb-1">
                            {{ ucfirst(str_replace('_', ' ', $item->kategori ?: 'Outdoor Gear')) }}
                        </div>
                        <h4 class="text-base font-bold text-[#1e293b] mb-2 line-clamp-1 hover:text-[#10B981] transition-colors">
                            {{ $item->nama_produk }}
                        </h4>
                        
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <div class="text-[11px] text-slate-400 font-medium">Harga</div>
                                <div class="text-sm font-extrabold text-[#1e293b]">{{ $priceDisplay }}</div>
                            </div>
                            <a href="{{ $detailUrl }}" class="px-4 py-2 rounded-xl bg-emerald-soft hover:bg-emerald-600 hover:text-white text-xs font-bold transition-all">
                                Detail →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif


    {{-- ==================== 8. SELLER CALLOUT SECTION (Identik Dark Gradient Seller) ==================== --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-modern bg-dark-gradient text-white p-8 sm:p-12 lg:p-16 relative overflow-hidden shadow-xl border-0">
                <div class="max-w-2xl relative z-10">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-4 border border-white/15">
                        <i data-lucide="store" class="w-3.5 h-3.5"></i>
                        <span>MITRA PENJUAL & RENTAL</span>
                    </span>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                        Punya Alat Camping yang Menganggur di Hari Kerja?
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-8">
                        Buka toko rentalmu sendiri di Campify! Jangkau ribuan pendaki dan camper di kotamu. Dilengkapi manajemen stok otomatis, proteksi denda keterlambatan, dan pencairan dana yang aman.
                    </p>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full bg-emerald-gradient text-white hover:opacity-95 font-extrabold text-sm shadow-lg shadow-emerald-500/25 transition-all">
                            <span>Buka Toko Gratis di Campify</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 text-white"></i>
                        </a>
                        <span class="text-xs text-slate-400 text-center sm:text-left">
                            ✓ Pendaftaran instan tanpa biaya langganan
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ==================== 9. FAQ SECTION (Light & Clean) ==================== --}}
    <section class="py-20 bg-[#f8fafc] border-t border-slate-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-xs font-bold uppercase tracking-widest bg-emerald-soft px-4 py-1.5 rounded-full inline-block mb-3">
                    TANYA JAWAB
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1e293b] tracking-tight">
                    Pertanyaan yang Sering Diajukan
                </h2>
            </div>

            <div class="space-y-4">
                {{-- FAQ 1 --}}
                <div class="card-modern p-6 bg-white border border-slate-100 shadow-sm">
                    <h4 class="text-base sm:text-lg font-bold text-[#1e293b] mb-2 flex items-center justify-between">
                        <span>Apa itu uang jaminan (escrow) pada saat sewa?</span>
                        <i data-lucide="help-circle" class="w-5 h-5 text-[#10B981] flex-shrink-0"></i>
                    </h4>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Uang jaminan (escrow deposit) adalah dana titipan keamanan yang disetor penyewa untuk menjamin keutuhan barang rental. Uang ini disimpan aman di sistem rekening bersama Campify dan akan dikembalikan 100% setelah barang dikembalikan dalam kondisi baik.
                    </p>
                </div>

                {{-- FAQ 2 --}}
                <div class="card-modern p-6 bg-white border border-slate-100 shadow-sm">
                    <h4 class="text-base sm:text-lg font-bold text-[#1e293b] mb-2 flex items-center justify-between">
                        <span>Apakah saya perlu memverifikasi KTP untuk menyewa alat?</span>
                        <i data-lucide="help-circle" class="w-5 h-5 text-[#10B981] flex-shrink-0"></i>
                    </h4>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Ya. Untuk menjaga keamanan seluruh ekosistem rental dan melindungi toko mitra, penyewa wajib mengunggah foto identitas KTP yang terverifikasi sebelum pesanan sewa diproses.
                    </p>
                </div>

                {{-- FAQ 3 --}}
                <div class="card-modern p-6 bg-white border border-slate-100 shadow-sm">
                    <h4 class="text-base sm:text-lg font-bold text-[#1e293b] mb-2 flex items-center justify-between">
                        <span>Bagaimana jika barang yang saya sewa mengalami kerusakan atau terlambat?</span>
                        <i data-lucide="help-circle" class="w-5 h-5 text-[#10B981] flex-shrink-0"></i>
                    </h4>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Biaya ganti rugi atau denda keterlambatan akan dihitung secara transparan dan adil saat barang dikembalikan, yang kemudian dipotong dari uang deposit escrow. Sisa jaminan tetap akan dikembalikan ke rekening penyewa.
                    </p>
                </div>
            </div>
        </div>
    </section>


    {{-- ==================== 10. FINAL BOTTOM CALL TO ACTION ==================== --}}
    <section class="py-20 bg-dark-gradient text-center relative overflow-hidden">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight mb-5">
                Siap Memulai Petualanganmu?
            </h2>
            <p class="text-slate-300 text-base sm:text-lg mb-8 max-w-xl mx-auto">
                Ratusan perlengkapan camping berkualitas menantimu. Sewa hemat sekarang atau lengkapi perlengkapan impianmu.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('produk.rental') }}" class="w-full sm:w-auto px-8 py-4 rounded-full bg-emerald-gradient text-white font-bold text-sm shadow-lg shadow-emerald-500/25 transition hover:scale-[1.02]">
                    Jelajahi Alat Sewa 🏕️
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 rounded-full bg-white/10 hover:bg-white/20 border border-white/25 text-white font-semibold text-sm transition">
                    Masuk ke Akun
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
