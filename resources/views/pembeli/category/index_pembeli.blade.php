@extends('layouts.app_pembeli')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4">

    <!-- BREADCRUMB / BACK -->
    <div class="mb-6">
        <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-2 text-sm text-emerald-600 hover:text-emerald-800 font-semibold transition-colors">
            ← Kembali ke Katalog Produk
        </a>
    </div>

    <!-- HEADER & TABS -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6 border-b border-slate-200 pb-8">
        <div>
            <span class="inline-block text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full mb-3">
                Kategori Produk
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 uppercase tracking-tight">
                {{ $category }}
            </h1>
            <p class="text-sm text-slate-500 mt-2">
                Menampilkan <span class="font-bold text-slate-800">{{ $products->count() }}</span> produk dalam kategori ini
            </p>
        </div>

        <!-- TYPE FILTER TABS -->
        <div class="flex items-center gap-2 bg-slate-100 p-1.5 rounded-2xl border border-slate-200 self-start md:self-auto">
            <a href="{{ route('produk.category', ['category' => $category, 'type' => 'all']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ ($currentType ?? 'all') === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Semua ({{ $countAll ?? $products->count() }})
            </a>
            <a href="{{ route('produk.category', ['category' => $category, 'type' => 'buy']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ ($currentType ?? 'all') === 'buy' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Beli ({{ $countBuy ?? 0 }})
            </a>
            <a href="{{ route('produk.category', ['category' => $category, 'type' => 'rent']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ ($currentType ?? 'all') === 'rent' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Sewa ({{ $countRent ?? 0 }})
            </a>
        </div>
    </div>

    <!-- CONTENT / PRODUCTS GRID -->
    @if($products->isEmpty())
        <div class="py-20 text-center bg-white rounded-3xl border border-slate-200 shadow-sm p-8">
            <div class="mx-auto h-20 w-20 bg-emerald-50 rounded-full flex items-center justify-center text-4xl mb-4">
                🏕️
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Produk</h3>
            <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">
                Tidak ada produk
                @if(($currentType ?? 'all') === 'buy') untuk dibeli
                @elseif(($currentType ?? 'all') === 'rent') untuk disewa
                @endif
                pada kategori "{{ $category }}".
            </p>
            @if(($currentType ?? 'all') !== 'all')
                <a href="{{ route('produk.category', ['category' => $category, 'type' => 'all']) }}" 
                   class="inline-flex items-center justify-center px-6 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors">
                    Lihat Semua Tipe Produk
                </a>
            @else
                <a href="{{ route('produk.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-100">
                    Lihat Produk Lainnya
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
                @include('components.product-card', [
                    'product' => $product,
                    'isWishlisted' => in_array($product->id, $wishlistProductIds ?? [])
                ])
            @endforeach
        </div>
    @endif

</div>
@endsection
