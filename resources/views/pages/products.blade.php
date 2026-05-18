<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Products | Ikat Ethnic</title>

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

<body class="bg-bg text-ink font-body antialiased selection:bg-gold selection:text-obsidian-900 flex flex-col min-h-screen">

  <x-navbar />

  <!-- Page Header -->
  <header class="pt-32 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full text-center">
    <h1 class="font-body text-4xl md:text-5xl font-medium text-white mb-4">Our Heritage Collection</h1>
    <p class="text-muted text-sm max-w-2xl mx-auto font-light leading-relaxed">
      Explore our complete collection of authentic, hand-woven masterpieces from across the Indonesian archipelago.
    </p>
  </header>

  <!-- Main Content: Product Catalog -->
  <main id="catalog" class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-20">

    <!-- Section Header & Filter/Search -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-16">
      <div class="flex-1">
        <div class="flex items-center justify-between w-full">
          <!-- Filters -->
          <div class="flex flex-wrap gap-3">
            <button class="px-5 py-2 rounded-full bg-gold text-bg text-xs font-semibold tracking-wider uppercase transition-colors">
              All Pieces
            </button>
            <button class="px-5 py-2 rounded-full bg-surface border border-surface2 hover:border-gold/50 text-ink hover:text-gold-lt text-xs font-semibold tracking-wider uppercase transition-colors">
              Sumba
            </button>
            <button class="px-5 py-2 rounded-full bg-surface border border-surface2 hover:border-gold/50 text-ink hover:text-gold-lt text-xs font-semibold tracking-wider uppercase transition-colors">
              Toraja
            </button>
            <button class="px-5 py-2 rounded-full bg-surface border border-surface2 hover:border-gold/50 text-ink hover:text-gold-lt text-xs font-semibold tracking-wider uppercase transition-colors">
              Ikat
            </button>
            <button class="px-5 py-2 rounded-full bg-surface border border-surface2 hover:border-gold/50 text-ink hover:text-gold-lt text-xs font-semibold tracking-wider uppercase transition-colors">
              Batik Silk
            </button>
          </div>

          <!-- Search & Sort -->
          <div class="hidden lg:flex items-center gap-6">
            <div class="flex items-center gap-2 text-xs text-muted uppercase tracking-widest">
              <span>Sort By:</span>
              <button class="font-medium text-gold-lt flex items-center gap-1 hover:text-gold-lt">
                Newest Arrivals <i data-feather="chevron-down" class="w-3 h-3"></i>
              </button>
            </div>

            <div class="relative group">
              <input type="text" placeholder="Search heritage..."
                class="w-64 bg-surface border border-surface2 focus:border-gold text-ink text-sm px-4 py-2.5 pl-10 rounded-sm outline-none transition-colors placeholder:text-muted">
              <i data-feather="search"
                class="w-4 h-4 text-muted absolute left-3 top-1/2 transform -translate-y-1/2 group-focus-within:text-gold transition-colors"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-16">

      <!-- Product Item 1 -->
      <div class="group cursor-pointer">
        <div class="relative overflow-hidden bg-surface aspect-4/5 mb-5">
          <img
            src="https://images.unsplash.com/photo-1584989658253-731cc91cce66?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
            alt="Hinggi Kombu"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
          <!-- Badges -->
          <div class="absolute top-4 left-4 flex gap-2">
            <span
              class="bg-bg/80 backdrop-blur-sm text-gold-lt text-[10px] font-bold tracking-[0.2em] uppercase px-3 py-1">Masterpiece</span>
          </div>
          <!-- Hover Action -->
          <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
            <button class="w-full bg-gold hover:bg-gold-lt text-bg font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
          </div>
        </div>
        <div class="flex flex-col">
          <span class="text-muted text-xs tracking-widest uppercase mb-1">Sumba, East Nusa Tenggara</span>
          <h3 class="text-lg text-ink group-hover:text-gold-lt transition-colors mb-2">Hinggi Kombu Heritage</h3>
          <p class="text-gold font-medium tracking-wide">IDR 12,500,000</p>
        </div>
      </div>

      <!-- Product Item 2 -->
      <div class="group cursor-pointer">
        <div class="relative overflow-hidden bg-surface aspect-4/5 mb-5">
          <img
            src="https://images.unsplash.com/photo-1603525281516-773a4b67f1de?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
            alt="Batik Silk"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
          <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
            <button class="w-full bg-gold hover:bg-gold-lt text-bg font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
          </div>
        </div>
        <div class="flex flex-col">
          <span class="text-muted text-xs tracking-widest uppercase mb-1">Toraja, South Sulawesi</span>
          <h3 class="text-lg text-ink group-hover:text-gold-lt transition-colors mb-2">Pa'tedong Gold Thread</h3>
          <p class="text-gold font-medium tracking-wide">IDR 8,200,000</p>
        </div>
      </div>

      <!-- Product Item 3 -->
      <div class="group cursor-pointer">
        <div class="relative overflow-hidden bg-surface aspect-4/5 mb-5">
          <img
            src="https://images.unsplash.com/photo-1616423458667-1721b017b209?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
            alt="Ikat Silk"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
          <div class="absolute top-4 left-4 flex gap-2">
            <span
              class="bg-bg/80 backdrop-blur-sm text-gold-lt text-[10px] font-bold tracking-[0.2em] uppercase px-3 py-1">New Arrival</span>
          </div>
          <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
            <button class="w-full bg-gold hover:bg-gold-lt text-bg font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
          </div>
        </div>
        <div class="flex flex-col">
          <span class="text-muted text-xs tracking-widest uppercase mb-1">Sikka, Flores</span>
          <h3 class="text-lg text-ink group-hover:text-gold-lt transition-colors mb-2">Sikka Silk Sarong</h3>
          <p class="text-gold font-medium tracking-wide">IDR 15,000,000</p>
        </div>
      </div>

      <!-- Product Item 4 -->
      <div class="group cursor-pointer">
        <div class="relative overflow-hidden bg-surface aspect-4/5 mb-5">
          <img
            src="https://images.unsplash.com/photo-1600889240409-eb5b7960fc5a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
            alt="Lurik Weave"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100 grayscale hover:grayscale-0">
          <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
            <button class="w-full bg-gold hover:bg-gold-lt text-bg font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
          </div>
        </div>
        <div class="flex flex-col">
          <span class="text-muted text-xs tracking-widest uppercase mb-1">Baduy, West Java</span>
          <h3 class="text-lg text-ink group-hover:text-gold-lt transition-colors mb-2">Saroja Midnight Weave</h3>
          <p class="text-gold font-medium tracking-wide">IDR 4,500,000</p>
        </div>
      </div>

      <!-- Product Item 5 -->
      <div class="group cursor-pointer">
        <div class="relative overflow-hidden bg-surface aspect-4/5 mb-5">
          <img
            src="https://images.unsplash.com/photo-1596489381665-661cc2e55728?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
            alt="Endek Bali"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100 sepia-[.3]">
          <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
            <button class="w-full bg-gold hover:bg-gold-lt text-bg font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
          </div>
        </div>
        <div class="flex flex-col">
          <span class="text-muted text-xs tracking-widest uppercase mb-1">Klungkung, Bali</span>
          <h3 class="text-lg text-ink group-hover:text-gold-lt transition-colors mb-2">Endek Surya Amber</h3>
          <p class="text-gold font-medium tracking-wide">IDR 6,800,000</p>
        </div>
      </div>

      <!-- Product Item 6 -->
      <div class="group cursor-pointer">
        <div class="relative overflow-hidden bg-surface aspect-4/5 mb-5">
          <img
            src="https://images.unsplash.com/photo-1629198688000-71f23e745b6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
            alt="Songket Palembang"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
          <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
            <button class="w-full bg-gold hover:bg-gold-lt text-bg font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
          </div>
        </div>
        <div class="flex flex-col">
          <span class="text-muted text-xs tracking-widest uppercase mb-1">Palembang, South Sumatra</span>
          <h3 class="text-lg text-ink group-hover:text-gold-lt transition-colors mb-2">Songket Limar Gold</h3>
          <p class="text-gold font-medium tracking-wide">IDR 22,000,000</p>
        </div>
      </div>

    </div>

    <!-- Pagination -->
    <div class="mt-20 flex justify-center">
      <nav class="flex items-center gap-2">
        <button class="w-10 h-10 flex items-center justify-center text-muted hover:text-gold-lt transition-colors cursor-not-allowed" disabled>
          <i data-feather="chevron-left" class="w-4 h-4"></i>
        </button>
        <button class="w-10 h-10 flex items-center justify-center rounded-full bg-gold text-bg font-medium text-sm">1</button>
        <button class="w-10 h-10 flex items-center justify-center rounded-full text-muted hover:text-gold-lt hover:bg-surface font-medium text-sm transition-colors">2</button>
        <button class="w-10 h-10 flex items-center justify-center rounded-full text-muted hover:text-gold-lt hover:bg-surface font-medium text-sm transition-colors">3</button>
        <button class="w-10 h-10 flex items-center justify-center text-muted hover:text-gold-lt transition-colors">
          <i data-feather="chevron-right" class="w-4 h-4"></i>
        </button>
      </nav>
    </div>

  </main>

  <x-footer />

</body>
</html>
