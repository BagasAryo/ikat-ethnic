@extends('layouts.app')

@section('title', 'Home | Ikat Ethnic')

@section('content')
  <!-- Hero Section -->
  <header class="relative w-full h-screen min-h-150 flex items-center justify-center overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 w-full h-full">
      <img src="https://images.pexels.com/photos/30236998/pexels-photo-30236998.jpeg" alt="Ikat Weaving Process"
        class="absolute inset-0 w-full h-full object-cover object-center transform scale-105" />
      <div class="absolute inset-0 bg-linear-to-b from-black/40 via-black/50 to-bg"></div>
      <!-- Texture Overlay -->
      <div class="absolute inset-0 opacity-[0.03] mix-blend-overlay"
        style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');">
      </div>
    </div>

    <!-- Hero Content -->
    <div
      class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-start justify-center pt-20">
      <div class="max-w-2xl">
        <p
          class="inline-block bg-white/15 border border-white/30 text-white text-xs font-secondary font-semibold tracking-[0.2em] uppercase px-3 py-1 rounded-full mb-3">
          Tenun Asli Indonesia
        </p>
        <h1 class="font-body text-5xl sm:text-6xl md:text-7xl font-medium text-white leading-[1.1] mb-6 text-balance">
          Kisah Tenun dari <br /><span class="text-gold-lt">Warisan Leluhur</span>
        </h1>
        <p class="text-white/80 text-base sm:text-lg mb-10 max-w-lg font-body light leading-relaxed">
          Temukan karya prestisius dari para penenun mahir Sumba. Setiap benang membawa warisan budaya,
          dicelup secara alami dengan pewarna alami secara cermat.
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="{{ url('/products') }}"
            class="rounded-md inline-flex items-center justify-center px-8 py-3.5 bg-gold hover:bg-gold/95 text-white text-sm font-medium tracking-wide uppercase transition-all duration-300">
            Belanja Sekarang
          </a>
          <a href="{{ url('/products') }}"
            class="rounded-md inline-flex items-center justify-center px-8 py-3.5 bg-transparent border border-white/50 hover:border-white text-white text-sm font-medium tracking-wide uppercase transition-all duration-300">
            Lihat Koleksi
          </a>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content: Product Catalog (Homepage) -->
  <main id="catalog" class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative z-20">
    <div class="flex flex-col gap-10">
      <!-- Header: Text and Button -->
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6">
        <div class="max-w-2xl">
          <h2 class="font-body text-3xl font-medium text-ink mb-4">Koleksi Terbaru</h2>
          <p class="text-muted text-sm font-light leading-relaxed">
            Jelajahi produk tenun kami. Setiap kain menceritakan kisah unik dari warisan Indonesia.
          </p>
        </div>
        <a href="{{ url('/products') }}"
          class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-ink/70 hover:text-ink transition-all border-ink/30 hover:border-ink pb-1 shrink-0 group">
          Lihat Semua Produk <i data-feather="arrow-right"
            class="w-4 h-4 text-gold group-hover:translate-x-1 transition-transform"></i>
        </a>
      </div>

      <!-- Product Grid -->
      <div class="w-full">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
          <!-- Product Items -->
          @foreach ($products as $product)
            <div
              class="group cursor-pointer bg-surface border border-black/8 hover:border-gold/30 hover:shadow-md p-3 rounded-sm transition-all duration-200 {{ $loop->index >= 2 ? 'hidden sm:block' : '' }}">
              <a class="flex flex-col" href="{{ route('product.show', $product->slug) }}">
                <div class="relative overflow-hidden bg-surface2 aspect-4/5 mb-4">
                  @if ($product->images?->first()?->image_url)
                    <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}"
                      class="w-full h-full object-cover transition-transform duration-500">
                  @else
                    <div class="w-full h-full flex items-center justify-center object-cover">
                      <i data-feather="image" class="w-10 h-10 text-faint"></i>
                    </div>
                  @endif
                </div>
                <span class="text-muted text-[10px] tracking-widest uppercase mb-1">{{ $product->category->name }}</span>
                <h3 class="text-sm font-medium text-ink/80 group-hover:text-ink transition-all mb-1 truncate">
                  {{ ucwords($product->name) }}</h3>
                <p class="text-ink text-sm font-medium tracking-wide">Rp{{ number_format($product->price, 0, ',', '.') }}
                </p>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </main>
@endsection
