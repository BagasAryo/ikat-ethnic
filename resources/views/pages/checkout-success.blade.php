<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Payment Success | Ikat Ethnic</title>

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

  <!-- Success Main Card -->
  <main class="grow w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-24 relative z-20 flex items-center justify-center">
    <div class="bg-surface border border-surface2 p-8 md:p-12 w-full text-center flex flex-col items-center gap-6">
      
      <!-- Checkmark Icon Circle -->
      <div class="w-16 h-16 bg-gold/10 text-gold rounded-full flex items-center justify-center mb-2">
        <i data-feather="check-circle" class="w-10 h-10"></i>
      </div>

      <h1 class="font-body text-2xl md:text-3xl font-medium text-white">Thank You for Your Order!</h1>
      <p class="text-muted text-sm max-w-md -mt-3 font-light leading-relaxed">
        Your payment has been successfully processed. An admin will prepare and dispatch your item shortly.
      </p>

      <!-- Order details grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full text-left bg-bg border border-surface2 p-6 rounded-sm mt-4">
        <div class="flex flex-col gap-3">
          <div>
            <span class="text-muted text-[10px] uppercase tracking-wider block">Order Number</span>
            <span class="text-white text-sm font-medium">{{ $order->order_number }}</span>
          </div>
          <div>
            <span class="text-muted text-[10px] uppercase tracking-wider block">Payment Status</span>
            <span class="inline-block px-2.5 py-0.5 bg-green-500/10 text-green-400 text-xs font-semibold rounded-sm mt-1 uppercase tracking-wider">
              {{ $order->payment ? strtoupper($order->payment->status) : 'PAID' }}
            </span>
          </div>
        </div>

        <div class="flex flex-col gap-3">
          <div>
            <span class="text-muted text-[10px] uppercase tracking-wider block">Receiver & Address</span>
            <span class="text-white text-sm font-medium block">{{ $order->shipping_name }} ({{ $order->shipping_phone }})</span>
            <span class="text-muted text-xs font-light block mt-0.5 leading-relaxed">{{ $order->shipping_address }}</span>
          </div>
        </div>
      </div>

      <!-- Item Details list -->
      <div class="w-full text-left mt-4 border border-surface2 rounded-sm overflow-hidden">
        <div class="bg-bg px-4 py-3 border-b border-surface2">
          <h3 class="text-white text-xs uppercase tracking-wider font-semibold">Ordered Items</h3>
        </div>
        <div class="divide-y divide-surface2">
          @foreach ($order->orderItems as $item)
            <div class="flex justify-between items-center p-4">
              <div class="flex items-center gap-4">
                @if($item->product && $item->product->images->first())
                  <div class="w-10 h-12 overflow-hidden bg-surface border border-surface2">
                    <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                      alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                  </div>
                @endif
                <div>
                  <h4 class="text-xs text-white font-medium">{{ ucwords($item->product_name) }}</h4>
                  <p class="text-[10px] text-muted font-light mt-0.5">Size: {{ $item->product_size }} | Qty: {{ $item->quantity }}</p>
                </div>
              </div>
              <span class="text-gold text-xs font-medium">
                Rp{{ number_format($item->subtotal, 0, ',', '.') }}
              </span>
            </div>
          @endforeach
        </div>
        
        <!-- Order totals calculation -->
        <div class="bg-bg p-4 flex flex-col gap-2 border-t border-surface2 text-xs">
          <div class="flex justify-between text-muted">
            <span>Subtotal</span>
            <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between text-muted">
            <span>Shipping Cost</span>
            <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between text-white font-medium border-t border-surface2/30 pt-2 mt-1">
            <span class="uppercase tracking-wider">Total Paid</span>
            <span class="text-gold font-semibold text-sm">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
          </div>
        </div>
      </div>

      <!-- Action buttons -->
      <div class="flex flex-col sm:flex-row gap-4 mt-6 w-full max-w-md justify-center">
        <a href="{{ route('orders') }}"
          class="flex-1 inline-flex items-center justify-center px-6 py-3.5 border border-surface2 hover:border-gold hover:text-gold text-muted text-sm font-semibold tracking-wider uppercase transition-all duration-300">
          View My Orders
        </a>
        <a href="{{ route('products') }}"
          class="flex-1 inline-flex items-center justify-center px-6 py-3.5 bg-gold hover:bg-gold-lt text-bg text-sm font-semibold tracking-wider uppercase transition-all duration-300">
          Continue Shopping
        </a>
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
