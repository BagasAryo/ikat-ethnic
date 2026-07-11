@extends('layouts.admin')

@section('title', 'Product')
@section('breadcrumb', 'Product')

@section('content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Product</h1>
      <p class="text-muted text-sm mt-0.5">Kelola seluruh product tenun</p>
    </div>
    <a href="{{ route('admin.products.create') }}"
      class="flex items-center gap-2 bg-gold hover:bg-gold-lt text-white text-sm font-medium px-4 py-2 rounded-sm transition-colors">
      <i data-feather="plus" class="w-4 h-4"></i>
      <span class="hidden sm:inline">Tambah Product</span>
    </a>
  </div>

  @if ($products->count() > 0)

    {{-- ── Mobile: Card List ── --}}
    <div class="sm:hidden space-y-3">
      @foreach ($products as $product)
        <div class="bg-surface border border-black/10 rounded-sm p-4 flex items-center gap-3">
          {{-- Thumbnail --}}
          @if ($product->images->count() > 0)
            <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}"
              class="w-14 h-14 object-cover rounded-sm border border-black/10 shrink-0">
          @else
            <div class="w-14 h-14 bg-black/5 rounded-sm flex items-center justify-center border border-black/10 shrink-0">
              <i data-feather="image" class="w-5 h-5 text-ink"></i>
            </div>
          @endif

          {{-- Info --}}
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-ink truncate">{{ $product->name }}</p>
            <p class="text-xs text-muted mt-0.5">{{ $product->category->name }}</p>
            <div class="flex items-center gap-3 mt-1.5">
              <span class="text-xs text-muted font-medium">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
              <span class="text-xs text-muted">Stok: @if ($product->sizes->contains(fn($size) => $size->stock <= 5))
                  <div class="inline-flex items-center text-warn font-bold">
                    {{ $product->sizes->sum('stock') }}
                  </div>
                @else
                  <span>{{ $product->sizes->sum('stock') }}</span>
                @endif
              </span>
            </div>
          </div>

          <!-- Mobile Action -->
          <div class="sm:hidden flex justify-end relative dropdown-container">
            <button type="button" class="text-muted hover:text-ink transition-colors p-1 cursor-pointer"
              onclick="toggleDropdown(this)">
              <i data-feather="more-vertical" class="w-4 h-4"></i>
            </button>
            <div
              class="dropdown-menu hidden absolute right-0 top-full mt-1 w-32 bg-surface2 border border-black/10 rounded-sm shadow-lg z-50 overflow-hidden">
              <a href="{{ route('admin.products.edit', $product->id) }}"
                class="flex items-center gap-2 px-4 py-2.5 text-xs text-ink hover:bg-black/5 transition-colors">
                <i data-feather="edit" class="w-3.5 h-3.5 text-gold/70"></i> Edit
              </a>
              <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                onsubmit="confirmDelete(event, 'Product', '{{ $product->name }}')">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-xs text-ink hover:bg-black/5 transition-colors cursor-pointer">
                  <i data-feather="trash-2" class="w-3.5 h-3.5 text-danger/70"></i> Hapus
                </button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- ── Desktop: Table ── --}}
    <div class="hidden sm:block bg-surface border border-black/10 rounded-sm text-sm">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="border-b border-black/10 text-left">
            <th class="px-4 py-3 font-medium text-ink">No</th>
            <th class="px-4 py-3 font-medium text-ink">Gambar</th>
            <th class="px-4 py-3 font-medium text-ink">Nama Product</th>
            <th class="px-4 py-3 font-medium text-ink">Harga</th>
            <th class="px-4 py-3 font-medium text-ink">Stok</th>
            <th class="px-4 py-3 font-medium text-ink">Kategori</th>
            <th class="px-4 py-3 font-medium text-ink">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @foreach ($products as $product)
            <tr class="border-b border-black/10 hover:bg-surface2/50 transition-colors duration-150">
              <td class="px-4 py-3 text-muted">
                {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
              <td class="px-4 py-3">
                @if ($product->images->count() > 0)
                  <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}"
                    class="w-12 h-12 object-cover rounded-sm border border-black/10">
                @else
                  <div class="w-12 h-12 bg-black/5 rounded-sm flex items-center justify-center border border-black/10">
                    <i data-feather="image" class="w-5 h-5 text-ink"></i>
                  </div>
                @endif
              </td>
              <td class="px-4 py-3 font-medium text-muted">{{ $product->name }}</td>
              <td class="px-4 py-3 text-muted">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
              <td class="px-4 py-3 text-muted">
                @if ($product->sizes->contains(fn($size) => $size->stock <= 5))
                  <div class="inline-flex items-center text-warn font-bold">
                    {{ $product->sizes->sum('stock') }}
                  </div>
                @else
                  <span>{{ $product->sizes->sum('stock') }}</span>
                @endif
              </td>
              <td class="px-4 py-3 text-muted">{{ $product->category->name }}</td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-4">
                  <a href="{{ route('admin.products.edit', $product->id) }}"
                    class="text-gold/70 hover:text-gold transition-colors" title="Edit Product">
                    <i data-feather="edit" class="w-4 h-4"></i>
                  </a>
                  <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                    class="text-danger/70 hover:text-danger transition-colors"
                    onsubmit="confirmDelete(event, 'Product', '{{ $product->name }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center justify-center cursor-pointer">
                      <i data-feather="trash-2" class="w-4 h-4"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $products->onEachSide(1)->links() }}
    </div>
  @else
    <div class="bg-surface border border-black/10 rounded-sm p-16 text-center">
      <i data-feather="package" class="w-10 h-10 text-faint mx-auto mb-4"></i>
      <p class="text-muted text-sm">Belum ada product yang ditambahkan.</p>
    </div>
  @endif
@endsection

@push('scripts')
  <script>
    function toggleDropdown(btn) {
      const allMenus = document.querySelectorAll('.dropdown-menu');
      const menu = btn.nextElementSibling;

      // Close other menus
      allMenus.forEach(m => {
        if (m !== menu) m.classList.add('hidden');
      });

      // Toggle current menu
      menu.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.dropdown-container')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
          menu.classList.add('hidden');
        });
      }
    });
  </script>
@endpush
