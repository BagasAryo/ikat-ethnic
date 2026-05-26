<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $product->name }} | Ikat Ethnic</title>

  <script src="https://unpkg.com/feather-icons"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
  class="bg-bg text-ink font-body antialiased selection:bg-gold selection:text-obsidian-900 flex flex-col min-h-screen">

  <x-navbar />

  <main class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-20 pt-28">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
      <div>
        <div class="bg-surface p-4">
          <img
            src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_url) : 'https://via.placeholder.com/600x800?text=No+Image' }}"
            alt="{{ $product->name }}" class="w-full h-130 object-cover">
        </div>

        @if ($product->images->count() > 1)
          <div class="mt-4 grid grid-cols-4 gap-3">
            @foreach ($product->images as $img)
              <img src="{{ asset('storage/' . $img->image_url) }}" alt="{{ $product->name }}"
                class="w-full h-24 object-cover border border-surface2">
            @endforeach
          </div>
        @endif
      </div>

      <div class="bg-surface p-8">
        <h1 class="text-2xl font-medium text-white mb-2">{{ ucwords($product->name) }}</h1>
        <div class="mb-4">
          <span class="text-gold font-semibold text-xl">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
          <div class="text-muted text-sm mt-1">{{ $product->category?->name ?? 'Uncategorized' }}</div>
        </div>

        <p class="text-muted leading-relaxed mb-6">{{ ucfirst($product->description) ?? 'No description available.' }}
        </p>

        <div class="mb-6">
          <form id="add-to-cart-form" action="{{ route('cart.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="product_size_id" id="selected_size_id" required>

            @if ($product->sizes->isNotEmpty())
              <div>
                <h4 class="text-white text-sm font-medium mb-3 uppercase tracking-widest">Choose Size</h4>
                <div class="flex flex-wrap gap-3" id="size-selector-container">
                  @foreach ($product->sizes as $size)
                    @if ($size->stock > 0)
                      <button type="button" 
                        data-size-id="{{ $size->id }}"
                        data-stock="{{ $size->stock }}"
                        class="size-option-btn flex flex-col items-center justify-center min-w-[80px] px-4 py-2.5 border border-surface2 text-ink text-sm rounded-sm transition-all duration-200 hover:border-gold cursor-pointer select-none">
                        <span class="font-medium text-base">{{ $size->name }}</span>
                        
                      </button>
                    @else
                      <button type="button" disabled
                        class="flex flex-col items-center justify-center min-w-[80px] px-4 py-2.5 border border-surface2/20 text-muted/40 text-sm rounded-sm cursor-not-allowed select-none">
                        <span class="font-medium text-base text-muted/40">{{ $size->name }}</span>
                        <span class="text-[9px] mt-0.5 whitespace-nowrap">Sold out</span>
                      </button>
                    @endif
                  @endforeach
                </div>
              </div>
            @else
              <div class="text-muted text-sm">No size information available.</div>
            @endif

            <!-- Quantity Selector & Helper Message -->
            <div id="selection-helper-msg" class="text-muted text-sm font-light italic">
              * Silakan pilih ukuran terlebih dahulu untuk melanjutkan pembelian.
            </div>

            <div class="space-y-2 select-none hidden" id="qty-container">
              <label class="block text-white text-sm font-medium" for="quantity-input">Quantity</label>
              <div class="flex items-center border border-surface2 rounded-sm w-32 bg-surface">
                <button type="button" id="qty-minus"
                  class="px-3 py-2 text-muted hover:text-gold-lt transition-colors text-lg font-medium cursor-pointer">-</button>
                <input type="number" name="quantity" id="quantity-input" value="1" min="1" max="1"
                  class="w-full text-center bg-transparent border-0 text-ink text-sm py-2 focus:ring-0 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                  readonly>
                <button type="button" id="qty-plus"
                  class="px-3 py-2 text-muted hover:text-gold-lt transition-colors text-lg font-medium cursor-pointer">+</button>
              </div>
              <p id="qty-stock-warning" class="text-xs text-gold/80 mt-1 font-light hidden"></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4">
              <button type="submit" id="btn-buy-now" name="buy_now" value="1"
                class="flex-1 sm:flex-initial px-6 py-3.5 border border-gold/40 hover:border-gold hover:text-gold rounded-sm text-muted text-sm font-medium tracking-wider uppercase transition-all duration-300 disabled:opacity-40 disabled:hover:border-gold/40 disabled:hover:text-muted disabled:cursor-not-allowed cursor-pointer" 
                disabled>
                Buy Now
              </button>
              <button type="submit" id="btn-add-to-cart"
                class="flex-1 sm:flex-initial px-6 py-3.5 bg-gold text-bg hover:bg-gold-lt rounded-sm text-sm font-medium tracking-wider uppercase transition-all duration-300 disabled:opacity-40 disabled:hover:bg-gold disabled:hover:text-bg disabled:cursor-not-allowed cursor-pointer"
                disabled>
                Add to Cart
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </main>

  <x-footer />

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const sizeButtons = document.querySelectorAll(".size-option-btn");
      const selectedSizeInput = document.getElementById("selected_size_id");
      const qtyContainer = document.getElementById("qty-container");
      const selectionHelperMsg = document.getElementById("selection-helper-msg");
      const qtyInput = document.getElementById("quantity-input");
      const qtyMinusBtn = document.getElementById("qty-minus");
      const qtyPlusBtn = document.getElementById("qty-plus");
      const qtyStockWarning = document.getElementById("qty-stock-warning");
      const btnBuyNow = document.getElementById("btn-buy-now");
      const btnAddToCart = document.getElementById("btn-add-to-cart");
      const form = document.getElementById("add-to-cart-form");

      if (form) {
        form.addEventListener("submit", (e) => {
          if (!selectedSizeInput.value) {
            e.preventDefault();
            alert("Silakan pilih ukuran terlebih dahulu.");
          }
        });
      }

      let selectedStock = 0;

      sizeButtons.forEach(btn => {
        btn.addEventListener("click", () => {
          // Remove active styles from all buttons
          sizeButtons.forEach(b => {
            b.classList.remove("border-gold", "text-gold", "bg-gold-dim");
            b.classList.add("border-surface2", "text-ink");
          });

          // Add active styles to clicked button
          btn.classList.remove("border-surface2", "text-ink");
          btn.classList.add("border-gold", "text-gold", "bg-gold-dim");

          // Set hidden input value
          const sizeId = btn.getAttribute("data-size-id");
          selectedSizeInput.value = sizeId;

          // Get stock
          selectedStock = parseInt(btn.getAttribute("data-stock"), 10);

          // Update Quantity constraints
          qtyInput.value = 1;
          qtyInput.max = selectedStock;

          // Toggle warning message
          updateStockWarning(1, selectedStock);

          // Show quantity container, hide helper msg
          if (qtyContainer) qtyContainer.classList.remove("hidden");
          if (selectionHelperMsg) selectionHelperMsg.classList.add("hidden");

          // Enable checkout actions
          if (btnBuyNow) {
            btnBuyNow.removeAttribute("disabled");
            btnBuyNow.classList.remove("text-muted");
            btnBuyNow.classList.add("text-gold");
          }
          if (btnAddToCart) btnAddToCart.removeAttribute("disabled");
        });
      });

      function updateStockWarning(qty, stock) {
        if (!qtyStockWarning) return;
        if (stock < 10) {
          qtyStockWarning.textContent = `Hanya tersisa ${stock} barang untuk ukuran ini.`;
          qtyStockWarning.classList.remove("hidden");
        } else {
          qtyStockWarning.classList.add("hidden");
        }
      }

      // Quantity buttons functionality
      if (qtyMinusBtn && qtyPlusBtn && qtyInput) {
        qtyMinusBtn.addEventListener("click", () => {
          let currentQty = parseInt(qtyInput.value, 10);
          if (currentQty > 1) {
            currentQty--;
            qtyInput.value = currentQty;
            updateStockWarning(currentQty, selectedStock);
          }
        });

        qtyPlusBtn.addEventListener("click", () => {
          let currentQty = parseInt(qtyInput.value, 10);
          if (currentQty < selectedStock) {
            currentQty++;
            qtyInput.value = currentQty;
            updateStockWarning(currentQty, selectedStock);
          }
        });
      }
    });
  </script>
</body>

</html>
