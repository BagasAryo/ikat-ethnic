@extends('layouts.app')

@section('title', 'Checkout | Ikat Ethnic')
@section('content')
  <!-- Page Header -->
  <header class="pt-32 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
    <h1 class="font-body text-3xl md:text-4xl font-medium text-white">Checkout</h1>
    <p class="text-muted text-sm mt-2 font-light">Complete your shipping info and complete payment securely.</p>
  </header>

  <!-- Checkout Content -->
  <main class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-20">
    <form id="checkout-form" class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
      @csrf

      <!-- Shipping Information Form -->
      <div class="lg:col-span-2 bg-surface border border-surface2 p-8 flex flex-col gap-6">
        <h2 class="text-white font-medium text-lg border-b border-surface2 pb-4 tracking-wide">Shipping Information</h2>

        <div class="flex flex-col gap-5">
          <!-- Receiver Name -->
          <div class="flex flex-col gap-2">
            <label for="shipping_name" class="text-white text-xs uppercase tracking-wider font-medium">Receiver
              Name</label>
            <input type="text" name="shipping_name" id="shipping_name" value="{{ old('shipping_name', $user->name) }}"
              required
              class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors">
          </div>

          <!-- Receiver Phone -->
          <div class="flex flex-col gap-2">
            <label for="shipping_phone" class="text-white text-xs uppercase tracking-wider font-medium">Phone
              Number</label>
            <input type="text" name="shipping_phone" id="shipping_phone"
              value="{{ old('shipping_phone', $user->no_hp) }}" required
              class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors">
          </div>

          <!-- Full Address -->
          <div class="flex flex-col gap-2">
            <label for="shipping_address" class="text-white text-xs uppercase tracking-wider font-medium">Full
              Address</label>
            <textarea name="shipping_address" id="shipping_address" rows="4" required
              class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors resize-y">{{ old('shipping_address', $user->alamat) }}</textarea>
          </div>
        </div>
      </div>

      <!-- Order Summary Card -->
      <div class="bg-surface border border-surface2 p-8 flex flex-col gap-5 sticky top-24">
        <h2 class="text-white font-medium tracking-wide text-base border-b border-surface2 pb-3">Order Summary</h2>

        <!-- Items list -->
        <div class="flex flex-col gap-4 max-h-64 overflow-y-auto pr-1">
          @foreach ($cartItems as $item)
            <div class="flex gap-4 items-center">
              <div class="w-12 h-14 shrink-0 overflow-hidden bg-bg border border-surface2">
                <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                  alt="{{ $item->product->name }}" class="w-full h-full object-cover">
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-xs text-white font-medium truncate">{{ ucwords($item->product->name) }}</h4>
                <p class="text-[10px] text-muted font-light mt-0.5">Size: {{ $item->product_size->name }} | Qty:
                  {{ $item->quantity }}</p>
              </div>
              <span class="text-gold text-xs font-medium shrink-0">
                Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
              </span>
            </div>
          @endforeach
        </div>

        <!-- Costs Breakdowns -->
        <div class="flex flex-col gap-3 text-sm border-t border-b border-surface2 py-4 mt-2">
          <div class="flex justify-between">
            <span class="text-muted">Subtotal</span>
            <span class="text-ink">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-muted">Shipping</span>
            <span class="text-ink">Rp{{ number_format($shippingCost, 0, ',', '.') }}</span>
          </div>
        </div>

        <!-- Grand Total -->
        <div class="flex justify-between font-medium">
          <span class="text-muted text-sm uppercase tracking-widest">Total</span>
          <span class="text-white text-lg font-semibold">Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>

        <!-- Submit Button -->
        <button type="submit" id="btn-pay"
          class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-sm bg-gold hover:bg-gold-lt text-bg text-sm font-semibold tracking-wider uppercase transition-all duration-300 text-center cursor-pointer select-none disabled:opacity-50 disabled:cursor-not-allowed">
          <span id="btn-text">Pay Now</span>
          <span id="btn-spinner" class="hidden ml-2 animate-spin">
            <i data-feather="loader" class="w-4 h-4"></i>
          </span>
        </button>

        <!-- Secure disclaimer -->
        <div class="flex items-center justify-center gap-2 text-muted text-[10px] tracking-widest uppercase">
          <i data-feather="lock" class="w-3 h-3"></i>
          <span>Secure Midtrans Payment Gateway</span>
        </div>
      </div>
    </form>
  </main>
@endsection

@push('scripts')
  <!-- Midtrans Snap JS SDK -->
  <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      feather.replace();

      const form = document.getElementById("checkout-form");
      const btnPay = document.getElementById("btn-pay");
      const btnText = document.getElementById("btn-text");
      const btnSpinner = document.getElementById("btn-spinner");

      form.addEventListener("submit", (e) => {
        e.preventDefault();

        // Show loading state
        btnPay.setAttribute("disabled", "true");
        btnText.textContent = "Processing...";
        btnSpinner.classList.remove("hidden");

        const formData = new FormData(form);

        fetch("{{ route('checkout.store') }}", {
            method: "POST",
            headers: {
              "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
              "Accept": "application/json"
            },
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              return response.json().then(err => {
                throw err;
              });
            }
            return response.json();
          })
          .then(data => {
            // Launch Midtrans Snap Popup
            window.snap.pay(data.snap_token, {
              onSuccess: function(result) {
                window.location.href = "{{ route('checkout.success') }}?order_id=" + data.order_id;
              },
              onPending: function(result) {
                // Redirect to user profile / order page where they can review pending orders
                window.location.href = "{{ route('orders') }}";
              },
              onError: function(result) {
                alert("Payment failed: " + (result.status_message || "Unknown error"));
                resetButtonState();
              },
              onClose: function() {
                alert("You closed the payment popup before completing the transaction.");
                resetButtonState();
              }
            });
          })
          .catch(err => {
            alert("Checkout error: " + (err.message || "Something went wrong"));
            resetButtonState();
          });
      });

      function resetButtonState() {
        btnPay.removeAttribute("disabled");
        btnText.textContent = "Pay Now";
        btnSpinner.classList.add("hidden");
      }
    });
  </script>
@endpush
