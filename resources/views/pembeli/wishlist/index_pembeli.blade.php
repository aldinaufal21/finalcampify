@extends('layouts.app_pembeli')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-6">Wishlist</h1>

    @if($wishlists->isEmpty())
        <div class="text-center py-20 bg-white rounded-3xl shadow-sm">
            <h2 class="text-xl font-semibold">Wishlist kosong</h2>
            <p class="text-gray-500 mt-3">Tambahkan produk favoritmu untuk simpan nanti.</p>
            <a href="{{ route('produk.index') }}" class="mt-6 inline-block bg-green-600 text-white px-6 py-3 rounded-full hover:bg-green-700 transition">
                Jelajahi Produk
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($wishlists as $wishlist)
                @php
                    $isBuy = $wishlist->product->jenis_produk === 'jual' || ($wishlist->product->buy_price > 0 && $wishlist->product->rent_price <= 0);
                    $detailUrl = $isBuy ? route('produk.detail.buy', $wishlist->product->id) : route('produk.detail.rent', $wishlist->product->id);
                @endphp
                <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-slate-100 flex flex-col justify-between">
                    <div>
                        <a href="{{ $detailUrl }}">
                            <img src="{{ $wishlist->product->image_url ?? asset($wishlist->product->image) }}" class="w-full h-56 object-cover bg-slate-50">
                        </a>
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full font-medium">{{ $wishlist->product->category }}</span>
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $isBuy ? 'bg-slate-100 text-slate-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $isBuy ? 'Beli' : 'Sewa' }}
                                </span>
                            </div>
                            <a href="{{ $detailUrl }}" class="block font-bold text-base text-slate-900 mb-2 hover:text-emerald-600 transition line-clamp-2">{{ $wishlist->product->name }}</a>
                            <div class="mb-4">
                                @if($isBuy)
                                    <span class="text-base font-extrabold text-slate-900">Rp {{ number_format($wishlist->product->buy_price) }}</span>
                                @else
                                    <span class="text-base font-extrabold text-emerald-600">Rp {{ number_format($wishlist->product->rent_price) }}<span class="text-xs font-normal text-slate-400">/hari</span></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="p-5 pt-0">
                        <form action="{{ route('wishlist.toggle') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $wishlist->product->id }}">
                            <button class="w-full text-center bg-rose-50 text-rose-600 border border-rose-200 py-2.5 rounded-2xl hover:bg-rose-100 font-bold text-xs transition">Hapus dari Wishlist</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection