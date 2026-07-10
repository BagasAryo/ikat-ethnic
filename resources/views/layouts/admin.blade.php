<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') - Ikat Ethnic</title>
  <meta name="description" content="@yield('meta-description', 'Admin panel Ikat Ethnic')">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
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
      background: linear-gradient(90deg, rgba(160, 125, 10, 0.10) 0%, transparent 100%);
      border-left: 2px solid #8a6a08;
    }

    /* Scrollbar slim for sidebar */
    #admin-sidebar::-webkit-scrollbar {
      width: 4px;
    }

    #admin-sidebar::-webkit-scrollbar-thumb {
      background: #d5d0c8;
      border-radius: 2px;
    }
  </style>
</head>

<body class="bg-bg text-ink font-body antialiased h-full overflow-hidden selection:bg-gold selection:text-white">

  <div class="flex h-screen overflow-hidden">

    {{-- Mobile sidebar backdrop --}}
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/30 z-20 hidden lg:hidden" aria-hidden="true"></div>

    {{-- ═══════════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════════ --}}
    <aside id="admin-sidebar"
      class="fixed lg:relative flex flex-col bg-surface border-r border-black/10 w-64 shrink-0 overflow-y-auto overflow-x-hidden z-30 h-full -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-sm">

      {{-- Brand --}}
      <a href="{{ route('/') }}" id="brand-wrapper" class="flex items-center gap-2 px-6 py-4 border-b border-black/10 shrink-0 transition-all duration-200">
        <h1 id="brand-text" class="text-gold text-2xl font-bold tracking-wider uppercase transition-all duration-200">Ikat Ethnic</h1>
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

        {{-- Kategori --}}
        <a href="{{ route('admin.categories.index') }}" id="nav-categories"
          class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.categories.*') ? 'nav-active text-gold' : 'text-muted hover:text-ink hover:bg-surface2' }}">
          <i data-feather="tag" class="w-4 h-4 shrink-0"></i>
          <span class="sidebar-text whitespace-nowrap">Kategori</span>
        </a>

        {{-- Product --}}
        <a href="{{ route('admin.products.index') }}" id="nav-products"
          class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.products.*') ? 'nav-active text-gold' : 'text-muted hover:text-ink hover:bg-surface2' }}">
          <i data-feather="package" class="w-4 h-4 shrink-0"></i>
          <span class="sidebar-text whitespace-nowrap">Product</span>
        </a>

        {{-- Order --}}
        <a href="{{ route('admin.orders.index') }}" id="nav-orders"
          class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.orders.*') ? 'nav-active text-gold' : 'text-muted hover:text-ink hover:bg-surface2' }}">
          <i data-feather="shopping-bag" class="w-4 h-4 shrink-0"></i>
          <span class="sidebar-text whitespace-nowrap">Order</span>
          {{-- Badge count --}}
          @if (isset($pendingOrdersCount) && $pendingOrdersCount > 0)
            <span
              class="sidebar-text ml-auto bg-gold text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">
              {{ $pendingOrdersCount }}
            </span>
          @endif
        </a>

        {{-- User --}}
        <a href="{{ route('admin.users.index') }}" id="nav-users"
          class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.users.*') ? 'nav-active text-gold' : 'text-muted hover:text-ink hover:bg-surface2' }}">
          <i data-feather="users" class="w-4 h-4 shrink-0"></i>
          <span class="sidebar-text whitespace-nowrap">User</span>
        </a>

        {{-- Laporan --}}
        <a href="{{ route('admin.reports.index') }}" id="nav-reports"
          class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.reports.*') ? 'nav-active text-gold' : 'text-muted hover:text-ink hover:bg-surface2' }}">
          <i data-feather="bar-chart-2" class="w-4 h-4 shrink-0"></i>
          <span class="sidebar-text whitespace-nowrap">Laporan</span>
        </a>

      </nav>

      {{-- Superadmin Menu --}}
      @if (auth()->user()->role === 'superadmin')
      <div class="px-4 py-2 mt-4 border-t border-black/10">
        <h3 class="sidebar-text text-xs font-semibold text-faint uppercase tracking-wider mb-2">Superadmin</h3>
        <nav class="space-y-1">
          <a href="{{ route('admin.admins.index') }}" id="nav-admins"
            class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium transition-all duration-200
                        {{ request()->routeIs('admin.admins.*') ? 'nav-active text-gold' : 'text-muted hover:text-ink hover:bg-surface2' }}">
            <i data-feather="shield" class="w-4 h-4 shrink-0"></i>
            <span class="sidebar-text whitespace-nowrap">Kelola Admin</span>
          </a>
          <a href="{{ route('admin.logs.index') }}" id="nav-logs"
            class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm font-medium transition-all duration-200
                        {{ request()->routeIs('admin.logs.*') ? 'nav-active text-gold' : 'text-muted hover:text-ink hover:bg-surface2' }}">
            <i data-feather="activity" class="w-4 h-4 shrink-0"></i>
            <span class="sidebar-text whitespace-nowrap">Log Aktivitas</span>
          </a>
        </nav>
      </div>
      @endif

      {{-- Bottom: User Profile --}}
      <div class="shrink-0 border-t border-black/10 px-4 py-4">
        <div class="flex items-center gap-3">
          <div
            class="w-8 h-8 rounded-full bg-surface2 border border-black/10 flex items-center justify-center shrink-0">
            <i data-feather="user" class="w-4 h-4 text-muted"></i>
          </div>
          <div id="sidebar-label" class="flex-1 min-w-0">
            <p class="text-ink text-xs font-medium truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
            <p class="text-muted text-[10px] truncate">{{ auth()->user()->email ?? 'admin@ikatethnic.id' }}</p>
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
        class="shrink-0 flex items-center justify-between h-14 px-4 sm:px-6 border-b border-black/5 bg-white/70 backdrop-blur-sm z-20">

        {{-- Left: Toggle + Breadcrumb --}}
        <div class="flex items-center gap-4">
          <button id="sidebar-toggle" class="text-muted hover:text-ink transition-colors -ml-1 cursor-pointer"
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
          <div class="flex items-center gap-2 text-xs text-ink bg-surface border border-black/10 rounded-sm px-3 py-2">
            <i data-feather="calendar" class="w-3.5 h-3.5 text-ink"></i>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
          </div>
        </div>
      </header>

      {{-- Page Content --}}
      <main class="flex-1 overflow-y-auto bg-bg">
        <div
          class="p-4 sm:p-6 lg:p-8 pb-[calc(1rem+env(safe-area-inset-bottom))] sm:pb-[calc(1.5rem+env(safe-area-inset-bottom))] lg:pb-[calc(2rem+env(safe-area-inset-bottom))]">
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
      const backdrop = document.getElementById('sidebar-backdrop');
      const labels = document.querySelectorAll('.sidebar-text');
      const isMobile = () => window.innerWidth < 1024;
      let collapsed = false;

      function closeMobileSidebar() {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      }

      function openMobileSidebar() {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
      }

      toggle.addEventListener('click', () => {
        if (isMobile()) {
          // Mobile: slide drawer in/out
          if (sidebar.classList.contains('-translate-x-full')) {
            openMobileSidebar();
          } else {
            closeMobileSidebar();
          }
        } else {
          // Desktop: collapse/expand
          collapsed = !collapsed;
          const brandText = document.getElementById('brand-text');
          const brandWrapper = document.getElementById('brand-wrapper');

          if (collapsed) {
            sidebar.style.width = '68px';
            
            if (brandWrapper) {
              brandWrapper.classList.remove('px-6');
              brandWrapper.classList.add('justify-center');
            }
            if (brandText) {
              brandText.textContent = 'IE';
              brandText.classList.remove('text-2xl');
              brandText.classList.add('text-xl');
            }

            labels.forEach(el => {
              el.style.opacity = '0';
              el.style.width = '0';
              el.style.overflow = 'hidden';
            });
          } else {
            sidebar.style.width = '256px';

            if (brandWrapper) {
              brandWrapper.classList.add('px-6');
              brandWrapper.classList.remove('justify-center');
            }
            if (brandText) {
              brandText.textContent = 'Ikat Ethnic';
              brandText.classList.add('text-2xl');
              brandText.classList.remove('text-xl');
            }

            labels.forEach(el => {
              el.style.opacity = '1';
              el.style.width = '';
              el.style.overflow = '';
            });
          }
        }
      });

      // Close mobile sidebar on backdrop click
      if (backdrop) {
        backdrop.addEventListener('click', closeMobileSidebar);
      }

      // Close mobile sidebar on link click
      sidebar.querySelectorAll('.nav-item').forEach(link => {
        link.addEventListener('click', () => {
          if (isMobile()) closeMobileSidebar();
        });
      });

      // Handle resize: reset mobile state when going to desktop
      window.addEventListener('resize', () => {
        if (!isMobile()) {
          sidebar.classList.remove('-translate-x-full');
          backdrop.classList.add('hidden');
        } else {
          if (!backdrop.classList.contains('hidden')) return;
          sidebar.classList.add('-translate-x-full');
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
      background: '#FFFFFF',
      color: '#1a1814',
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

    @if ($errors->any())
      Toast.fire({
        icon: 'error',
        title: "{{ $errors->first() }}"
      });
    @endif

    function confirmDelete(event, type, name) {
      event.preventDefault();
      const form = event.target;
      Swal.fire({
        title: `Hapus ${type}?`,
        text: `Apakah Anda yakin ingin menghapus ${type.toLowerCase()} "${name}"? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#78716c',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        background: '#FFFFFF',
        color: '#1a1814',
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    }

    function confirmEdit(event, type) {
      event.preventDefault();
      const form = event.target;
      Swal.fire({
        title: 'Simpan Perubahan?',
        text: `Apakah Anda yakin ingin menyimpan perubahan pada ${type.toLowerCase()} ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#8a6a08',
        cancelButtonColor: '#78716c',
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',
        background: '#FFFFFF',
        color: '#1a1814',
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    }
  </script>
</body>

</html>
