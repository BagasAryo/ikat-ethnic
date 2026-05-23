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

        <p class="text-muted leading-relaxed mb-6">{{ ucfirst($product->description) ?? 'No description available.' }}</p>

        <div class="mb-6">
          <h4 class="text-white text-sm font-medium mb-2 uppercase tracking-widest">Available Sizes</h4>
          <div class="flex flex-wrap gap-2">
            @foreach ($product->sizes as $size)
              <div
                class="px-4 py-2 border border-surface2 rounded-sm text-sm {{ $size->stock > 0 ? 'text-ink' : 'text-muted' }}">
                {{ $size->name }} @if ($size->stock <= 0)
                  <span class="text-xs text-muted">(Sold out)</span>
                @endif
              </div>
            @endforeach
            @if ($product->sizes->isEmpty())
              <div class="text-muted text-sm">No size information available.</div>
            @endif
          </div>
        </div>

        <div class="flex items-center gap-4">
          <a href="#" class="px-4 py-3 border border-gold/40 rounded-sm text-gold text-sm">Buy Now</a>
          <button class="px-4 py-3 bg-gold text-bg hover:bg-gold-lt rounded-sm text-sm font-medium">Add to Cart</button>
        </div>
      </div>

    </div>
  </main>

  <x-footer />

</body>

</html>
