@extends('layouts.app')

@section('title', 'My Orders | Ikat Ethnic')

@section('content')
  <!-- Page Header -->
  <header class="pt-32 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full text-center">
    <h1 class="font-body text-3xl md:text-4xl font-medium text-white">My Orders</h1>
    <p class="text-muted text-sm mt-2 font-light">Track and manage your order history</p>
  </header>

  <!-- My Orders Content -->
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
            <span class="inline-block mt-3 px-3 py-1 text-xs tracking-widest uppercase bg-gold/20 text-gold rounded-full">
              {{ ucfirst($user->role ?? 'customer') }}
            </span>
          </div>

          <!-- Quick Stats -->
          <div class="w-full grid grid-cols-2 gap-4 pt-6 border-t border-surface2">
            <div class="text-center">
              <div class="text-gold text-xl font-medium">{{ $orders->count() }}</div>
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
          <form action="{{ route('logout') }}" method="POST"
            class="flex items-center gap-3 px-4 py-3 bg-surface border border-surface2 hover:border-gold text-white hover:text-gold transition-colors rounded-sm">
            @csrf
            <i data-feather="log-out" class="w-4 h-4"></i>
            <button type="submit" class="text-sm font-medium cursor-pointer">Logout</button>
          </form>
        </div>
      </div>

      <!-- Main Content - Orders List -->
      <div class="lg:col-span-2 flex flex-col gap-6">
        @if ($orders->isEmpty())
          <!-- Empty State -->
          <div class="bg-surface border border-surface2 p-12 text-center flex flex-col items-center justify-center gap-6">
            <div class="w-16 h-16 bg-surface2 rounded-full flex items-center justify-center text-muted">
              <i data-feather="box" class="w-8 h-8"></i>
            </div>
            <div>
              <h3 class="text-white text-lg font-medium">No Orders Found</h3>
              <p class="text-muted text-sm mt-1 max-w-sm">You haven't placed any orders yet. Start exploring our premium
                collections!</p>
            </div>
            <a href="{{ route('products') }}"
              class="inline-flex items-center justify-center px-6 py-3 bg-gold hover:bg-gold-lt text-bg text-xs font-semibold tracking-wider uppercase transition-all duration-300 rounded-sm">
              Start Shopping
            </a>
          </div>
        @else
          <!-- Orders Container -->
          <div class="flex flex-col gap-6">
            @foreach ($orders as $order)
              <div class="bg-surface border border-surface2 rounded-sm overflow-hidden flex flex-col">

                <!-- Order Header -->
                <div
                  class="bg-surface2/40 px-6 py-4 border-b border-surface2 flex flex-wrap justify-between items-center gap-4">
                  <div>
                    <span class="text-muted text-[10px] uppercase tracking-wider block">Order Number</span>
                    <span class="text-white font-medium text-sm md:text-base">#{{ $order->order_number }}</span>
                    <span class="text-muted text-xs block mt-0.5">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                  </div>

                  <div class="flex items-center gap-3">
                    @php
                      $statusClass = 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20';
                      if (
                          strtolower($order->status) === 'processing' ||
                          strtolower($order->status) === 'completed' ||
                          (isset($order->payment) && strtolower($order->payment->status) === 'paid')
                      ) {
                          $statusClass = 'bg-green-500/10 text-green-400 border-green-500/20';
                      } elseif (strtolower($order->status) === 'cancelled' || strtolower($order->status) === 'failed') {
                          $statusClass = 'bg-red-500/10 text-red-400 border-red-500/20';
                      }
                    @endphp
                    <span
                      class="px-3 py-1 border text-xs font-medium uppercase tracking-wider rounded-sm {{ $statusClass }}">
                      {{ $order->status }}
                    </span>
                    <a href="{{ route('orders.show', $order->id) }}"
                      class="px-3 py-1 border border-surface2 hover:border-gold text-muted hover:text-gold text-xs font-medium uppercase tracking-wider rounded-sm transition-colors">
                      Details
                    </a>
                  </div>
                </div>

                <!-- Order Items -->
                <div class="divide-y divide-surface2/50 px-6">
                  @foreach ($order->orderItems as $item)
                    <div class="py-4 flex gap-4 items-center">
                      <!-- Product Image -->
                      <div class="w-12 h-14 shrink-0 overflow-hidden bg-bg border border-surface2 rounded-sm">
                        @if ($item->product && $item->product->images->first())
                          <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                            alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                        @else
                          <div class="w-full h-full flex items-center justify-center text-muted">
                            <i data-feather="image" class="w-5 h-5"></i>
                          </div>
                        @endif
                      </div>

                      <!-- Product Info -->
                      <div class="flex-1 min-w-0">
                        <h4 class="text-white text-sm font-medium truncate">{{ ucwords($item->product_name) }}</h4>
                        <p class="text-xs text-muted font-light mt-0.5">
                          Size: <span class="text-ink font-medium">{{ $item->product_size }}</span>
                          <span class="mx-2">•</span>
                          Qty: <span class="text-ink font-medium">{{ $item->quantity }}</span>
                        </p>
                      </div>

                      <!-- Product Price & Subtotal -->
                      <div class="text-right shrink-0">
                        <p class="text-white text-sm font-medium">Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                        </p>
                        <p class="text-[10px] text-muted font-light mt-0.5">
                          Rp{{ number_format($item->unit_price, 0, ',', '.') }} each</p>
                      </div>
                    </div>
                  @endforeach
                </div>

                <!-- Order Footer -->
                <div
                  class="bg-bg/40 px-6 py-4 border-t border-surface2 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                  <!-- Shipping Info -->
                  <div class="text-xs text-muted max-w-md">
                    <span class="uppercase tracking-wider text-[10px] font-semibold block mb-1">Shipping Details</span>
                    <p class="text-ink font-medium">{{ $order->shipping_name }} ({{ $order->shipping_phone }})</p>
                    <p class="font-light mt-0.5 leading-relaxed">{{ $order->shipping_address }}</p>
                    <p class="font-light mt-0.5">{{ $order->shipping_city_name }}, {{ $order->shipping_province }}</p>
                    
                    <div class="mt-2">
                      <span class="font-medium text-white">{{ strtoupper($order->shipping_courier) }} - {{ $order->shipping_service }}</span>
                      @if($order->shipping_etd)
                        <span class="ml-1 font-light">(Est. {{ $order->shipping_etd }})</span>
                      @endif
                    </div>
                    @if($order->shipping_tracking_number)
                      <div class="mt-1">
                        <span class="uppercase tracking-wider text-[10px] font-semibold">Resi:</span>
                        <span class="text-gold font-medium">{{ $order->shipping_tracking_number }}</span>
                      </div>
                    @endif
                  </div>

                  <!-- Calculations and Action -->
                  <div class="text-right flex flex-col items-end gap-3 w-full md:w-auto shrink-0">
                    <div class="text-xs text-muted flex flex-col gap-1 w-full sm:w-48">
                      <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span class="text-ink">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span>Shipping:</span>
                        <span class="text-ink">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                      </div>
                      <div
                        class="flex justify-between text-sm text-white font-medium border-t border-surface2/30 pt-1.5 mt-1">
                        <span>Total:</span>
                        <span
                          class="text-gold font-semibold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                      </div>
                    </div>

                    <!-- Pay Button if Pending -->
                    @if (strtolower($order->status) === 'pending' &&
                            $order->payment &&
                            $order->payment->snap_token &&
                            strtolower($order->payment->status) === 'pending')
                      <button type="button"
                        onclick="payOrder('{{ $order->payment->snap_token }}', '{{ $order->id }}')"
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gold hover:bg-gold-lt text-bg text-xs font-semibold tracking-wider uppercase transition-all duration-300 rounded-sm cursor-pointer select-none">
                        <i data-feather="credit-card" class="w-3.5 h-3.5"></i>
                        Pay Now
                      </button>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    function payOrder(snapToken, orderId) {
      if (typeof window.snap === 'undefined') {
        alert("Midtrans SDK failed to load. Please refresh the page.");
        return;
      }

      window.snap.pay(snapToken, {
        onSuccess: function(result) {
          window.location.href = "{{ route('checkout.success') }}?order_id=" + orderId;
        },
        onPending: function(result) {
          window.location.reload();
        },
        onError: function(result) {
          alert("Payment failed: " + (result.status_message || "Unknown error"));
        },
        onClose: function() {
          // Do nothing
        }
      });
    }
  </script>
@endpush
