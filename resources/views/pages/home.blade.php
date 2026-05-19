<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ikat Ethnic</title>

  <!-- Fonts: Playfair Display & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
    rel="stylesheet">

  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- Styles / Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
  class="bg-bg text-ink font-body antialiased selection:bg-gold selection:text-obsidian-900 flex flex-col min-h-screen">

  <!-- Navigation -->
  <x-navbar />

  <!-- Hero Section -->
  <header class="relative w-full h-screen min-h-[600px] flex items-center justify-center overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 w-full h-full">
      <img
        src="https://images.unsplash.com/photo-1605518216938-7c31b7b14ad0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80"
        alt="Ikat Weaving Process"
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
        <p class="text-gold-lt text-xs sm:text-sm font-semibold tracking-[0.2em] uppercase mb-4 pl-1">The Sumba
          Collection</p>
        <h1 class="font-body text-5xl sm:text-6xl md:text-7xl font-medium text-white leading-[1.1] mb-6 text-balance">
          Woven Stories of <br /><span class="text-gold-lt">Ancient Wisdom</span>
        </h1>
        <p class="text-ink text-base sm:text-lg mb-10 max-w-lg font-body light leading-relaxed">
          Discover the prestigious craftsmanship of Sumba's master weavers. Each thread carries a legacy of culture,
          meticulously hand-dyed with natural pigments.
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="#catalog"
            class="inline-flex items-center justify-center px-8 py-3.5 bg-gold hover:bg-gold-lt text-bg text-sm font-medium tracking-wide uppercase transition-all duration-300">
            Explore Sumba
          </a>
          <a href="#"
            class="inline-flex items-center justify-center px-8 py-3.5 bg-transparent border border-gold/50 hover:border-gold-lt text-gold-lt text-sm font-medium tracking-wide uppercase transition-all duration-300">
            View Gallery
          </a>
        </div>
      </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex flex-col items-center animate-bounce">
      <span class="text-[10px] tracking-[0.2em] uppercase text-muted mb-2">Scroll</span>
      <i data-feather="chevron-down" class="text-gold w-4 h-4"></i>
    </div>
  </header>

  <!-- Main Content: Product Catalog (Homepage) -->
  <main id="catalog" class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative z-20">
    <div class="flex flex-col lg:flex-row gap-12 items-center lg:items-start">
      <!-- Left side: Text and Button -->
      <div class="lg:w-1/4 flex flex-col justify-center lg:pt-8">
        <h2 class="font-body text-3xl font-medium text-ink mb-4">Curated Pieces</h2>
        <p class="text-muted text-sm font-light leading-relaxed mb-8">
          Explore our most sought-after handwoven masterpieces. Each piece tells a unique story of Indonesian heritage.
        </p>
        <a href="{{ url('/products') }}" class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-gold hover:text-gold-lt transition-colors border-b border-gold/30 hover:border-gold pb-1 self-start">
          View All Products <i data-feather="arrow-right" class="w-4 h-4"></i>
        </a>
      </div>

      <!-- Right side: Product Row (Smaller cards) -->
      <div class="lg:w-3/4 w-full">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
          <!-- Product Item 1 -->
          <div class="group cursor-pointer">
            <div class="relative overflow-hidden bg-surface aspect-4/5 mb-4">
              <img
                src="{{ asset('/storage/products/kain-tenun-toraja.webp') }}"
                alt="Kain Tenun Toraja"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
              <div class="absolute inset-x-0 bottom-0 p-3 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                <button class="w-full bg-gold hover:bg-gold-lt text-bg font-medium text-xs py-2.5 uppercase tracking-wider">Add to Bag</button>
              </div>
            </div>
            <div class="flex flex-col">
              <span class="text-muted text-[10px] tracking-widest uppercase mb-1">Sumba</span>
              <h3 class="text-base text-ink group-hover:text-gold-lt transition-colors mb-1 truncate">Hinggi Kombu Heritage</h3>
              <p class="text-gold text-sm font-medium tracking-wide">IDR 12,500,000</p>
            </div>
          </div>

          <!-- Product Item 2 -->
          <div class="group cursor-pointer">
            <div class="relative overflow-hidden bg-surface aspect-4/5 mb-4">
              <img
                src="{{ asset('/storage/products/kain-tenun-bunga2.webp') }}"
                alt="Kain Bunga"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
              <div class="absolute inset-x-0 bottom-0 p-3 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                <button class="w-full bg-gold hover:bg-gold-lt text-bg font-medium text-xs py-2.5 uppercase tracking-wider">Add to Bag</button>
              </div>
            </div>
            <div class="flex flex-col">
              <span class="text-muted text-[10px] tracking-widest uppercase mb-1">Toraja</span>
              <h3 class="text-base text-ink group-hover:text-gold-lt transition-colors mb-1 truncate">Pa'tedong Gold Thread</h3>
              <p class="text-gold text-sm font-medium tracking-wide">IDR 8,200,000</p>
            </div>
          </div>

          <!-- Product Item 3 -->
          <div class="group cursor-pointer">
            <div class="relative overflow-hidden bg-surface aspect-4/5 mb-4">
              <img
                src="{{ asset('/storage/products/kain-tenun-bunga.webp') }}"
                alt="Kain Tenun Bunga"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
              <div class="absolute inset-x-0 bottom-0 p-3 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                <button class="w-full bg-gold hover:bg-gold-lt text-bg font-medium text-xs py-2.5 uppercase tracking-wider">Add to Bag</button>
              </div>
            </div>
            <div class="flex flex-col">
              <span class="text-muted text-[10px] tracking-widest uppercase mb-1">Sikka</span>
              <h3 class="text-base text-ink group-hover:text-gold-lt transition-colors mb-1 truncate">Sikka Silk Sarong</h3>
              <p class="text-gold text-sm font-medium tracking-wide">IDR 15,000,000</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Simplified Feature Section -->
  <section class="border-t border-surface py-16 bg-surface/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-surface2">
        <div class="py-6 md:py-0 px-4 flex flex-col items-center">
          <i data-feather="shield" class="text-gold w-6 h-6 mb-4"></i>
          <h3 class="text-sm tracking-widest text-ink uppercase mb-2">Authentic Heritage</h3>
          <p class="text-muted text-xs font-light max-w-xs">Certified genuine handwoven pieces directly from masterful artisans.</p>
        </div>
        <div class="py-6 md:py-0 px-4 flex flex-col items-center">
          <i data-feather="heart" class="text-gold w-6 h-6 mb-4"></i>
          <h3 class="text-sm tracking-widest text-ink uppercase mb-2">Ethical Sourcing</h3>
          <p class="text-muted text-xs font-light max-w-xs">Fair trade practices ensuring sustainable livelihoods for weaving communities.</p>
        </div>
        <div class="py-6 md:py-0 px-4 flex flex-col items-center">
          <i data-feather="globe" class="text-gold w-6 h-6 mb-4"></i>
          <h3 class="text-sm tracking-widest text-ink uppercase mb-2">Global Shipping</h3>
          <p class="text-muted text-xs font-light max-w-xs">Securely packaged and shipped worldwide with insured delivery.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <x-footer />

</body>

</html>
