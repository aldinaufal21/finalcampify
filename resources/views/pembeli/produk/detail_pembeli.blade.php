@extends('layouts.app_pembeli')

@section('content')
<div class="pt-8 pb-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4">

        <div class="mb-6 flex items-center justify-between gap-4">
            <a href="{{ route('produk.index') }}" class="text-sm text-green-600 hover:text-green-800 font-semibold">← Kembali ke Produk</a>
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <span>{{ $produk->category }}</span>
                <span>•</span>
                <span class="{{ ($produk->stock ?? 0) > 0 ? 'text-emerald-600 font-semibold' : 'text-red-500 font-semibold' }}">
                    {{ ($produk->stock ?? 0) > 0 ? 'Stok: ' . $produk->stock . ' unit' : 'Stok Habis' }}
                </span>
            </div>
        </div>

        <div class="grid gap-10 xl:grid-cols-[1.5fr_1fr]">

            <div class="space-y-6">
                <div class="rounded-[32px] overflow-hidden shadow-lg bg-white">
                    <img src="{{ $produk->image_url }}"
                         alt="{{ $produk->name }}"
                         class="w-full h-[520px] object-cover object-center">
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="bg-white rounded-3xl p-6 shadow-sm">
                        <h2 class="text-xl font-bold mb-4">Detail Produk</h2>
                        <p class="text-sm leading-7 text-slate-600">{{ $produk->description }}</p>
                    </div>
                    <div class="bg-white rounded-3xl p-6 shadow-sm">
                        <h2 class="text-xl font-bold mb-4">Rating & Review</h2>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex text-yellow-400 text-xl">
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= $produk->rating)
                                        <span>★</span>
                                    @else
                                        <span class="text-slate-300">★</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm text-slate-500">({{ $produk->reviews_count }} ulasan)</span>
                        </div>
                        <p class="text-6xl font-bold text-slate-900">{{ number_format($produk->rating, 1) }}</p>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-start justify-between gap-4 mb-6">
                        <div class="flex-1 min-w-0 pr-2">
                            <span class="inline-block text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md mb-2">
                                {{ ($mode ?? 'buy') === 'rent' ? 'Detail Penyewaan' : 'Detail Pembelian' }}
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug break-words">
                                {{ $produk->name }}
                            </h1>
                            <div class="flex items-center gap-1.5 text-sm text-slate-500 mt-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span>Penjual:</span>
                                <span class="font-semibold text-slate-700 truncate">{{ $produk->store?->nama_toko ?? $produk->seller?->name ?? $produk->owner?->name ?? 'Toko Resmi' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons: Favorit & Chat -->
                        <div class="flex items-center gap-2 flex-shrink-0 pt-0.5">
                            @auth
                                <form action="{{ route('wishlist.toggle') }}" method="POST" class="inline-flex m-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $produk->id }}">
                                    <button type="submit" 
                                            class="group inline-flex h-11 w-11 items-center justify-center rounded-2xl border transition-all duration-200
                                            {{ in_array($produk->id, $wishlistProductIds ?? []) 
                                                ? 'bg-rose-50 border-rose-200 text-rose-500 shadow-sm hover:bg-rose-100 hover:border-rose-300' 
                                                : 'bg-white border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50/50 shadow-sm' }}"
                                            title="{{ in_array($produk->id, $wishlistProductIds ?? []) ? 'Hapus dari Favorit' : 'Tambah ke Favorit' }}">
                                        @if(in_array($produk->id, $wishlistProductIds ?? []))
                                            <svg class="w-5 h-5 fill-current text-rose-500 transition-transform duration-200 group-hover:scale-110" viewBox="0 0 24 24">
                                                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="group inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50/50 shadow-sm transition-all duration-200" 
                                   title="Login untuk favorit">
                                    <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </a>
                            @endauth

                            <a href="{{ route('chat.product.start', $produk->id) }}" 
                               class="group inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50/50 shadow-sm transition-all duration-200" 
                               title="Hubungi Penjual">
                                <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </a>
                        </div>
                    </div>



                    <div class="grid gap-4 mb-6">
                        <div class="rounded-3xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Harga Beli</p>
                            <p class="text-2xl font-bold text-emerald-700">Rp {{ number_format($produk->buy_price) }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Harga Sewa</p>
                            @if($produk->rent_price && $produk->rent_price > 0)
                                <p class="text-2xl font-bold text-blue-700">Rp {{ number_format($produk->rent_price) }}/hari</p>
                            @else
                                <p class="text-sm text-slate-400 italic">Tidak tersedia untuk sewa</p>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-6">
                        @php $mode = $mode ?? 'buy'; @endphp

                        @if($mode === 'buy')
                            <div class="bg-slate-50 rounded-3xl p-5 border border-slate-200">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-slate-900">Beli Sekarang</h3>
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ ($produk->stock ?? 0) > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                        Sisa Stok: {{ $produk->stock ?? 0 }} unit
                                    </span>
                                </div>
                                @if(($produk->stock ?? 0) > 0)
                                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $produk->id }}">
                                        <input type="hidden" name="type" value="buy">

                                        <div>
                                            <p class="text-sm text-slate-500 font-medium">Jumlah Pembelian</p>
                                            <input type="number" name="quantity" min="1" max="{{ max(1, $produk->stock) }}" value="1" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3" />
                                        </div>

                                        <div class="grid gap-3 sm:grid-cols-[1fr_auto]">
                                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800 w-full">Tambah Keranjang</button>
                                            <a href="{{ route('checkout.now', $produk->id) }}" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">Beli Sekarang</a>
                                        </div>
                                    </form>
                                @else
                                    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-center">
                                        <p class="text-sm font-bold text-red-600">Stok Barang Habis</p>
                                    </div>
                                @endif
                            </div>

                            @if($produk->rent_price && $produk->rent_price > 0)
                                <div class="bg-blue-50 rounded-3xl p-5 border border-blue-200">
                                    <p class="text-sm text-slate-500 mb-3">Atau pilih penyewaan jika kamu butuh sementara.</p>
                                    <a href="{{ route('produk.detail.rent', $produk->id) }}" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">Lihat Detail Sewa</a>
                                </div>
                            @endif
                        @else
                            <div class="bg-slate-50 rounded-3xl p-5 border border-slate-200">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-slate-900">Sewa Sekarang</h3>
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ ($produk->stock ?? 0) > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                        Sisa Stok: {{ $produk->stock ?? 0 }} unit
                                    </span>
                                </div>
                                @if(($produk->stock ?? 0) > 0)
                                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $produk->id }}">
                                        <input type="hidden" name="type" value="rent">

                                        <div>
                                            <p class="text-sm text-slate-500">Tanggal Peminjaman</p>
                                            <input type="date" name="start_date" min="{{ date('Y-m-d') }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3" />
                                        </div>
                                        <div>
                                            <p class="text-sm text-slate-500">Durasi Sewa (hari)</p>
                                            <input type="number" name="duration" min="1" value="1" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3" />
                                        </div>

                                        <div class="grid gap-3 sm:grid-cols-[1fr_auto]">
                                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800 w-full">Tambah Keranjang</button>
                                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">Sewa Sekarang</button>
                                        </div>
                                    </form>
                                @else
                                    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-center">
                                        <p class="text-sm font-bold text-red-600">Stok Sewa Habis</p>
                                    </div>
                                @endif
                            </div>

                            @if($produk->buy_price && $produk->buy_price > 0)
                                <div class="bg-emerald-50 rounded-3xl p-5 border border-emerald-200">
                                    <p class="text-sm text-slate-500 mb-3">Atau pilih pembelian jika kamu ingin memiliki barang ini.</p>
                                    <a href="{{ route('produk.detail.buy', $produk->id) }}" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">Lihat Detail Beli</a>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="mt-8 pt-8 border-t border-slate-100">
                        @auth
                            @if(auth()->user()->role === 'buyer')
                                <form method="POST" action="{{ route('products.report', $produk->id) }}" class="mb-4 grid gap-2 sm:grid-cols-[160px_1fr_auto]">
                                    @csrf
                                    <select name="reason" class="rounded-2xl border border-slate-200 px-3 py-2 text-sm">
                                        <option value="Produk mencurigakan">Produk mencurigakan</option>
                                        <option value="Harga tidak wajar">Harga tidak wajar</option>
                                        <option value="Deskripsi menyesatkan">Deskripsi menyesatkan</option>
                                    </select>
                                    <input name="description" class="rounded-2xl border border-slate-200 px-3 py-2 text-sm" placeholder="Detail laporan (opsional)">
                                    <button class="rounded-2xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">Laporkan</button>
                                </form>
                            @endif
                        @endauth

                        @if($produk->store_id)
                            @auth
                                @if(auth()->user()->role === 'buyer')
                                    <form method="POST" action="{{ route('stores.report', $produk->store_id) }}" class="grid gap-2 sm:grid-cols-[160px_1fr_auto]">
                                        @csrf
                                        <select name="reason" class="rounded-2xl border border-slate-200 px-3 py-2 text-sm">
                                            <option value="Toko mencurigakan">Toko mencurigakan</option>
                                            <option value="Pelayanan buruk">Pelayanan buruk</option>
                                            <option value="Informasi toko tidak valid">Informasi toko tidak valid</option>
                                        </select>
                                        <input name="description" class="rounded-2xl border border-slate-200 px-3 py-2 text-sm" placeholder="Detail laporan toko (opsional)">
                                        <button class="rounded-2xl border border-orange-200 px-4 py-2 text-sm font-semibold text-orange-600 hover:bg-orange-50">Laporkan Toko</button>
                                    </form>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
