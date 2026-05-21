<nav class="fixed w-full z-50 glass-nav transition-all duration-300">
  <div class="flex justify-between items-center h-16">
    <!-- Logo -->
    <div class="shrink-0 flex items-center cursor-pointer">
      <a href="{{ url('/') }}" class="font-body font-bold text-2xl tracking-wider text-gold uppercase px-4">Ikat Ethnic</a>
    </div>

    <!-- Desktop Menu -->
    <div class="hidden md:flex items-center space-x-10">
      <a href="{{ url('/') }}" class="text-xs tracking-[0.15em] font-medium {{ request()->is('/') ? 'text-gold border-b border-gold pb-1' : 'text-muted hover:text-gold-lt transition-colors' }} uppercase">Home</a>
      <a href="{{ url('/products') }}" class="text-xs tracking-[0.15em] font-medium {{ request()->is('products') ? 'text-gold border-b border-gold pb-1' : 'text-muted hover:text-gold-lt transition-colors' }} uppercase">Products</a>
      <a href="{{ url('/about') }}" class="text-xs tracking-[0.15em] font-medium {{ request()->is('about') ? 'text-gold border-b border-gold pb-1' : 'text-muted hover:text-gold-lt transition-colors' }} uppercase">About</a>
    </div>

    <!-- Icons & Auth -->
    <div class="flex items-center">
      <div class="flex items-center space-x-4">
        <button class="text-ink hover:text-muted transition-colors cursor-pointer">
          <i data-feather="search" class="w-5 h-5"></i>
        </button>
        <a href="{{ url('/cart') }}" class="text-ink hover:text-muted transition-colors relative">
          <i data-feather="shopping-bag" class="w-5 h-5"></i>
          <span class="absolute -top-1.5 -right-1.5 bg-gold text-ink text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center">@auth{{ Auth::user()->cart->cartItems->count() }}@else{{ 0 }}@endauth</span>
        </a>
      </div>

      @if (Route::has('login'))
        <div class="hidden sm:flex items-center space-x-3 mx-3 border-l border-surface2">
          @auth
          @if (Auth::user()->role === 'admin')
          <a href="{{ url('/admin/dashboard') }}" class="text-sm tracking-widest text-ink hover:text-gold-lt px-3 py-2 font-medium border rounded-sm transition-colors">Dashboard</a>
              @elseif (Auth::user()->role === 'user')
              <i data-feather="user" class="w-5 h-5"></i>
          @endif
          @else
            <!-- <div class="flex items-center space-x-2"> -->
            <a href="{{ route('login') }}" class="px-4 py-2 border border-gold/40 rounded-sm text-gold bg-transparent hover:bg-gold-dim text-sm duration-200 hover:text-gold-lt">Log in</a>
            <a href="{{ route('register') }}" class="px-4 py-2 bg-gold rounded-sm hover:bg-gold-lt hover:-translate-y-1px duration-200 text-sm text-black">Register</a>
            <!-- </div> -->
          @endauth
        </div>
      @endif

      <!-- Mobile Menu Button -->
      <button class="md:hidden text-ink hover:text-gold-lt transition-colors">
        <i data-feather="menu" class="w-6 h-6"></i>
      </button>
    </div>
  </div>
</nav>

<!-- Navbar Scroll Script -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Navbar scroll effect
    const nav = document.querySelector('nav');
    if (nav) {
      window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
          nav.classList.add('shadow-md', 'bg-bg/95');
          nav.classList.remove('bg-bg/80');
        } else {
          nav.classList.remove('shadow-md', 'bg-bg/95');
          nav.classList.add('bg-bg/80');
        }
      });
    }
  });
</script>
