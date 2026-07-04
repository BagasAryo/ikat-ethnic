@extends('layouts.app')

@section('title', 'Home | Ikat Ethnic')

@section('content')
  <!-- Hero Section -->
  <header class="relative w-full h-screen min-h-150 flex items-center justify-center overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 w-full h-full">
      <img src="https://images.pexels.com/photos/30236998/pexels-photo-30236998.jpeg" alt="Ikat Weaving Process"
        class="absolute inset-0 w-full h-full object-cover object-center transform scale-105" />
      <div class="absolute inset-0 bg-linear-to-b from-bg/60 via-bg/80 to-bg"></div>
      <!-- Texture Overlay -->
      <div class="absolute inset-0 opacity-[0.03] mix-blend-overlay"
        style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');">
      </div>
    </div>

    <!-- Hero Content -->
    <div
      class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-start justify-center pt-20">
      <div class="max-w-2xl">
        <p class="text-gold-lt text-xs sm:text-md font-secondary font-semibold tracking-[0.2em] uppercase pl-1">Tenun Asli
          Indonesia</p>
        <h1 class="font-body text-5xl sm:text-6xl md:text-7xl font-medium text-white leading-[1.1] mb-6 text-balance">
          Kisah Tenun dari <br /><span class="text-gold-lt">Warisan Leluhur</span>
        </h1>
        <p class="text-ink text-base sm:text-lg mb-10 max-w-lg font-body light leading-relaxed">
          Temukan karya prestisius dari para penenun mahir Sumba. Setiap benang membawa warisan budaya,
          dicelup secara alami dengan pewarna alami secara cermat.
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="{{ url('/products') }}"
            class="rounded-md inline-flex items-center justify-center px-8 py-3.5 bg-gold hover:bg-gold/95 text-bg text-sm font-medium tracking-wide uppercase transition-all duration-300">
            Belanja Sekarang
          </a>
          <a href="{{ url('/products') }}"
            class="rounded-md inline-flex items-center justify-center px-8 py-3.5 bg-transparent border border-gold/50 hover:border-gold-lt text-gold-lt text-sm font-medium tracking-wide uppercase transition-all duration-300">
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
          class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-gold hover:text-gold-lt transition-colors border-gold/30 hover:border-gold pb-1 shrink-0">
          Lihat Semua Produk <i data-feather="arrow-right" class="w-4 h-4"></i>
        </a>
      </div>

      <!-- Product Grid -->
      <div class="w-full">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
          <!-- Product Items -->
          @foreach ($products as $product)
            <div class="group cursor-pointer bg-bg hover:bg-surface p-3 -m-3 rounded-md transition-all duration-200 active:scale-[0.98] {{ $loop->index >= 2 ? 'hidden sm:block' : '' }}">
              <a class="flex flex-col" href="{{ route('product.show', $product->slug) }}">
                <div class="relative overflow-hidden bg-surface aspect-4/5 mb-4">
                  @if ($product->images?->first()?->image_url)
                    <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}"
                      class="w-full h-full object-cover">
                  @else
                    <div class="w-full h-full flex items-center justify-center object-cover">
                      <i data-feather="image" class="w-10 h-10"></i>
                    </div>
                  @endif
                </div>
                <span class="text-muted text-[10px] tracking-widest uppercase mb-1">{{ $product->category->name }}</span>
                <h3 class="text-base text-ink group-hover:text-gold-lt transition-colors mb-1 truncate">
                  {{ ucwords($product->name) }}</h3>
                <p class="text-gold text-sm font-medium tracking-wide">Rp{{ number_format($product->price, 0, ',', '.') }}
                </p>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </main>

  <!-- Simplified Feature Section -->
  {{-- <section class="border-t border-surface py-16 bg-surface/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-surface2">
        <div class="py-6 md:py-0 px-4 flex flex-col items-center">
          <i data-feather="shield" class="text-gold w-6 h-6 mb-4"></i>
          <h3 class="text-sm tracking-widest text-ink uppercase mb-2">Warisan Autentik</h3>
          <p class="text-muted text-xs font-light max-w-xs">Potongan tenun tangan asli bersertifikat langsung dari para
            pengrajin ahli.</p>
        </div>
        <div class="py-6 md:py-0 px-4 flex flex-col items-center">
          <i data-feather="heart" class="text-gold w-6 h-6 mb-4"></i>
          <h3 class="text-sm tracking-widest text-ink uppercase mb-2">Sumber Etis</h3>
          <p class="text-muted text-xs font-light max-w-xs">Praktik perdagangan adil memastikan mata pencaharian yang
            berkelanjutan untuk komunitas penenun.</p>
        </div>
        <div class="py-6 md:py-0 px-4 flex flex-col items-center">
          <i data-feather="globe" class="text-gold w-6 h-6 mb-4"></i>
          <h3 class="text-sm tracking-widest text-ink uppercase mb-2">Pengiriman Global</h3>
          <p class="text-muted text-xs font-light max-w-xs">Dikemas dengan aman dan dikirim ke seluruh dunia dengan
            asuransi pengiriman.
          </p>
        </div>
      </div>
    </div>
  </section> --}}
@endsection
