@extends('layouts.app')

@section('title', 'Cart | Ikat Ethnic')

@section('content')
  <!-- Page Header -->
  <header class="pt-32 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
    <h1 class="font-body text-3xl md:text-4xl font-medium text-white">Keranjang Belanja</h1>
    <p class="text-muted text-sm mt-2 font-light">Silahkan periksa kembali pesanan anda sebelum checkout.</p>
  </header>

  <!-- Cart Content -->
  <main class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-20">
    @if ($cartItems->isEmpty())
      <!-- Empty Cart State -->
      <div class="flex flex-col items-center justify-center py-14 px-4">
        <div class="text-center max-w-md">
          <div class="mb-8">
            <i data-feather="shopping-cart" class="w-12 h-12 text-surface2 mx-auto"></i>
          </div>

          <h2 class="text-2xl font-medium text-white mb-3">Keranjang Anda Kosong</h2>
          <p class="text-muted mb-8 leading-relaxed">
            @if (Auth::check())
              Sepertinya Anda belum menambahkan barang apa pun. Mulailah menjelajahi koleksi kami untuk menemukan sesuatu yang Anda sukai.
            @else
              Silahkan masuk ke akun Anda untuk menambahkan barang ke keranjang.
            @endif
          </p>

          <div class="flex flex-col gap-3">
            @if (!Auth::check())
              <a href="{{ route('login') }}"
                class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-gold hover:bg-gold-lt text-bg text-sm font-medium tracking-wider uppercase transition-all duration-300">
                Masuk Ke Akun
              </a>
            @endif
            <a href="{{ route('products') }}"
              class="w-full inline-flex items-center justify-center px-6 py-3.5 border border-surface2 text-muted hover:text-gold hover:border-gold text-sm font-medium tracking-wider uppercase transition-all duration-300">
              Lanjutkan Belanja
            </a>
          </div>
        </div>
      </div>
    @else
      <!-- Cart with Items -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-start">

        <!-- Cart Items -->
        <div class="lg:col-span-2 gap-8 flex flex-col">
          @foreach ($cartItems as $item)
            <div class="cart-item flex gap-4 sm:gap-5 items-start border-b border-surface2 pb-8"
              data-price="{{ $item->product->price }}" data-item-id="{{ $item->id }}">
              <input type="checkbox"
                class="item-checkbox w-5 h-5 rounded border-surface2 bg-bg text-gold focus:ring-gold accent-gold cursor-pointer mt-1 shrink-0"
                checked>
              <div class="w-20 h-24 sm:w-24 sm:h-28 shrink-0 overflow-hidden bg-surface">
                <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                  alt="{{ $item->product->name }}" class="w-full h-full object-cover">
              </div>
              <div class="flex flex-col flex-1 gap-1.5">
                <span class="text-muted text-[10px] tracking-widest uppercase">{{ $item->product->origin }}</span>
                <h3 class="text-sm sm:text-base text-white font-medium leading-snug">{{ ucwords($item->product->name) }}</h3>
                <h5 class="text-xs text-muted font-light">{{ $item->product_size->name }}</h5>
                <p class="text-gold text-sm font-medium tracking-wide">Rp
                  {{ number_format($item->product->price, 0, ',', '.') }}</p>

                <!-- Qty Controls -->
                <div class="flex flex-wrap items-center gap-3 sm:gap-4 mt-2">
                  <div class="flex items-center border border-surface2 rounded-sm">
                    <button
                      class="btn-qty-minus px-3 py-1.5 text-muted hover:text-gold-lt transition-colors text-sm cursor-pointer">-</button>
                    <span
                      class="item-qty px-3 py-1.5 text-ink text-sm border-x border-surface2 min-w-9 text-center">{{ $item->quantity }}</span>
                    <button
                      class="btn-qty-plus px-3 py-1.5 text-muted hover:text-gold-lt transition-colors text-sm cursor-pointer">+</button>
                  </div>
                  <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="btn-remove text-muted hover:text-red-400 transition-colors text-xs tracking-widest uppercase flex items-center gap-1 cursor-pointer">
                      <i data-feather="trash-2" class="w-3.5 h-3.5"></i> Hapus
                    </button>
                  </form>
                </div>
              </div>
              <p class="item-total-display text-gold font-medium text-sm shrink-0 hidden sm:block">
                Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
            </div>
          @endforeach
        </div>

        <!-- Order Summary -->
        <div class="bg-surface border border-surface2 p-6 sm:p-8 flex flex-col gap-5 lg:sticky lg:top-24">
          <h2 class="text-white font-medium tracking-wide text-base">Ringkasan Pesanan</h2>

          <div class="flex flex-col gap-3 text-sm border-b border-surface2 pb-5">
            <div class="flex justify-between">
              <span id="summary-qty-label" class="text-muted"></span>
              <span id="summary-subtotal" class="text-ink"></span>
            </div>
          </div>

          <div class="flex justify-between font-medium">
            <span class="text-muted text-sm uppercase tracking-widest">Total</span>
            <span id="summary-total" class="text-white text-lg"></span>
          </div>

          <a href="{{ route('checkout') }}" id="btn-checkout"
            class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-sm bg-gold hover:bg-gold-lt text-bg text-sm font-medium tracking-wider uppercase transition-all duration-300 text-center">
            Lanjutkan Ke Pembayaran
          </a>

          <div class="flex items-center justify-center gap-2 text-muted text-[10px] tracking-widest uppercase">
            <i data-feather="lock" class="w-3 h-3"></i>
            <span>Checkout Aman & Terenkripsi</span>
          </div>
        </div>

      </div>
    @endif
  </main>
@endsection

@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const cartItems = document.querySelectorAll(".cart-item");
      const summaryQtyLabel = document.getElementById("summary-qty-label");
      const summarySubtotal = document.getElementById("summary-subtotal");
      const summaryTotal = document.getElementById("summary-total");
      const btnCheckout = document.getElementById("btn-checkout");

      function formatIDR(amount) {
        return "Rp" + amount.toLocaleString("id-ID");
      }

      function updateTotals() {
        let subtotal = 0;
        let checkedCount = 0;
        let checkoutUrl = new URL("{{ route('checkout') }}", window.location.origin);

        cartItems.forEach(item => {
          if (item.style.display === "none") return;

          const checkbox = item.querySelector(".item-checkbox");
          const price = parseInt(item.getAttribute("data-price"), 10);
          const qty = parseInt(item.querySelector(".item-qty").textContent, 10);

          const itemTotalDisplay = item.querySelector(".item-total-display");
          const itemTotalPrice = price * qty;
          if (itemTotalDisplay) {
            itemTotalDisplay.textContent = formatIDR(itemTotalPrice);
          }

          if (checkbox && checkbox.checked) {
            subtotal += itemTotalPrice;
            checkedCount += qty;
            const itemId = item.getAttribute("data-item-id");
            checkoutUrl.searchParams.append('cart_items[]', itemId);
          }
        });

        if (summaryQtyLabel) {
          summaryQtyLabel.textContent = `Subtotal (${checkedCount} item${checkedCount !== 1 ? 's' : ''})`;
        }
        if (summarySubtotal) {
          summarySubtotal.textContent = formatIDR(subtotal);
        }
        if (summaryTotal) {
          summaryTotal.textContent = formatIDR(subtotal);
        }

        if (btnCheckout) {
          btnCheckout.href = checkoutUrl.toString();
          if (checkedCount === 0) {
            btnCheckout.classList.add("opacity-50", "pointer-events-none");
          } else {
            btnCheckout.classList.remove("opacity-50", "pointer-events-none");
          }
        }
      }

      cartItems.forEach(item => {
        const checkbox = item.querySelector(".item-checkbox");
        const btnMinus = item.querySelector(".btn-qty-minus");
        const btnPlus = item.querySelector(".btn-qty-plus");
        const qtySpan = item.querySelector(".item-qty");
        const btnRemove = item.querySelector(".btn-remove");
        const itemId = item.getAttribute("data-item-id");

        function updateQuantityInDatabase(qty) {
            fetch(`/cart/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: qty })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    updateTotals();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        if (checkbox) {
          checkbox.addEventListener("change", updateTotals);
        }

        if (btnMinus && qtySpan) {
          btnMinus.addEventListener("click", () => {
            let currentQty = parseInt(qtySpan.textContent, 10);
            if (currentQty > 1) {
              qtySpan.textContent = currentQty - 1;
              updateTotals();
              updateQuantityInDatabase(currentQty - 1);
            }
          });
        }

        if (btnPlus && qtySpan) {
          btnPlus.addEventListener("click", () => {
            let currentQty = parseInt(qtySpan.textContent, 10);
            qtySpan.textContent = currentQty + 1;
            updateTotals();
            updateQuantityInDatabase(currentQty + 1);
          });
        }

        if (btnRemove) {
          btnRemove.addEventListener("click", () => {
            item.style.display = "none";
            if (checkbox) {
              checkbox.checked = false;
            }
            updateTotals();
          });
        }
      });

      updateTotals();
    });
  </script>
@endpush
