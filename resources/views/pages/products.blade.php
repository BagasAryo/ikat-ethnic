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

<body
  class="bg-bg text-ink font-body antialiased selection:bg-gold selection:text-obsidian-900 flex flex-col min-h-screen">

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

    <!-- Mobile Filter Toggle & Summary -->
    <div class="lg:hidden flex items-center justify-between mb-8 pb-4 border-b border-surface2">
      <button type="button" id="mobile-filter-toggle" class="flex items-center gap-2 px-4 py-2.5 bg-surface border border-surface2 hover:border-gold/50 text-sm text-ink rounded-sm transition-colors">
        <i data-feather="sliders" class="w-4 h-4 text-gold"></i>
        <span>Filter & Search</span>
      </button>
      <span class="text-xs text-muted">
        {{ $products->total() }} pieces found
      </span>
    </div>

    <!-- Product Grid & Sidebar Layout -->
    <div class="flex flex-col lg:flex-row gap-12 items-start">
      
      <!-- Sidebar: Filter and Search -->
      <aside id="filter-sidebar" class="w-full lg:w-72 shrink-0 hidden lg:block">
        <form id="filter-form" action="{{ route('products') }}" method="GET" class="sticky top-28 bg-surface/30 border border-surface2 p-6 rounded-sm backdrop-blur-sm">
          
          <!-- Text Search -->
          <div class="mb-8">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-muted mb-4 flex items-center gap-2">
              <span>Search</span>
            </h3>
            <div class="relative group">
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Search heritage..."
                class="w-full bg-surface border border-surface2 focus:border-gold text-ink text-sm px-4 py-2.5 pl-10 rounded-sm outline-none transition-colors placeholder:text-muted">
              <i data-feather="search"
                class="w-4 h-4 text-muted absolute left-3 top-1/2 transform -translate-y-1/2 group-focus-within:text-gold transition-colors"></i>
            </div>
          </div>

          <!-- Categories Checkbox Filter -->
          <div class="mb-8">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-muted mb-4">Categories</h3>
            <div class="flex flex-col gap-3">
              @foreach ($categories as $category)
                @if ($category->products_count > 0 || in_array($category->id, (array)request('categories', [])))
                  <label class="flex items-center gap-3 cursor-pointer group text-sm text-ink hover:text-gold-lt transition-colors">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                      {{ in_array($category->id, (array)request('categories', [])) ? 'checked' : '' }}
                      class="accent-gold w-4 h-4 rounded-sm border-surface2 bg-surface text-gold focus:ring-0 focus:ring-offset-0">
                    <span class="font-light">{{ $category->name }}</span>
                    <span class="text-xs text-muted ml-auto">({{ $category->products_count }})</span>
                  </label>
                @endif
              @endforeach
            </div>
          </div>

          <!-- Price Range Filter -->
          <div class="mb-8">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-muted mb-4">Price Range (IDR)</h3>
            <div class="flex items-center gap-3">
              <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                class="w-1/2 bg-surface border border-surface2 focus:border-gold text-ink text-sm px-3 py-2 rounded-sm outline-none transition-colors placeholder:text-muted min-w-0">
              <span class="text-muted text-xs">—</span>
              <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                class="w-1/2 bg-surface border border-surface2 focus:border-gold text-ink text-sm px-3 py-2 rounded-sm outline-none transition-colors placeholder:text-muted min-w-0">
            </div>
          </div>

          <!-- Sort Filter -->
          <div class="mb-8">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-muted mb-4">Sort By</h3>
            <div class="relative">
              <select name="sort" class="w-full bg-surface border border-surface2 focus:border-gold text-ink text-sm px-3 py-2.5 pr-8 rounded-sm outline-none transition-colors appearance-none cursor-pointer">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-muted">
                <i data-feather="chevron-down" class="w-4 h-4"></i>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-col gap-3">
            <button type="submit"
              class="w-full bg-gold hover:bg-gold-lt text-bg font-semibold text-xs py-3 uppercase tracking-wider transition-colors rounded-sm cursor-pointer">
              Apply Filters
            </button>
            @if(request()->anyFilled(['search', 'categories', 'min_price', 'max_price', 'sort']))
              <a href="{{ route('products') }}"
                class="w-full bg-surface hover:bg-surface2 border border-surface2 text-center text-ink font-semibold text-xs py-3 uppercase tracking-wider transition-colors rounded-sm flex items-center justify-center">
                Clear Filters
              </a>
            @endif
          </div>
        </form>
      </aside>

      <!-- Right Column: Product Grid -->
      <div class="flex-1 w-full">
        <!-- Desktop Header Summary -->
        <div class="hidden lg:flex items-center justify-between mb-8 pb-4 border-b border-white/5">
          <span class="text-xs text-muted tracking-wide">
            Showing <span class="text-ink font-medium">{{ $products->firstItem() ?? 0 }}</span>–<span class="text-ink font-medium">{{ $products->lastItem() ?? 0 }}</span> of <span class="text-ink font-medium">{{ $products->total() }}</span> masterpieces
          </span>
          @if(request()->anyFilled(['search', 'categories', 'min_price', 'max_price']))
            <a href="{{ route('products') }}" class="text-xs text-gold hover:text-gold-lt flex items-center gap-1 transition-colors">
              <i data-feather="x" class="w-3 h-3"></i> Clear all active filters
            </a>
          @endif
        </div>

        @if($products->isEmpty())
          <!-- Empty State -->
          <div class="text-center py-24 bg-surface/20 border border-dashed border-surface2 rounded-sm px-4">
            <i data-feather="info" class="w-12 h-12 text-muted mx-auto mb-4"></i>
            <h3 class="text-xl text-ink font-medium mb-2">No masterpieces found</h3>
            <p class="text-muted text-sm max-w-sm mx-auto mb-8 font-light leading-relaxed">
              We couldn't find any items matching your selected criteria. Try adjusting your search keyword or clearing the filters.
            </p>
            <a href="{{ route('products') }}" class="inline-block bg-gold hover:bg-gold-lt text-bg text-xs font-semibold tracking-wider uppercase px-6 py-3 transition-colors rounded-sm">
              View All Pieces
            </a>
          </div>
        @else
          <!-- Product Grid (3 Columns) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
            @foreach ($products as $product)
              <a href="{{ route('product.show', $product->slug) }}" class="group block">
                <div class="relative overflow-hidden bg-surface aspect-4/5 mb-5">
                  <img
                    src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_url) : 'https://via.placeholder.com/400x500?text=No+Image' }}"
                    alt="{{ $product->name }}"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
                  <!-- Hover Action -->
                  <div
                    class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                    <button
                      class="w-full bg-gold hover:bg-gold-lt text-bg font-medium text-sm py-3 uppercase tracking-wider">Add to
                      Bag</button>
                  </div>
                </div>
                <div class="flex flex-col">
                  <span
                    class="text-muted text-xs tracking-widest uppercase mb-1">{{ $product->category?->name ?? 'Uncategorized' }}</span>
                  <h3 class="text-lg text-ink group-hover:text-gold-lt transition-colors mb-2">{{ $product->name }}</h3>
                  <p class="text-gold font-medium tracking-wide">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
              </a>
            @endforeach
          </div>

          <!-- Dynamic Pagination -->
          <div class="mt-20 flex justify-center">
            {{ $products->links() }}
          </div>
        @endif
      </div>
    </div>

    <!-- Toggle & Auto-submit Script -->
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        // Mobile sidebar toggle
        const toggleBtn = document.getElementById('mobile-filter-toggle');
        const sidebar = document.getElementById('filter-sidebar');
        if (toggleBtn && sidebar) {
          toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
          });
        }

        // Auto-submit form when category checkbox or sort select changes
        const filterForm = document.getElementById('filter-form');
        if (filterForm) {
          const autoSubmittingElements = filterForm.querySelectorAll('input[type="checkbox"], select');
          autoSubmittingElements.forEach(element => {
            element.addEventListener('change', () => {
              filterForm.submit();
            });
          });
        }
      });
    </script>

  </main>

  <x-footer />

</body>

</html>
