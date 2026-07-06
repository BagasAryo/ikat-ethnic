@extends('layouts.app')

@section('title', 'Order Detail | Ikat Ethnic')

@section('content')
    <!-- Page Header -->
  <header class="pt-32 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full text-center">
    <h1 class="font-body text-3xl md:text-4xl font-medium text-ink">Detail Pesanan</h1>
    <p class="text-muted text-sm mt-2 font-light">Informasi detail pesanan anda</p>
  </header>

  <!-- Content -->
  <main class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Left Sidebar - Profile Summary -->
      <div class="flex flex-col gap-6 border-b border-surface2 pb-8 lg:border-0 lg:pb-0">
        <!-- Profile Card -->
        <div class="bg-surface border border-surface2 p-8 flex flex-col items-center gap-6">
          <!-- Avatar -->
          <div class="w-20 h-20 rounded-full bg-linear-to-br from-gold to-gold-lt flex items-center justify-center">
            <span class="text-xl font-medium text-white uppercase">
              {{ substr($user->name, 0, 1) }}
            </span>
          </div>

          <!-- User Info -->
          <div class="text-center w-full">
            <h2 class="text-xl font-medium text-ink mb-1">{{ $user->name }}</h2>
            <p class="text-muted text-sm break-all">{{ $user->email }}</p>
          </div>

          <!-- Quick Stats -->
          <div class="w-full grid grid-cols-2 gap-4 pt-6 border-t border-surface2">
            <div class="text-center">
              <div class="text-gold text-xl font-medium">{{ Auth::user()->orders->count() }}</div>
              <div class="text-muted text-xs tracking-widest uppercase mt-1">Orders</div>
            </div>
            <div class="text-center">
              <div class="text-gold text-xl font-medium">Member</div>
              <div class="text-muted text-xs tracking-widest uppercase mt-1">Since
                {{ $user->created_at->isoFormat('MMMM Y') }}
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-3 lg:flex lg:flex-col gap-2 lg:gap-3">
          <a href="{{ route('profile') }}"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-3 px-2 lg:px-4 py-3 {{ request()->routeIs('profile*') ? 'border-gold text-gold bg-gold/10' : 'bg-surface border border-surface2 hover:border-gold text-ink hover:text-gold' }} transition-colors rounded-sm text-center lg:text-left">
            <i data-feather="user" class="w-4 h-4 shrink-0"></i>
            <span class="text-[11px] lg:text-sm font-medium">Profile</span>
          </a>
          <a href="{{ route('orders') }}"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-3 px-2 lg:px-4 py-3 {{ request()->routeIs('orders*') ? 'border-gold text-gold bg-gold/10' : 'bg-surface border border-surface2 hover:border-gold text-ink hover:text-gold' }} transition-colors rounded-sm text-center lg:text-left">
            <i data-feather="shopping-bag" class="w-4 h-4 shrink-0"></i>
            <span class="text-[11px] lg:text-sm font-medium">Orders</span>
          </a>
          <form action="{{ route('logout') }}" method="POST" class="contents">
            @csrf
            <button type="submit"
              class="w-full flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-3 px-2 lg:px-4 py-3 bg-surface border border-surface2 hover:border-gold text-ink hover:text-gold transition-colors rounded-sm text-center lg:text-left cursor-pointer">
              <i data-feather="log-out" class="w-4 h-4 shrink-0"></i>
              <span class="text-[11px] lg:text-sm font-medium">Logout</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Main Content - Order Detail Card -->
      <div class="lg:col-span-2 flex flex-col">
        
        <!-- Back Button -->
        <a href="{{ route('orders') }}" class="inline-flex items-center gap-2 text-xs text-muted hover:text-gold uppercase tracking-wider mb-6 transition-colors w-fit">
          <i data-feather="arrow-left" class="w-4 h-4"></i>
          Kembali Ke Pesanan
        </a>

        <div class="bg-surface border border-surface2 rounded-sm overflow-hidden flex flex-col">
          
          <!-- Detail Header -->
          <div class="bg-surface2/40 px-6 py-5 border-b border-surface2 flex flex-wrap justify-between items-center gap-4">
            <div>
              <span class="text-muted text-[10px] uppercase tracking-wider block">Nomor Pesanan</span>
              <h2 class="text-ink font-medium text-lg md:text-xl">#{{ $order->order_number }}</h2>
              <span class="text-muted text-xs block mt-0.5">Pesanan Dibuat Pada {{ $order->created_at->isoFormat('D MMMM Y, HH:mm') }}</span>
            </div>
            
            <div class="flex items-center gap-3">
              @php
                $statusClass = 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20';
                if (strtolower($order->status) === 'processing' || strtolower($order->status) === 'completed' || (isset($order->payment) && strtolower($order->payment->status) === 'paid')) {
                  $statusClass = 'bg-green-500/10 text-green-400 border-green-500/20';
                } elseif (strtolower($order->status) === 'cancelled' || strtolower($order->status) === 'failed') {
                  $statusClass = 'bg-red-500/10 text-red-400 border-red-500/20';
                }
              @endphp
              <span class="px-3 py-1.5 border text-xs font-semibold uppercase tracking-wider rounded-sm {{ $statusClass }}">
                {{ $order->status }}
              </span>
            </div>
          </div>

          <!-- Items Ordered Section -->
          <div class="px-6 py-4 border-b border-surface2">
            <h3 class="text-ink text-xs uppercase tracking-wider font-semibold mb-4">Produk Yang Dipesan</h3>
            <div class="divide-y divide-surface2/50">
              @foreach($order->orderItems as $item)
                <div class="py-4 flex gap-4 items-center">
                  <!-- Product Image -->
                  <div class="w-16 h-20 shrink-0 overflow-hidden bg-bg border border-surface2 rounded-sm">
                    @if($item->product && $item->product->images->first())
                      <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}" 
                        alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                    @else
                      <div class="w-full h-full flex items-center justify-center text-muted">
                        <i data-feather="image" class="w-6 h-6"></i>
                      </div>
                    @endif
                  </div>

                  <!-- Product Info -->
                  <div class="flex-1 min-w-0">
                    <h4 class="text-ink text-sm font-medium hover:text-gold transition-colors">
                      @if($item->product)
                        <a href="{{ route('product.show', $item->product->slug) }}">{{ ucwords($item->product_name) }}</a>
                      @else
                        {{ ucwords($item->product_name) }}
                      @endif
                    </h4>
                    <p class="text-xs text-muted font-light mt-1">
                      Size: <span class="text-ink font-medium">{{ $item->product_size }}</span> 
                      <span class="mx-2">•</span> 
                      Qty: <span class="text-ink font-medium">{{ $item->quantity }}</span>
                    </p>
                  </div>

                  <!-- Product Price & Subtotal -->
                  <div class="text-right shrink-0">
                    <p class="text-gold text-sm font-semibold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-muted font-light mt-0.5">Rp{{ number_format($item->unit_price, 0, ',', '.') }} each</p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <!-- Two Column Details Info -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 border-b border-surface2">
            
            <!-- Shipping Info -->
            <div class="bg-surface2 p-5 border border-surface2 rounded-sm flex flex-col gap-3">
              <div class="flex items-center gap-2 border-b border-surface2 pb-2 text-ink font-medium text-xs uppercase tracking-wider">
                <i data-feather="truck" class="w-4 h-4 text-gold"></i>
                Shipping Address
              </div>
              <div class="text-xs">
                <p class="text-ink font-medium text-sm">{{ $order->shipping_name }}</p>
                <p class="text-muted font-medium mt-1">{{ $order->shipping_phone }}</p>
                <p class="text-muted font-light mt-1">{{ $order->shipping_district_name ? $order->shipping_district_name . ', ' : '' }}{{ $order->shipping_city_name }}, {{ $order->shipping_province }}</p>
                <p class="text-muted font-light mt-2 leading-relaxed">{{ $order->shipping_address }}</p>
                
                <div class="mt-4 pt-3 border-t border-surface2">
                  <span class="text-muted block text-[10px] uppercase tracking-wider font-semibold">Courier & Service</span>
                  <span class="text-ink font-medium mt-0.5 inline-block">{{ strtoupper($order->shipping_courier) }} - {{ $order->shipping_service }}</span>
                  @if($order->shipping_etd)
                    <span class="text-muted text-[10px] ml-2">(Est. {{ $order->shipping_etd }})</span>
                  @endif
                </div>

                @if($order->shipping_tracking_number)
                  <div class="mt-3 pt-3 border-t border-surface2">
                    <span class="text-muted block text-[10px] uppercase tracking-wider font-semibold">Tracking Number</span>
                    <span class="text-gold font-medium mt-0.5 inline-block">{{ $order->shipping_tracking_number }}</span>
                  </div>
                @endif
              </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-surface2 p-5 border border-surface2 rounded-sm flex flex-col gap-3">
              <div class="flex items-center gap-2 border-b border-surface2 pb-2 text-ink font-medium text-xs uppercase tracking-wider">
                <i data-feather="credit-card" class="w-4 h-4 text-gold"></i>
                Payment Information
              </div>
              <div class="text-xs flex flex-col gap-2.5">
                <div>
                  <span class="text-muted block text-[10px] uppercase tracking-wider">Payment Method</span>
                  <span class="text-ink font-medium mt-0.5 inline-block capitalize">{{ $order->payment?->payment_method ?? 'Midtrans' }}</span>
                </div>
                <div>
                  <span class="text-muted block text-[10px] uppercase tracking-wider">Payment Status</span>
                  @php
                    $payStatusClass = 'text-yellow-400 bg-yellow-500/10 border-yellow-500/20';
                    if ($order->payment && strtolower($order->payment->status) === 'paid') {
                      $payStatusClass = 'text-green-400 bg-green-500/10 border-green-500/20';
                    } elseif ($order->payment && (strtolower($order->payment->status) === 'failed' || strtolower($order->payment->status) === 'cancelled')) {
                      $payStatusClass = 'text-red-400 bg-red-500/10 border-red-500/20';
                    }
                  @endphp
                  <span class="px-2 py-0.5 border text-[10px] font-semibold uppercase tracking-wider rounded-sm inline-block mt-1 {{ $payStatusClass }}">
                    {{ $order->payment?->status ?? 'UNPAID' }}
                  </span>
                </div>
                @if($order->payment?->transaction_id)
                  <div>
                    <span class="text-muted block text-[10px] uppercase tracking-wider">Transaction ID</span>
                    <span class="text-muted font-light mt-0.5 inline-block break-all">{{ $order->payment->transaction_id }}</span>
                  </div>
                @endif
              </div>
            </div>

          </div>

          <!-- Total Calculation and Actions -->
          <div class="bg-surface2 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
            <!-- Customer Support -->
            <div>
              <p class="text-xs text-muted font-light">Have an issue with your order?</p>
              <a href="https://wa.me/6281234567890?text=Halo%20Admin%2C%20saya%20ingin%20bertanya%20mengenai%20pesanan%20saya%20dengan%20nomor%20order%20%23{{ $order->order_number }}" 
                target="_blank"
                class="inline-flex items-center gap-2 mt-2 px-4 py-2 border border-surface2 hover:border-green-500/50 hover:text-green-400 text-muted text-xs font-semibold tracking-wider uppercase transition-colors rounded-sm">
                <i data-feather="message-circle" class="w-3.5 h-3.5"></i>
                Contact Support
              </a>
            </div>

            <!-- Pricing Calculation -->
            <div class="text-right flex flex-col items-end gap-4 w-full sm:w-auto">
              <div class="text-xs text-muted flex flex-col gap-1.5 w-full sm:w-64">
                <div class="flex justify-between">
                  <span>Subtotal:</span>
                  <span class="text-ink">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Ongkos Kirim:</span>
                  <span class="text-ink">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-ink font-medium border-t border-surface2/30 pt-2 mt-1.5">
                  <span>Total:</span>
                  <span class="text-gold font-bold text-base">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
              </div>

              <!-- Action buttons -->
              @if(strtolower($order->status) === 'pending' && $order->payment && $order->payment->snap_token && strtolower($order->payment->status) === 'pending')
                <button type="button" onclick="payOrder('{{ $order->payment->snap_token }}')"
                  class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gold hover:bg-gold-lt text-white text-xs font-semibold tracking-wider uppercase transition-all duration-300 rounded-sm cursor-pointer select-none">
                  <i data-feather="credit-card" class="w-4 h-4"></i>
                  Selesaikan Pembayaran
                </button>
              @endif
            </div>
          </div>

        </div>

      </div>

    </div>
  </main>
@endsection

@push('scripts')
  <!-- Midtrans Snap JS SDK -->
  <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>

  <script>
    function payOrder(snapToken) {
      if (typeof window.snap === 'undefined') {
        alert("Midtrans SDK failed to load. Please refresh the page.");
        return;
      }
      
      window.snap.pay(snapToken, {
        onSuccess: function(result) {
          window.location.href = "{{ route('checkout.success') }}?order_id={{ $order->id }}";
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