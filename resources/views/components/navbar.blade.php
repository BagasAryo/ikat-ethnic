<nav class="fixed w-full z-50 glass-nav transition-all duration-300">
  <div class="flex justify-between items-center h-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="md:hidden text-ink hover:text-gold transition-colors cursor-pointer p-1"
      aria-label="Toggle menu">
      <i data-feather="menu" id="menu-icon-open" class="w-6 h-6"></i>
      <i data-feather="x" id="menu-icon-close" class="w-6 h-6 hidden"></i>
    </button>

    <!-- Logo -->
    <div class="shrink-0 flex items-center cursor-pointer">
      <a href="{{ url('/') }}" class="font-body font-bold text-2xl tracking-wider text-gold uppercase">Ikat
        Ethnic</a>
    </div>

    <!-- Desktop Menu -->
    <div class="hidden md:flex items-center space-x-10">
      <a href="{{ url('/') }}"
        class="text-xs tracking-[0.15em] font-medium {{ request()->is('/') ? 'text-gold border-b border-gold pb-1' : 'text-muted hover:text-gold-lt transition-colors' }} uppercase">Home</a>
      <a href="{{ url('/products') }}"
        class="text-xs tracking-[0.15em] font-medium {{ request()->is('products') ? 'text-gold border-b border-gold pb-1' : 'text-muted hover:text-gold-lt transition-colors' }} uppercase">Products</a>
      <a href="{{ url('/about') }}"
        class="text-xs tracking-[0.15em] font-medium {{ request()->is('about') ? 'text-gold border-b border-gold pb-1' : 'text-muted hover:text-gold-lt transition-colors' }} uppercase">About</a>
    </div>

    <!-- Icons & Auth -->
    <div class="flex items-center gap-3">
      <!-- Cart Icon -->
      <a href="{{ url('/cart') }}" class="text-ink hover:text-muted transition-colors relative">
        <i data-feather="shopping-bag" class="w-5 h-5"></i>
        <span
          class="absolute -top-1.5 -right-1.5 bg-gold text-white text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center">
          @auth{{ Auth::user()->cart?->cartItems?->count() ?? 0 }}@else{{ 0 }}@endauth
        </span>
      </a>

      <!-- User Icon (selalu tampil, route dibedakan) -->
      @auth
        <a href="{{ Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin' ? url('/admin/dashboard') : route('profile') }}"
          class="text-ink hover:text-gold-lt transition-colors">
          <i data-feather="user" class="w-5 h-5"></i>
        </a>
      @else
        <a href="{{ route('login') }}" class="text-ink hover:text-gold-lt transition-colors">
          <i data-feather="user" class="w-5 h-5"></i>
        </a>
      @endauth

    </div>
  </div>

  <!-- Mobile Menu Overlay -->
  <div id="mobile-menu" class="md:hidden hidden">
    <div class="bg-surface border-t border-surface2 px-4 py-6 space-y-1 animate-slide-down shadow-lg">
      <!-- Navigation Links -->
      <a href="{{ url('/') }}"
        class="block px-4 py-3 text-sm font-medium tracking-wide rounded-sm transition-colors {{ request()->is('/') ? 'text-gold bg-gold-dim' : 'text-ink hover:text-gold hover:bg-surface2' }}">
        <i data-feather="home" class="w-4 h-4 inline-block mr-3"></i>Home
      </a>
      <a href="{{ url('/products') }}"
        class="block px-4 py-3 text-sm font-medium tracking-wide rounded-sm transition-colors {{ request()->is('products') ? 'text-gold bg-gold-dim' : 'text-ink hover:text-gold hover:bg-surface2' }}">
        <i data-feather="shopping-bag" class="w-4 h-4 inline-block mr-3"></i>Products
      </a>
      <a href="{{ url('/about') }}"
        class="block px-4 py-3 text-sm font-medium tracking-wide rounded-sm transition-colors {{ request()->is('about') ? 'text-gold bg-gold-dim' : 'text-ink hover:text-gold hover:bg-surface2' }}">
        <i data-feather="info" class="w-4 h-4 inline-block mr-3"></i>About
      </a>

      <!-- Auth Section -->
      <div class="border-t border-surface2 pt-4 mt-4 space-y-2">
        @auth
          @if (Auth::user()->role === 'admin')
            <a href="{{ url('/admin/dashboard') }}"
              class="block px-4 py-3 text-sm font-medium tracking-wide text-gold bg-gold-dim rounded-sm">
              <i data-feather="grid" class="w-4 h-4 inline-block mr-3"></i>Dashboard Admin
            </a>
          @else
            <a href="{{ route('profile') }}"
              class="block px-4 py-3 text-sm font-medium tracking-wide text-ink hover:text-gold hover:bg-surface2 rounded-sm transition-colors">
              <i data-feather="user" class="w-4 h-4 inline-block mr-3"></i>Profil Saya
            </a>
            <a href="{{ route('orders') }}"
              class="block px-4 py-3 text-sm font-medium tracking-wide text-ink hover:text-gold hover:bg-surface2 rounded-sm transition-colors">
              <i data-feather="package" class="w-4 h-4 inline-block mr-3"></i>Pesanan Saya
            </a>
          @endif
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
              class="w-full text-left px-4 py-3 text-sm font-medium tracking-wide text-danger hover:bg-surface2 rounded-sm transition-colors cursor-pointer">
              <i data-feather="log-out" class="w-4 h-4 inline-block mr-3"></i>Keluar
            </button>
          </form>
        @else
          <a href="{{ route('login') }}"
            class="block w-full text-center px-4 py-3 border border-gold/40 rounded-sm text-gold text-sm font-medium hover:bg-gold-dim transition-colors">Masuk</a>

        @endauth
      </div>
    </div>
  </div>
</nav>

<!-- Navbar Scripts -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    feather.replace();
    // Navbar scroll effect
    const nav = document.querySelector('nav');
    if (nav) {
      window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
          nav.classList.add('shadow-md', 'bg-white/95');
          nav.classList.remove('bg-white/80');
        } else {
          nav.classList.remove('shadow-md', 'bg-white/95');
          nav.classList.add('bg-white/80');
        }
      });
    }

    // Mobile menu toggle
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('menu-icon-open');
    const iconClose = document.getElementById('menu-icon-close');

    if (menuBtn && mobileMenu) {
      menuBtn.addEventListener('click', () => {
        const isOpen = !mobileMenu.classList.contains('hidden');
        if (isOpen) {
          mobileMenu.classList.add('hidden');
          iconOpen.classList.remove('hidden');
          iconClose.classList.add('hidden');
        } else {
          mobileMenu.classList.remove('hidden');
          iconOpen.classList.add('hidden');
          iconClose.classList.remove('hidden');
        }
      });
    }
  });
</script>
