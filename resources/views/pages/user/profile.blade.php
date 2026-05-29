<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Profile | Ikat Ethnic</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
    rel="stylesheet">

  <script src="https://unpkg.com/feather-icons"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
  class="bg-bg text-ink font-body antialiased selection:bg-gold selection:text-obsidian-900 flex flex-col min-h-screen">

  <x-navbar />

  <!-- Page Header -->
  <header class="pt-32 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full text-center">
    <h1 class="font-body text-3xl md:text-4xl font-medium text-white">My Profile</h1>
    <p class="text-muted text-sm mt-2 font-light">Manage your account information</p>
  </header>

  <!-- Profile Content -->
  <main class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Left Sidebar - Profile Summary -->
      <div class="flex flex-col gap-6">
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
            <span
              class="inline-block mt-3 px-3 py-1 text-xs tracking-widest uppercase bg-gold/20 text-gold rounded-full">
              {{ ucfirst($user->role ?? 'customer') }}
            </span>
          </div>

          <!-- Quick Stats -->
          <div class="w-full grid grid-cols-2 gap-4 pt-6 border-t border-surface2">
            <div class="text-center">
              <div class="text-gold text-xl font-medium">@auth{{ Auth::user()->orders->count() }}@else{{ 0 }}@endauth</div>
              <div class="text-muted text-xs tracking-widest uppercase mt-1">Orders</div>
            </div>
            <div class="text-center">
              <div class="text-gold text-xl font-medium">Member</div>
              <div class="text-muted text-xs tracking-widest uppercase mt-1">Since
                {{ $user->created_at->format('M Y') }}
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="flex flex-col gap-3">
          <a href="{{ route('profile') }}"
            class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('profile') ? 'border-gold text-gold bg-gold/10' : 'bg-surface border border-surface2 hover:border-gold text-white hover:text-gold' }} transition-colors rounded-sm">
            <i data-feather="user" class="w-4 h-4"></i>
            <span class="text-sm font-medium">Profile</span>
          </a>
          <a href="{{ route('orders') }}"
            class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('orders') ? 'border-gold text-gold bg-gold/10' : 'bg-surface border border-surface2 hover:border-gold text-white hover:text-gold' }} transition-colors rounded-sm">
            <i data-feather="shopping-bag" class="w-4 h-4"></i>
            <span class="text-sm font-medium">Orders</span>
          </a>
          <form action="{{ route('logout') }}" method="POST" class="flex items-center gap-3 px-4 py-3 bg-surface border border-surface2 hover:border-gold text-white hover:text-gold transition-colors rounded-sm">
            @csrf
            <i data-feather="log-out" class="w-4 h-4"></i>
            <button type="submit" class="text-sm font-medium cursor-pointer">Logout</button>
          </form>
        </div>
      </div>

      <!-- Main Content - Profile Details -->
      <div class="lg:col-span-2 flex flex-col gap-6">

        <!-- Personal Information -->
        <div class="bg-surface border border-surface2 p-8">
          <div class="flex items-center gap-3 mb-8 pb-6 border-b border-surface2">
            <i data-feather="user" class="w-5 h-5 text-gold"></i>
            <h3 class="text-lg font-medium text-white">Personal Information</h3>
          </div>

          <div class="space-y-6">
            <!-- Name -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs text-muted tracking-widest uppercase mb-2">Full Name</label>
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
                <label class="block text-xs text-muted tracking-widest uppercase mb-2">Phone Number</label>
                <div class="px-4 py-3 bg-bg border border-surface2 text-ink rounded-sm">
                  {{ $user->no_hp ?? '-' }}
                </div>
              </div>
              <div>
                <label class="block text-xs text-muted tracking-widest uppercase mb-2">Join Date</label>
                <div class="px-4 py-3 bg-bg border border-surface2 text-ink rounded-sm">
                  {{ $user->created_at->format('d M Y') }}
                </div>
              </div>
            </div>

            <!-- Full Address -->
            <div>
              <label class="block text-xs text-muted tracking-widest uppercase mb-2">Address</label>
              <div class="px-4 py-3 bg-bg border border-surface2 text-ink rounded-sm min-h-24 wrap-break-word">
                {{ $user->alamat ?? 'Not provided' }}
              </div>
            </div>
          </div>

          <!-- Edit Button -->
          <div class="mt-8 pt-6 border-t border-surface2">
            <a href="#"
              class="inline-flex items-center gap-2 px-6 py-3 bg-gold hover:bg-gold-lt text-bg text-sm font-medium tracking-wider uppercase transition-all duration-300 rounded-sm">
              <i data-feather="edit-2" class="w-4 h-4"></i>
              Edit Profile
            </a>
          </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-surface border border-surface2 p-8">
          <div class="flex items-center gap-3 pb-2 border-b border-surface2">
            <i data-feather="package" class="w-5 h-5 text-gold"></i>
            <h3 class="text-lg font-medium text-white">Recent Orders</h3>
          </div>

          <div class="flex flex-col items-center py-2">
            @if ($orders->isEmpty())
            <i data-feather="box" class="w-16 h-16 text-muted mb-4"></i>
            <p class="text-muted text-sm">No orders yet</p>
            <a href="{{ route('products') }}"
              class="inline-block mt-4 px-6 py-2.5 border border-gold text-gold hover:bg-gold hover:text-bg text-xs font-medium tracking-wider uppercase transition-all duration-300 rounded-sm">
              Start Shopping
            </a>
            @else
            <div class="space-y-6 w-full">
              @foreach($orders as $order)
              <a href="{{ route('orders.show', $order->id) }}" class="block bg-bg border border-surface2 p-4 hover:border-gold transition-colors">
                <div class="flex justify-between items-start mb-4 pb-2 border-b border-surface2">
                  <div>
                    <p class="text-white text-sm font-medium">Order #{{ $order->order_number }}</p>
                    <p class="text-muted text-xs mt-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                  </div>
                  <span class="px-3 py-1 bg-primary/20 text-primary border border-primary/30 text-xs font-medium rounded-sm">
                    {{ $order->status }}
                  </span>
                </div>
                
                <div class="flex items-center gap-4">
                  @foreach($order->orderItems as $item)
                  <div class="w-12 h-14 shrink-0 overflow-hidden bg-surface border border-surface2">
                    <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                  </div>
                  @endforeach
                  <div class="flex-1">
                    <p class="text-white text-sm font-medium">{{ $order->orderItems->first()->product->name }}</p>
                    <p class="text-muted text-xs mt-1">{{ $order->orderItems->count() }} item(s) • Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                  </div>
                </div>
              </a>
              @endforeach
            </div>
            @endif
          </div>
        </div>

        <!-- Account Security -->
        <div class="bg-surface border border-surface2 p-8">
          <div class="flex items-center gap-3 mb-8 pb-6 border-b border-surface2">
            <i data-feather="lock" class="w-5 h-5 text-gold"></i>
            <h3 class="text-lg font-medium text-white">Account Security</h3>
          </div>

          <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-bg border border-surface2 rounded-sm">
              <div>
                <p class="text-white text-sm font-medium">Password</p>
                <p class="text-muted text-xs mt-1">Last changed 3 months ago</p>
              </div>
              <a href="#"
                class="px-4 py-2 border border-surface2 text-muted hover:text-gold hover:border-gold text-xs font-medium tracking-wider uppercase transition-colors rounded-sm">
                Change
              </a>
            </div>

            <div class="flex items-center justify-between p-4 bg-bg border border-surface2 rounded-sm">
              <div>
                <p class="text-white text-sm font-medium">Email Verification</p>
                <p class="text-gold text-xs mt-1">✓ Verified</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-red-950/20 border border-red-900/50 p-8 rounded-sm">
          <h3 class="text-lg font-medium text-white mb-4 flex items-center gap-2">
            <i data-feather="alert-triangle" class="w-5 h-5 text-red-400"></i>
            Danger Zone
          </h3>
          <p class="text-muted text-sm mb-4">
            Once you delete your account, there is no going back. Please be certain.
          </p>
          <button
            class="px-6 py-2.5 bg-red-900 hover:bg-red-800 text-red-100 text-sm font-medium tracking-wider uppercase transition-colors rounded-sm">
            Delete Account
          </button>
        </div>
      </div>

    </div>
  </main>

  <x-footer />

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      feather.replace();
    });
  </script>
</body>

</html>
