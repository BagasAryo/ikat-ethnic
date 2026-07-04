@extends('layouts.admin')

@section('title', 'Kategori')
@section('breadcrumb', 'Kategori')

@section('content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Kategori</h1>
      <p class="text-muted text-sm mt-0.5">Kelola kategori product</p>
    </div>
    <a href="{{ route('admin.categories.create') }}"
      class="flex items-center gap-2 bg-gold hover:bg-gold-lt text-bg text-sm font-medium px-4 py-2 rounded-sm transition-colors">
      <i data-feather="plus" class="w-4 h-4"></i> 
      <span class="hidden sm:inline">Tambah Kategori</span>
    </a>
  </div>

  @if ($categories->count() > 0)
    <div class="bg-surface border border-white/5 rounded-sm">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-surface2 border-b border-white/5 text-xs uppercase tracking-wider text-faint">
            {{-- <th class="px-6 py-4 font-medium w-16">No</th> --}}
            <th class="px-6 py-4 font-medium">Nama Kategori</th>
            <th class="px-6 py-4 font-medium text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @foreach ($categories as $category)
            <tr class="hover:bg-white/2 transition-colors group">
              {{-- <td class="px-6 py-4 text-sm text-muted">{{ $loop->iteration }}</td> --}}
              <td class="px-6 py-4 text-sm text-ink font-medium">{{ $category->name }}</td>
              <td class="px-6 py-4 text-sm">
                <!-- Desktop Action -->
                <div class="hidden sm:flex items-center justify-end gap-4">
                  <a href="{{ route('admin.categories.edit', $category->id) }}"
                    class="text-gold/70 hover:text-gold transition-colors" title="Edit Kategori">
                    <i data-feather="edit" class="w-4 h-4"></i>
                  </a>
                  <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                    class="text-danger/70 hover:text-danger transition-colors" title="Hapus Kategori"
                    onsubmit="confirmDelete(event, 'Kategori', '{{ $category->name }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center justify-center cursor-pointer">
                      <i data-feather="trash-2" class="w-4 h-4"></i>
                    </button>
                  </form>
                </div>
                
                <!-- Mobile Action -->
                <div class="sm:hidden flex justify-end relative dropdown-container">
                  <button type="button" class="text-muted hover:text-ink transition-colors p-1 cursor-pointer" onclick="toggleDropdown(this)">
                    <i data-feather="more-vertical" class="w-4 h-4"></i>
                  </button>
                  <div class="dropdown-menu hidden absolute right-0 top-full mt-1 w-32 bg-surface2 border border-white/5 rounded-sm shadow-lg z-50 overflow-hidden">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-ink hover:bg-white/5 transition-colors">
                      <i data-feather="edit" class="w-3.5 h-3.5 text-gold/70"></i> Edit
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="confirmDelete(event, 'Kategori', '{{ $category->name }}')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-xs text-ink hover:bg-white/5 transition-colors cursor-pointer">
                        <i data-feather="trash-2" class="w-3.5 h-3.5 text-danger/70"></i> Hapus
                      </button>
                    </form>
                  </div>
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
      <p class="text-muted text-sm">Anda belum menambahkan kategori product apapun.</p>
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
