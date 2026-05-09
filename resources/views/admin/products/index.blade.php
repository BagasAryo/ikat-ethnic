@extends('layouts.admin')

@section('title', 'Produk')
@section('breadcrumb', 'Produk')

@section('content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Produk</h1>
      <p class="text-muted text-sm mt-0.5">Kelola seluruh produk tenun</p>
    </div>
    <a href="{{ route('admin.products.create') }}"
      class="flex items-center gap-2 bg-gold hover:bg-gold-lt text-bg text-sm font-medium px-4 py-2 rounded-sm transition-colors">
      <i data-feather="plus" class="w-4 h-4"></i> Tambah Produk
    </a>
  </div>

  @if ($products->count() > 0)
    <div class="bg-surface border border-white/5 rounded-sm text-sm">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="border-b border-white/5 text-left">
            <th class="px-6 py-4 font-medium text-muted">No</th>
            <th class="px-6 py-4 font-medium text-muted">Nama Produk</th>
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
              <td class="px-6 py-4 font-medium">{{ $product->name }}</td>
              <td class="px-6 py-4">{{ $product->price }}</td>
              <td class="px-6 py-4">{{ $product->stock }}</td>
              <td class="px-6 py-4">{{ $product->kategori->name }}</td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                  <a href="{{ route('admin.products.edit', $product->id) }}"
                    class="text-gold/70 hover:text-gold transition-colors" title="Edit Produk">
                    <i data-feather="edit" class="w-4 h-4"></i>
                  </a>
                  <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                    class="text-danger/70 hover:text-danger transition-colors" title="Hapus Produk">
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
      <p class="text-muted text-sm">Halaman produk dalam pengembangan.</p>
    </div>
  @endif
@endsection
