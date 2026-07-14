@extends('layouts.app')

@section('title', 'Produk | Ikat Ethnic')

@section('content')
  <!-- Page Header -->
  <header class="pt-32 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full text-center">
    <h1 class="font-body text-4xl md:text-5xl font-medium text-ink mb-4">Koleksi Kami</h1>
    <p class="text-muted text-sm max-w-2xl mx-auto font-light leading-relaxed">
      Jelajahi koleksi kain tenun buatan tangan dari berbagai motif nusantara.
    </p>
  </header>

  <!-- Main Content: Product Catalog -->
  <main id="catalog" class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-20">

    <!-- Mobile Filter Toggle & Summary -->
    <div class="lg:hidden flex items-center justify-between mb-8 pb-4 border-b border-surface2">
      <button type="button" id="mobile-filter-toggle"
        class="flex items-center gap-2 px-4 py-2.5 bg-surface border border-surface2 hover:border-gold/50 text-sm text-ink rounded-sm transition-colors">
        <i data-feather="sliders" class="w-4 h-4 text-gold"></i>
        <span>Filter & Cari</span>
      </button>
      <span class="text-xs text-muted">
        {{ $products->total() }} produk ditemukan
      </span>
    </div>

    <!-- Product Grid & Sidebar Layout -->
    <div class="flex flex-col lg:flex-row gap-12 items-start">

      <!-- Sidebar: Filter and Search -->
      <aside id="filter-sidebar" class="w-full lg:w-72 shrink-0 hidden lg:block">
        <form id="filter-form" action="{{ route('products') }}" method="GET"
          class="sticky top-28 bg-surface border border-surface2 p-6 rounded-sm shadow-sm">

          <!-- Text Search -->
          <div class="mb-8">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-muted mb-4 flex items-center gap-2">
              <span>Cari</span>
            </h3>
            <div class="relative group">
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari koleksi..."
                class="w-full bg-bg border border-surface2 focus:border-ink text-ink text-sm px-4 py-2.5 pl-10 rounded-sm outline-none transition-colors placeholder:text-muted">
              <i data-feather="search"
                class="w-4 h-4 text-muted absolute left-3 top-1/2 transform -translate-y-1/2 group-focus-within:text-gold transition-colors"></i>
            </div>
          </div>

          <!-- Categories Checkbox Filter -->
          <div class="mb-8">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-muted mb-4">Kategori</h3>
            <div class="flex flex-col gap-3">
              @foreach ($categories as $category)
                @if ($category->products_count > 0 || in_array($category->id, (array) request('categories', [])))
                  <label
                    class="flex items-center gap-3 cursor-pointer group text-sm text-muted hover:text-ink transition-colors">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                      {{ in_array($category->id, (array) request('categories', [])) ? 'checked' : '' }}
                      class="accent-gold w-4 h-4 rounded-sm border-surface2 bg-surface text-ink focus:ring-0 focus:ring-offset-0">
                    <span class="font-light">{{ $category->name }}</span>
                    <span class="text-xs text-muted ml-auto">({{ $category->products_count }})</span>
                  </label>
                @endif
              @endforeach
            </div>
          </div>

          <!-- Price Range Filter -->
          <div class="mb-8">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-muted mb-4">Rentang Harga (IDR)</h3>
            <div class="flex items-center gap-3">
              <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                class="w-1/2 bg-bg border border-surface2 focus:border-gold text-ink text-sm px-3 py-2 rounded-sm outline-none transition-colors placeholder:text-muted min-w-0">
              <span class="text-muted text-xs">—</span>
              <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Maks"
                class="w-1/2 bg-bg border border-surface2 focus:border-gold text-ink text-sm px-3 py-2 rounded-sm outline-none transition-colors placeholder:text-muted min-w-0">
            </div>
          </div>

          <!-- Sort Filter -->
          <div class="mb-8">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-muted mb-4">Urutkan Berdasarkan</h3>
            <div class="relative">
              <select name="sort"
                class="w-full bg-bg border border-surface2 focus:border-gold text-ink text-sm px-3 py-2.5 pr-8 rounded-sm outline-none transition-colors appearance-none cursor-pointer">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi
                </option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah
                </option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-muted">
                <i data-feather="chevron-down" class="w-4 h-4"></i>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-row sm:flex-col gap-3">
            <button type="submit"
              class="w-full bg-gold hover:bg-gold-lt text-white font-semibold text-xs py-3 uppercase tracking-wider transition-colors rounded-sm cursor-pointer">
              Terapkan Filter
            </button>
            @if (request()->anyFilled(['search', 'categories', 'min_price', 'max_price', 'sort']))
              <a href="{{ route('products') }}"
                class="w-full bg-surface hover:bg-surface2 border border-surface2 text-center text-ink font-semibold text-xs py-3 uppercase tracking-wider transition-colors rounded-sm flex items-center justify-center">
                Hapus Filter
              </a>
            @endif
          </div>
        </form>
      </aside>

      <!-- Right Column: Product Grid -->
      <div class="flex-1 w-full">
        <!-- Desktop Header Summary -->
        <div class="hidden lg:flex items-center justify-between mb-8 pb-4 border-b border-black/10">
          <span class="text-xs text-muted tracking-wide">
            Menampilkan <span class="text-ink font-medium">{{ $products->firstItem() ?? 0 }}</span>–<span
              class="text-ink font-medium">{{ $products->lastItem() ?? 0 }}</span> dari <span
              class="text-ink font-medium">{{ $products->total() }}</span> produk
          </span>
          @if (request()->anyFilled(['search', 'categories', 'min_price', 'max_price']))
            <a href="{{ route('products') }}"
              class="text-xs text-gold hover:text-gold-lt flex items-center gap-1 transition-colors">
              <i data-feather="x" class="w-3 h-3"></i> Hapus semua filter aktif
            </a>
          @endif
        </div>

        @if ($products->isEmpty())
          <!-- Empty State -->
          <div class="text-center py-24 bg-surface/20 border border-dashed border-surface2 rounded-sm px-4">
            <i data-feather="info" class="w-12 h-12 text-muted mx-auto mb-4"></i>
            <h3 class="text-xl text-ink font-medium mb-2">Produk tidak ditemukan</h3>
            <p class="text-muted text-sm max-w-sm mx-auto mb-8 font-light leading-relaxed">
              Kami tidak dapat menemukan produk yang sesuai dengan kriteria Anda. Coba sesuaikan kata kunci pencarian atau hapus filter.
            </p>
            <a href="{{ route('products') }}"
              class="inline-block bg-gold hover:bg-gold-lt text-white text-xs font-semibold tracking-wider uppercase px-6 py-3 transition-colors rounded-sm">
              Lihat Semua Produk
            </a>
          </div>
        @else
          <!-- Product Grid (3 Columns) -->
          <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-x-8 sm:gap-y-16">
            @foreach ($products as $product)
              <div
                class="group cursor-pointer bg-surface border border-black/8 hover:border-gold/30 hover:shadow-md p-3 rounded-sm transition-all duration-200">
                <a href="{{ route('product.show', $product->slug) }}" class="group block">
                  <div class="relative overflow-hidden bg-surface2 aspect-4/5 mb-5 border border-black/5">
                    @if ($product->images?->first()?->image_url)
                      <img src="{{ asset('storage/' . $product->images->first()->image_url) }}"
                        alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700">
                    @else
                      <div class="w-full h-full flex items-center justify-center">
                        <i data-feather="image" class="w-10 h-10 text-faint"></i>
                      </div>
                    @endif
                  </div>
                  <div class="flex flex-col">
                    <span
                      class="text-muted text-xs tracking-widest uppercase mb-1">{{ $product->category?->name ?? 'Tanpa Kategori' }}</span>
                    <h3 class="text-base font-medium text-muted group-hover:text-ink transition-colors mb-1">
                      {{ ucwords($product->name) }}</h3>
                    <p class="text-ink/90 font-semibold tracking-wide text-sm">
                      Rp{{ number_format($product->price, 0, ',', '.') }}
                    </p>
                  </div>
                </a>
              </div>
            @endforeach
          </div>

          <!-- Dynamic Pagination -->
          <div class="mt-20 flex justify-center">
            {{ $products->onEachSide(1)->links() }}
          </div>
        @endif
      </div>
    </div>
  </main>
@endsection

<!-- Toggle & Auto-submit Script -->
@push('scripts')
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
@endpush
