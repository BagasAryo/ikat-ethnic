<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') — Ikat Ethnic</title>
  <meta name="description" content="@yield('meta-description', 'Admin panel Ikat Ethnic')">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

  <!-- Styles & Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @stack('head')

  <style>
    /* Sidebar transition */
    #admin-sidebar {
      transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1),
        transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #sidebar-label {
      transition: opacity 0.2s ease, width 0.3s ease;
    }

    /* Active nav item glow */
    .nav-active {
      background: linear-gradient(90deg, rgba(212, 175, 55, 0.15) 0%, transparent 100%);
      border-left: 2px solid #d4af37;
    }

    /* Scrollbar slim for sidebar */
    #admin-sidebar::-webkit-scrollbar {
      width: 4px;
    }

    #admin-sidebar::-webkit-scrollbar-thumb {
      background: #2a2a2a;
      border-radius: 2px;
    }
  </style>
</head>

<body class="bg-bg text-ink font-body antialiased h-full overflow-hidden">

  <div class="flex h-screen overflow-hidden">

    {{-- ═══════════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════════ --}}
    <aside id="admin-sidebar"
      class="relative flex flex-col bg-surface border-r border-white/5 w-64 shrink-0 overflow-y-auto overflow-x-hidden z-30">

      {{-- Brand --}}
      <a href="{{ route('/') }}" class="flex items-center gap-2 px-6 py-4 border-b border-white/5 shrink-0 ">
        <img src="{{ asset('images/logo.png') }}" alt="Ikat Ethnic" class="w-10">
        <h1 class="text-gold font-medium tracking-wider">Ikat Ethnic</h1>
      </a>

      {{-- Navigation --}}
      <nav class="flex-1 px-3 py-2 space-y-1">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}" id="nav-dashboard"
          class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.dashboard') ? 'nav-active text-gold' : 'text-muted hover:text-ink hover:bg-surface2' }}">
          <i data-feather="grid" class="w-4 h-4 shrink-0"></i>
          <span class="sidebar-text whitespace-nowrap">Dashboard</span>
        </a>

        {{-- Divider label --}}
        <div class="sidebar-text px-3">
          <span class="text-faint text-[10px] tracking-[0.2em] uppercase font-semibold">Katalog</span>
        </div>

        {{-- Kategori --}}
        <a href="{{ route('admin.categories.index') }}" id="nav-categories"
          class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.categories.*') ? 'nav-active text-gold' : 'text-muted hover:text-ink hover:bg-surface2' }}">
          <i data-feather="tag" class="w-4 h-4 shrink-0"></i>
          <span class="sidebar-text whitespace-nowrap">Kategori</span>
        </a>

        {{-- Produk --}}
        <a href="{{ route('admin.products.index') }}" id="nav-products"
          class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.products.*') ? 'nav-active text-gold' : 'text-muted hover:text-ink hover:bg-surface2' }}">
          <i data-feather="package" class="w-4 h-4 shrink-0"></i>
          <span class="sidebar-text whitespace-nowrap">Produk</span>
        </a>

        {{-- Divider label --}}
        <div class="sidebar-text px-3">
          <span class="text-faint text-[10px] tracking-[0.2em] uppercase font-semibold">Transaksi</span>
        </div>

        {{-- Order --}}
        <a href="{{ route('admin.orders.index') }}" id="nav-orders"
          class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.orders.*') ? 'nav-active text-gold' : 'text-muted hover:text-ink hover:bg-surface2' }}">
          <i data-feather="shopping-bag" class="w-4 h-4 shrink-0"></i>
          <span class="sidebar-text whitespace-nowrap">Order</span>
          {{-- Badge count --}}
          @if (isset($pendingOrdersCount) && $pendingOrdersCount > 0)
            <span
              class="sidebar-text ml-auto bg-gold text-bg text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">
              {{ $pendingOrdersCount }}
            </span>
          @endif
        </a>

      </nav>

      {{-- Bottom: User Profile --}}
      <div class="shrink-0 border-t border-white/5 px-4 py-4">
        <div class="flex items-center gap-3">
          <div
            class="w-8 h-8 rounded-full bg-surface2 border border-white/10 flex items-center justify-center shrink-0">
            <i data-feather="user" class="w-4 h-4 text-muted"></i>
          </div>
          <div id="sidebar-label" class="flex-1 min-w-0">
            <p class="text-ink text-xs font-medium truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
            <p class="text-faint text-[10px] truncate">{{ auth()->user()->email ?? 'admin@ikatethnic.id' }}</p>
          </div>
          <form method="POST" action="{{ route('logout') }}" class="sidebar-text shrink-0">
            @csrf
            <button type="submit" title="Logout" class="text-faint hover:text-danger transition-colors">
              <i data-feather="log-out" class="w-4 h-4"></i>
            </button>
          </form>
        </div>
      </div>

    </aside>

    {{-- ═══════════════════════════════════════════
         MAIN CONTENT AREA
    ═══════════════════════════════════════════ --}}
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

      {{-- Top Navbar --}}
      <header
        class="shrink-0 flex items-center justify-between h-14 px-6 border-b border-white/5 bg-surface/50 backdrop-blur-sm z-20">

        {{-- Left: Toggle + Breadcrumb --}}
        <div class="flex items-center gap-4">
          <button id="sidebar-toggle" class="text-muted hover:text-ink transition-colors -ml-1"
            aria-label="Toggle Sidebar">
            <i data-feather="menu" class="w-5 h-5"></i>
          </button>
          <div class="hidden sm:flex items-center gap-2 text-xs text-faint">
            <span>Admin</span>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span class="text-muted">@yield('breadcrumb', 'Dashboard')</span>
          </div>
        </div>

        {{-- Right: Actions --}}
        <div class="flex items-center gap-4">
          {{-- Search --}}
          <div
            class="hidden md:flex items-center gap-2 bg-surface2 border border-white/5 rounded-sm px-3 py-1.5 text-sm">
            <i data-feather="search" class="w-3.5 h-3.5 text-faint"></i>
            <input type="text" placeholder="Cari..."
              class="bg-transparent text-ink text-xs outline-none placeholder:text-faint w-40">
          </div>

          {{-- Notification --}}
          <button class="relative text-muted hover:text-ink transition-colors">
            <i data-feather="bell" class="w-5 h-5"></i>
            <span class="absolute -top-1 -right-1 w-2 h-2 bg-gold rounded-full"></span>
          </button>

          {{-- Avatar --}}
          <div class="w-7 h-7 rounded-full bg-gold/20 border border-gold/30 flex items-center justify-center">
            <span class="text-gold text-xs font-bold">A</span>
          </div>
        </div>
      </header>

      {{-- Page Content --}}
      <main class="flex-1 overflow-y-auto bg-bg">
        <div class="p-6 lg:p-8">
          @yield('content')
        </div>
      </main>

    </div>
  </div>

  {{-- Sidebar Toggle Script --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      feather.replace();

      const sidebar = document.getElementById('admin-sidebar');
      const toggle = document.getElementById('sidebar-toggle');
      const labels = document.querySelectorAll('.sidebar-text');
      let collapsed = false;

      toggle.addEventListener('click', () => {
        collapsed = !collapsed;
        if (collapsed) {
          sidebar.style.width = '68px';
          labels.forEach(el => {
            el.style.opacity = '0';
            el.style.width = '0';
            el.style.overflow = 'hidden';
          });
        } else {
          sidebar.style.width = '256px';
          labels.forEach(el => {
            el.style.opacity = '1';
            el.style.width = '';
            el.style.overflow = '';
          });
        }
      });
    });
  </script>

  @stack('scripts')

  <script>
    // SweetAlert2 Toast configuration
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      background: '#151515',
      color: '#f0ece4',
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    // Session Flash Messages
    @if (session('success'))
      Toast.fire({
        icon: 'success',
        title: "{{ session('success') }}"
      });
    @endif

    @if (session('error'))
      Toast.fire({
        icon: 'error',
        title: "{{ session('error') }}"
      });
    @endif
  </script>
</body>

</html>
