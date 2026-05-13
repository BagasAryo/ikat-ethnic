@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('breadcrumb', 'Produk')

@section('content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Edit Produk</h1>
      <p class="text-muted text-sm mt-0.5">Kelola seluruh produk tenun</p>
    </div>
  </div>
  <div class="bg-surface border border-white/5 rounded-sm overflow-hidden">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="px-6 py-6">
        <div class="form-group mb-4">
          <label for="name" class="block text-sm font-medium text-muted mb-2">Nama Produk</label>
          <input type="text" name="name" id="name" value="{{ $product->name }}"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-gold transition-colors">
        </div>
        <div class="form-group mb-4">
          <label for="description" class="block text-sm font-medium text-muted mb-2">Deskripsi</label>
          <textarea name="description" id="description"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-gold transition-colors">{{ $product->description }}</textarea>
        </div>
        <div class="form-group mb-4">
          <label for="price" class="block text-sm font-medium text-muted mb-2">Harga</label>
          <input type="number" name="price" id="price" value="{{ $product->price }}"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-gold transition-colors"
            required>
        </div>
        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-muted mb-2">Ukuran dan Stok</label>
          <div id="sizes-container" class="space-y-3">
            @if ($product->sizes->count() > 0)
              @foreach ($product->sizes as $index => $size)
                <div class="size-row flex items-center gap-3">
                  <input type="text" name="sizes[{{ $index }}][name]" value="{{ $size->name }}"
                    placeholder="Nama Ukuran (M, L, XL)"
                    class="w-1/2 bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors"
                    required>
                  <input type="number" name="sizes[{{ $index }}][stock]" value="{{ $size->stock }}"
                    placeholder="Stok"
                    class="w-1/3 bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors"
                    required min="0">
                  <button type="button" class="remove-size text-danger/70 hover:text-danger p-2 transition-colors"
                    title="Hapus Ukuran">
                    <i data-feather="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              @endforeach
            @else
              <div class="size-row flex items-center gap-3">
                <input type="text" name="sizes[0][name]" placeholder="Nama Ukuran (M, L, XL)"
                  class="w-1/2 bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors"
                  required>
                <input type="number" name="sizes[0][stock]" placeholder="Stok"
                  class="w-1/3 bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors"
                  required min="0">
                <button type="button" class="remove-size text-danger/70 hover:text-danger p-2 transition-colors"
                  title="Hapus Ukuran">
                  <i data-feather="trash-2" class="w-4 h-4"></i>
                </button>
              </div>
            @endif
          </div>
          <button type="button" id="add-size"
            class="mt-3 flex items-center gap-2 text-sm text-gold hover:text-gold-lt transition-colors">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah Ukuran
          </button>
          @error('sizes')
            <span class="text-danger text-sm mt-1 block">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group mb-4">
          <label class="block text-sm font-medium text-muted mb-2">Gambar Produk Saat Ini</label>
          @if ($product->images->count() > 0)
            <div class="flex flex-wrap gap-4 mb-4">
              @foreach ($product->images as $image)
                <div class="relative w-24 h-24 border border-white/10 rounded-sm overflow-hidden bg-white/5">
                  <img src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $product->name }}"
                    class="w-full h-full object-cover">
                  @if ($image->is_thumbnail)
                    <span
                      class="absolute top-1 right-1 bg-gold text-bg text-[10px] font-bold px-1.5 py-0.5 rounded-sm">Cover</span>
                  @endif
                </div>
              @endforeach
            </div>
          @else
            <p class="text-sm text-muted mb-4 italic">Belum ada gambar.</p>
          @endif

          <label for="images" class="block text-sm font-medium text-muted mb-2">Tambah Gambar Baru (Bisa lebih dari
            1)</label>
          <input type="file"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20"
            id="images" name="images[]" multiple accept="image/*">
          <p class="text-xs text-muted mt-2">Gambar yang diunggah akan ditambahkan ke daftar gambar di atas.</p>
          @error('images')
            <span class="text-danger text-sm mt-1 block">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group mb-4">
          <label for="kategori_id" class="block text-sm font-medium text-muted mb-2">Kategori</label>
          <select name="kategori_id" id="kategori_id"
            class="w-full bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-gold transition-colors"
            required>
            @foreach ($kategoris as $kategori)
              <option value="{{ $kategori->id }}" {{ $kategori->id == $product->kategori_id ? 'selected' : '' }}>
                {{ $kategori->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="flex items-center gap-2 px-6 py-4 border-t border-white/5">
        <a href="{{ route('admin.products.index') }}"
          class="bg-surface2 hover:bg-white/5 text-muted text-sm font-medium px-4 py-2.5 rounded-sm transition-colors">
          Kembali
        </a>
        <button type="submit"
          class="bg-gold hover:bg-gold-lt text-bg text-sm font-medium px-4 py-2.5 rounded-sm transition-colors cursor-pointer">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sizesContainer = document.getElementById('sizes-container');
      const addSizeBtn = document.getElementById('add-size');
      let sizeCount = {{ $product->sizes->count() > 0 ? $product->sizes->count() : 1 }};

      addSizeBtn.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'size-row flex items-center gap-3';
        row.innerHTML = `
          <input type="text" name="sizes[${sizeCount}][name]" placeholder="Nama Ukuran (M, L, XL)"
            class="w-1/2 bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors" required>
          <input type="number" name="sizes[${sizeCount}][stock]" placeholder="Stok"
            class="w-1/3 bg-surface2 border border-white/5 rounded-sm px-4 py-2.5 hover:border-gold text-sm text-ink focus:outline-none focus:border-gold transition-colors" required min="0">
          <button type="button" class="remove-size text-danger/70 hover:text-danger p-2 transition-colors" title="Hapus Ukuran">
            <i data-feather="trash-2" class="w-4 h-4"></i>
          </button>
        `;
        sizesContainer.appendChild(row);
        feather.replace();
        sizeCount++;
      });

      sizesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-size')) {
          const rows = sizesContainer.querySelectorAll('.size-row');
          if (rows.length > 1) {
            e.target.closest('.size-row').remove();
          } else {
            alert('Minimal harus ada 1 ukuran!');
          }
        }
      });
    });
  </script>
@endsection
