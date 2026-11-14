@php 
    $routeName = request()->route()?->getName(); 
    $hasHeroPhoto = in_array($routeName, ['landing', 'home']);
    $expiredRentals = collect();
    if(auth()->check() && auth()->user()->role === 'buyer') {
        $expiredRentals = \App\Models\Pembeli\OrderDetail_pembeli::whereHas('order', function($q) {
            $q->where('user_id', auth()->id())
              ->whereNotIn('status', ['selesai', 'dibatalkan', 'menunggu'])
              ->whereDoesntHave('returns', function($rq) {
                  $rq->whereIn('status', ['pending', 'checking', 'waiting_refund', 'completed']);
              });
        })
        ->where('type', 'rent')
        ->get()
        ->filter(function($detail) {
            if($detail->start_date && $detail->duration) {
                // Tambah durasi ke start date, set end of day, lalu cek apakah sekarang > end date
                $endDate = \Carbon\Carbon::parse($detail->start_date)->addDays($detail->duration)->endOfDay();
                return now()->greaterThan($endDate);
            }
            return false;
        });
    }
@endphp

@if($expiredRentals->count() > 0)
<div id="rentalAlertBanner" class="fixed top-0 left-0 right-0 bg-red-600 text-white text-center text-xs md:text-sm py-2.5 px-4 shadow-md z-[60] flex flex-col md:flex-row justify-center items-center gap-2">
    <span>⚠️ <strong>Peringatan:</strong> Ada {{ $expiredRentals->count() }} barang sewaan yang durasinya telah habis.</span>
    @if($expiredRentals->count() === 1)
        <a href="{{ route('orders.detail', ['id' => $expiredRentals->first()->order_id, 'detail_id' => $expiredRentals->first()->id]) }}" class="underline font-bold hover:text-red-200 bg-white/20 px-3 py-1 rounded-full text-xs">Kembalikan Sekarang</a>
    @else
        <a href="{{ route('profile', ['tab' => 'rentals']) }}" class="underline font-bold hover:text-red-200 bg-white/20 px-3 py-1 rounded-full text-xs">Kembalikan Sekarang</a>
    @endif
</div>
@endif

<nav id="mainNavbar" 
     data-has-hero="{{ $hasHeroPhoto ? 'true' : 'false' }}"
     class="fixed {{ $expiredRentals->count() > 0 ? 'top-[68px] md:top-[40px]' : 'top-0' }} left-0 right-0 z-50 transition-all duration-500 text-white" 
     style="{{ $hasHeroPhoto 
        ? 'background: rgba(14, 46, 36, 0.45); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border-bottom: 1px solid rgba(255, 255, 255, 0.12);' 
        : 'background: rgba(10, 32, 25, 0.96); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 1px solid rgba(16, 185, 129, 0.2); box-shadow: 0 4px 20px rgba(0,0,0,0.2);' }}">
  <div class="max-w-7xl mx-auto px-4 py-3 flex flex-col gap-2.5">

    <!-- TOP BAR -->
    <div class="flex items-center justify-between">

      <!-- Logo -->
      <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-emerald-500/25 transition-all duration-300 group-hover:scale-105 group-hover:shadow-emerald-500/40">
          C
        </div>
        <div class="flex items-center gap-1.5">
          <span class="font-extrabold text-xl tracking-tight text-white drop-shadow-sm">Campify</span>
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-sm shadow-emerald-400/50"></span>
        </div>
      </a>

      <!-- Search -->
      <div class="hidden md:flex flex-1 mx-6 max-w-xl">
        <form action="{{ route('produk.search') }}" method="GET" class="w-full">
          <div class="w-full bg-white/10 hover:bg-white/15 focus-within:bg-white/20 rounded-full px-4 py-2 flex items-center border border-white/20 focus-within:border-emerald-400 focus-within:ring-2 focus-within:ring-emerald-400/25 transition-all shadow-inner backdrop-blur-md">
            <span class="mr-2.5 text-white/80">🔍</span>
            <input type="text"
              name="q"
              placeholder="Cari tenda dome, carrier, kompor ultralight..."
              class="bg-transparent w-full focus:outline-none text-sm text-white placeholder-white/70">
          </div>
        </form>
      </div>

      <!-- Icons & Actions -->
      <div class="flex items-center gap-3 text-white">
        <a href="{{ route('chat.index') }}" title="Chat" class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-white/15 transition-all relative text-lg hover:text-emerald-300">
          💬
          @if(auth()->check() && ($chatUnreadCount ?? 0) > 0)
            <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold shadow-sm">
              {{ $chatUnreadCount > 9 ? '9+' : $chatUnreadCount }}
            </span>
          @endif
        </a>
        <a href="{{ route('wishlist.index') }}" title="Wishlist" class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-white/15 transition-all relative text-lg hover:text-emerald-300">
          🤍
          @if(auth()->check() && ($wishlistCount ?? 0) > 0)
            <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold shadow-sm">
              {{ $wishlistCount ?? 0 }}
            </span>
          @endif
        </a>
        <a href="{{ route('cart.index') }}" title="Keranjang" class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-white/15 transition-all relative text-lg hover:text-emerald-300">
          🛒
          @if(auth()->check() && ($cartCount ?? 0) > 0)
            <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold shadow-sm">
              {{ $cartCount ?? 0 }}
            </span>
          @endif
        </a>

        <div class="flex items-center gap-2 border-l border-white/20 pl-3 ml-1">
          @auth
            <div class="relative">
              <button onclick="toggleDropdown()" class="flex items-center gap-2 hover:text-emerald-300 transition text-white px-2 py-1 rounded-full hover:bg-white/10">
                <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">
                  {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <span class="text-sm font-semibold max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                <span class="text-xs opacity-75">▼</span>
              </button>
              
              <!-- Dropdown Menu -->
              <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-100 hidden text-slate-800 z-50 py-1.5 overflow-hidden">
                <a href="{{ route('profile') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Profile</a>
                
                <hr class="my-1 border-slate-100">
                <form action="{{ route('logout') }}" method="POST" class="block">
                  @csrf
                  <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium transition-colors">Logout</button>
                </form>
              </div>
            </div>
          @else
            <div class="flex items-center gap-2.5">
              <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-500/25 transition-all duration-200 hover:scale-105 active:scale-95">
                Login
              </a>
              <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/25 hover:border-emerald-300 text-white hover:text-emerald-300 font-semibold text-xs backdrop-blur-sm transition-all duration-200 hidden sm:inline-flex">
                Daftar
              </a>
            </div>
          @endauth
        </div>
      </div>
    </div>

    <!-- MENU -->
    <div class="flex items-center gap-2 sm:gap-4 text-xs sm:text-sm border-t border-white/10 pt-2 font-medium">
      <a href="{{ route('landing') }}" 
         class="px-3 py-1 rounded-full transition-all duration-200 {{ request()->routeIs('landing') ? 'bg-emerald-500/25 text-emerald-300 font-bold border border-emerald-400/40 shadow-sm backdrop-blur-sm' : 'text-slate-200 hover:text-emerald-300 hover:bg-white/10' }}">
        Home
      </a>
      <a href="{{ route('produk.rental') }}" 
         class="px-3 py-1 rounded-full transition-all duration-200 {{ request()->routeIs('produk.rental*') ? 'bg-emerald-500/25 text-emerald-300 font-bold border border-emerald-400/40 shadow-sm backdrop-blur-sm' : 'text-slate-200 hover:text-emerald-300 hover:bg-white/10' }}">
        Sewa Alat
      </a>
      <a href="{{ route('produk.index') }}" 
         class="px-3 py-1 rounded-full transition-all duration-200 {{ request()->routeIs('produk.index') ? 'bg-emerald-500/25 text-emerald-300 font-bold border border-emerald-400/40 shadow-sm backdrop-blur-sm' : 'text-slate-200 hover:text-emerald-300 hover:bg-white/10' }}">
        Beli Produk
      </a>
      <a href="{{ route('articles.index') }}" 
         class="px-3 py-1 rounded-full transition-all duration-200 {{ request()->routeIs('articles*') ? 'bg-emerald-500/25 text-emerald-300 font-bold border border-emerald-400/40 shadow-sm backdrop-blur-sm' : 'text-slate-200 hover:text-emerald-300 hover:bg-white/10' }}">
        Artikel
      </a>
    </div>

  </div>
</nav>

<script>
function toggleDropdown() {
  const dropdown = document.getElementById('userDropdown');
  dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
  const dropdown = document.getElementById('userDropdown');
  const button = event.target.closest('button');
  
  if (!button || !button.onclick || typeof button.onclick !== 'function' || button.onclick.toString().indexOf('toggleDropdown') === -1) {
    if (dropdown && !dropdown.classList.contains('hidden')) {
      dropdown.classList.add('hidden');
    }
  }
});

window.addEventListener('scroll', function () {
  const navbar = document.getElementById('mainNavbar');
  if (!navbar) return;
  const hasHero = navbar.dataset.hasHero === 'true';

  if (window.scrollY > 20) {
    navbar.style.background = 'rgba(10, 32, 25, 0.95)';
    navbar.style.backdropFilter = 'blur(16px)';
    navbar.style.webkitBackdropFilter = 'blur(16px)';
    navbar.style.borderBottom = '1px solid rgba(16, 185, 129, 0.25)';
    navbar.style.boxShadow = '0 10px 30px -5px rgba(0, 0, 0, 0.35)';
  } else {
    if (hasHero) {
      navbar.style.background = 'rgba(14, 46, 36, 0.45)';
      navbar.style.backdropFilter = 'blur(14px)';
      navbar.style.webkitBackdropFilter = 'blur(14px)';
      navbar.style.borderBottom = '1px solid rgba(255, 255, 255, 0.12)';
      navbar.style.boxShadow = 'none';
    } else {
      navbar.style.background = 'rgba(10, 32, 25, 0.96)';
      navbar.style.backdropFilter = 'blur(16px)';
      navbar.style.webkitBackdropFilter = 'blur(16px)';
      navbar.style.borderBottom = '1px solid rgba(16, 185, 129, 0.2)';
      navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.2)';
    }
  }
});
</script>
