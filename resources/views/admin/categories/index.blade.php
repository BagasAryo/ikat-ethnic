@extends('layouts.admin')

@section('title', 'Kategori')
@section('breadcrumb', 'Kategori')

@section('content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Kategori</h1>
      <p class="text-muted text-sm mt-0.5">Kelola kategori produk</p>
    </div>
    <button
      class="flex items-center gap-2 bg-gold hover:bg-gold-lt text-bg text-sm font-medium px-4 py-2 rounded-sm transition-colors">
      <i data-feather="plus" class="w-4 h-4"></i> Tambah Kategori
    </button>
  </div>

  @if ( $kategoris->count() > 0 )
    <div class="bg-surface border border-white/5 rounded-sm overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-surface2 border-b border-white/5 text-xs uppercase tracking-wider text-faint">
            <th class="px-6 py-4 font-medium w-16">No</th>
            <th class="px-6 py-4 font-medium">Nama Kategori</th>
            <th class="px-6 py-4 font-medium text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @foreach ( $kategoris as $kategori )
            <tr class="hover:bg-white/2 transition-colors group">
              <td class="px-6 py-4 text-sm text-muted">{{ $loop->iteration }}</td>
              <td class="px-6 py-4 text-sm text-ink font-medium">{{ $kategori->name }}</td>
              <td class="px-6 py-4 text-sm">
                <div class="flex items-center justify-end gap-4">
                  <a href="{{ route('admin.categories.edit', $kategori->id) }}"
                    class="text-gold/70 hover:text-gold transition-colors" title="Edit Kategori">
                    <i data-feather="edit" class="w-4 h-4"></i>
                  </a>
                  <a href="{{ route('admin.categories.destroy', $kategori->id) }}"
                    class="text-danger/70 hover:text-danger transition-colors" title="Hapus Kategori">
                    <i data-feather="trash-2" class="w-4 h-4"></i>
                  </a>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    <div class="bg-surface border border-white/5 rounded-sm p-16 text-center">
      <div class="w-16 h-16 bg-surface2 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/5">
        <i data-feather="tag" class="w-6 h-6 text-faint"></i>
      </div>
      <p class="text-ink font-medium mb-1">Belum Ada Kategori</p>
      <p class="text-muted text-sm">Anda belum menambahkan kategori produk apapun.</p>
    </div>
  @endif
@endsection
