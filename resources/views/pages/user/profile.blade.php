@extends('layouts.app')

@section('title', 'Profile | Ikat Ethnic')

@section('content')
  <!-- Page Header -->
  <header class="pt-32 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full text-center">
    <h1 class="font-body text-3xl md:text-4xl font-medium text-white">Profil Saya</h1>
    <p class="text-muted text-sm mt-2 font-light">Kelola informasi diri Anda</p>
  </header>

  <!-- Session Alerts -->
  @if (session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 w-full">
      <div
        class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-sm text-sm flex items-center gap-2">
        <i data-feather="check-circle" class="w-4 h-4 shrink-0"></i>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  <!-- Profile Content -->
  <main class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Left Sidebar - Profile Summary -->
      <div class="flex flex-col gap-6 border-b border-surface2 pb-8 lg:border-0 lg:pb-0">
        <!-- Profile Card -->
        <div class="bg-surface border border-surface2 p-8 flex flex-col items-center gap-6">
          <!-- Avatar -->
          <div class="w-20 h-20 rounded-full bg-linear-to-br from-gold to-gold-lt flex items-center justify-center">
            <span class="text-xl font-medium text-bg uppercase">
              {{ substr($user->name, 0, 1) }}
            </span>
          </div>

          <!-- User Info -->
          <div class="text-center w-full">
            <h2 class="text-xl font-medium text-white mb-1">{{ $user->name }}</h2>
            <p class="text-muted text-sm break-all">{{ $user->email }}</p>
          </div>

          <!-- Quick Stats -->
          <div class="w-full grid grid-cols-2 gap-4 pt-6 border-t border-surface2">
            <div class="text-center">
              <div class="text-gold text-xl font-medium">
                @auth{{ Auth::user()->orders->count() }}@else{{ 0 }}@endauth
              </div>
              <div class="text-muted text-xs tracking-widest uppercase mt-1">Orders</div>
            </div>
            <div class="text-center">
              <div class="text-gold text-xl font-medium">Member</div>
              <div class="text-muted text-xs tracking-widest uppercase mt-1">Sejak
                {{ $user->created_at->isoFormat('MMMM Y') }}
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-3 lg:flex lg:flex-col gap-2 lg:gap-3">
          <a href="{{ route('profile') }}"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-3 px-2 lg:px-4 py-3 {{ request()->routeIs('profile') ? 'border-gold text-gold bg-gold/10' : 'bg-surface border border-surface2 hover:border-gold text-white hover:text-gold' }} transition-colors rounded-sm text-center lg:text-left">
            <i data-feather="user" class="w-4 h-4 shrink-0"></i>
            <span class="text-[11px] lg:text-sm font-medium">Profile</span>
          </a>
          <a href="{{ route('orders') }}"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-3 px-2 lg:px-4 py-3 {{ request()->routeIs('orders') ? 'border-gold text-gold bg-gold/10' : 'bg-surface border border-surface2 hover:border-gold text-white hover:text-gold' }} transition-colors rounded-sm text-center lg:text-left">
            <i data-feather="shopping-bag" class="w-4 h-4 shrink-0"></i>
            <span class="text-[11px] lg:text-sm font-medium">Orders</span>
          </a>
          <form action="{{ route('logout') }}" method="POST" class="contents">
            @csrf
            <button type="submit"
              class="w-full flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-3 px-2 lg:px-4 py-3 bg-surface border border-surface2 hover:border-gold text-white hover:text-gold transition-colors rounded-sm text-center lg:text-left cursor-pointer">
              <i data-feather="log-out" class="w-4 h-4 shrink-0"></i>
              <span class="text-[11px] lg:text-sm font-medium">Logout</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Main Content - Profile Details -->
      <div class="lg:col-span-2 flex flex-col gap-6">

        <!-- Personal Information -->
        <div class="bg-surface border border-surface2 p-8">
          <div class="flex items-center gap-3 mb-8 pb-6 border-b border-surface2">
            <i data-feather="user" class="w-5 h-5 text-gold"></i>
            <h3 class="text-lg font-medium text-white">Informasi Pribadi</h3>
          </div>

          <div class="space-y-6">
            <!-- Name -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs text-muted tracking-widest uppercase mb-2">Nama Lengkap</label>
                <div class="px-4 py-3 bg-bg border border-surface2 text-ink rounded-sm">
                  {{ $user->name }}
                </div>
              </div>
              <div>
                <label class="block text-xs text-muted tracking-widest uppercase mb-2">Email</label>
                <div class="px-4 py-3 bg-bg border border-surface2 text-ink rounded-sm">
                  {{ $user->email }}
                </div>
              </div>
            </div>

            <!-- Phone & Address -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs text-muted tracking-widest uppercase mb-2">Nomor Telepon</label>
                <div class="px-4 py-3 bg-bg border border-surface2 text-ink rounded-sm">
                  {{ $user->no_hp ?? '-' }}
                </div>
              </div>
              <div>
                <label class="block text-xs text-muted tracking-widest uppercase mb-2">Tanggal Bergabung</label>
                <div class="px-4 py-3 bg-bg border border-surface2 text-ink rounded-sm">
                  {{ $user->created_at->isoFormat('D MMMM Y') }}
                </div>
              </div>
            </div>

            <!-- Full Address -->
            <div>
              <label class="block text-xs text-muted tracking-widest uppercase mb-2">Alamat</label>
              <div class="px-4 py-3 bg-bg border border-surface2 text-ink rounded-sm min-h-24 wrap-break-word">
                {{ $user->alamat ?? 'Not provided' }}
              </div>
            </div>
          </div>

          <!-- Edit Button -->
          <div class="mt-8 pt-6 border-t border-surface2">
            <a href="{{ route('profile.edit') }}"
              class="inline-flex items-center gap-2 px-6 py-3 bg-gold hover:bg-gold-lt text-bg text-sm font-medium tracking-wider uppercase transition-all duration-300 rounded-sm">
              <i data-feather="edit-2" class="w-4 h-4"></i>
              Edit Profil
            </a>
          </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-surface border border-surface2 p-8">
          <div class="flex items-center gap-3 pb-2 border-b border-surface2">
            <i data-feather="package" class="w-5 h-5 text-gold"></i>
            <h3 class="text-lg font-medium text-white">Pemesanan Terakhir</h3>
          </div>

          <div class="flex flex-col items-center py-2">
            @if ($orders->isEmpty())
              <i data-feather="box" class="w-16 h-16 text-muted mb-4"></i>
              <p class="text-muted text-sm">Belum ada pesanan</p>
              <a href="{{ route('products') }}"
                class="inline-block mt-4 px-6 py-2.5 border border-gold text-gold hover:bg-gold hover:text-bg text-xs font-medium tracking-wider uppercase transition-all duration-300 rounded-sm">
                Mulai Belanja
              </a>
            @else
              <div class="space-y-6 w-full">
                @foreach ($orders as $order)
                  <a href="{{ route('orders.show', $order->id) }}"
                    class="block bg-bg border border-surface2 p-4 hover:border-gold transition-colors">
                    <div class="flex justify-between items-start mb-4 pb-2 border-b border-surface2">
                      <div>
                        <p class="text-white text-sm font-medium">Order #{{ $order->order_number }}</p>
                        <p class="text-muted text-xs mt-1">{{ $order->created_at->isoFormat('D MMMM Y, HH:mm') }}</p>
                      </div>
                      <span
                        class="px-3 py-1 bg-primary/20 text-primary border border-primary/30 text-xs font-medium rounded-sm">
                        {{ $order->status }}
                      </span>
                    </div>

                    <div class="flex items-center gap-4">
                      @foreach ($order->orderItems as $item)
                        <div class="w-12 h-14 shrink-0 overflow-hidden bg-surface border border-surface2 flex items-center justify-center">
                          @if ($item->product && $item->product->images->first())
                            <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                              alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                          @else
                            <i data-feather="image" class="w-4 h-4 text-muted"></i>
                          @endif
                        </div>
                      @endforeach
                      <div class="flex-1">
                        <p class="text-white text-sm font-medium">{{ ucwords($order->orderItems?->first()?->product_name ?? 'Product') }}</p>
                        <p class="text-muted text-xs mt-1">{{ $order->orderItems->count() }} item(s) •
                          Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                      </div>
                    </div>
                  </a>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
