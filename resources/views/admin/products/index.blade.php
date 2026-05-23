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
      class="flex items-center gap-2 bg-gold hover:bg-gold-lt text-bg text-sm font-medium px-4 py-2 rounded-sm transition-colors">
      <i data-feather="plus" class="w-4 h-4"></i> Tambah Product
    </a>
  </div>

  @if ($products->count() > 0)
    <div class="bg-surface border border-white/5 rounded-sm text-sm">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="border-b border-white/5 text-left">
            <th class="px-6 py-4 font-medium text-muted">No</th>
            <th class="px-6 py-4 font-medium text-muted">Gambar</th>
            <th class="px-6 py-4 font-medium text-muted">Nama Product</th>
            <th class="px-6 py-4 font-medium text-muted">Harga</th>
            <th class="px-6 py-4 font-medium text-muted">Stok</th>
            <th class="px-6 py-4 font-medium text-muted">Kategori</th>
            <th class="px-6 py-4 font-medium text-muted">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @foreach ($products as $product)
            <tr class="border-b border-white/5 hover:bg-white/2">
              <td class="px-6 py-4">{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
              <td class="px-6 py-4">
                @if($product->images->count() > 0)
                  <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-sm border border-white/10">
                @else
                  <div class="w-12 h-12 bg-white/5 rounded-sm flex items-center justify-center border border-white/10">
                    <i data-feather="image" class="w-5 h-5 text-faint"></i>
                  </div>
                @endif
              </td>
              <td class="px-6 py-4 font-medium">{{ $product->name }}</td>
              <td class="px-6 py-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
              <td class="px-6 py-4">{{ $product->sizes->sum('stock') }}</td>
              <td class="px-6 py-4">{{ $product->category->name }}</td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                  <a href="{{ route('admin.products.edit', $product->id) }}"
                    class="text-gold/70 hover:text-gold transition-colors" title="Edit Product">
                    <i data-feather="edit" class="w-4 h-4"></i>
                  </a>
                  <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                    class="text-danger/70 hover:text-danger transition-colors" title="Hapus Product" onsubmit="return confirm('Apakah anda yakin ingin menghapus {{ $product->name }}?')">
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
      {{ $products->links() }}
    </div>
  @else
    <div class="bg-surface border border-white/5 rounded-sm p-16 text-center">
      <i data-feather="package" class="w-10 h-10 text-faint mx-auto mb-4"></i>
      <p class="text-muted text-sm">Halaman Product dalam pengembangan.</p>
    </div>
  @endif
@endsection
