@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('breadcrumb', 'User')
@section('meta-description', 'Daftar semua user terdaftar di Ikat Ethnic')

@section('content')

  {{-- Page Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
      <h1 class="text-xl font-semibold text-ink tracking-wide">Manajemen User</h1>
      <p class="text-muted text-sm mt-0.5">Kelola semua pengguna yang terdaftar</p>
    </div>
    <div class="flex items-center gap-2 text-xs text-faint bg-surface border border-white/5 rounded-sm px-3 py-2">
      <i data-feather="users" class="w-3.5 h-3.5 text-gold"></i>
      <span>{{ $users->total() }} user terdaftar</span>
    </div>
  </div>

  {{-- Search Bar --}}
  <div class="mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-3">
      <div class="flex items-center gap-2 bg-surface border border-white/5 rounded-sm px-4 py-2.5 flex-1 max-w-sm">
        <i data-feather="search" class="w-4 h-4 text-faint shrink-0"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
          class="bg-transparent text-ink text-sm outline-none placeholder:text-faint w-full">
      </div>
      @if (request('search'))
        <a href="{{ route('admin.users.index') }}"
          class="text-xs text-faint hover:text-muted transition-colors flex items-center gap-1">
          <i data-feather="x" class="w-3.5 h-3.5"></i> Reset
        </a>
      @endif
    </form>
  </div>

  {{-- Table --}}
  <div class="bg-surface border border-white/5 rounded-sm">

    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-white/5">
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">#</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">User</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em] hidden md:table-cell">No. HP</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em] hidden lg:table-cell">Alamat</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Orders</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em] hidden sm:table-cell">Bergabung</th>
            <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse ($users as $index => $user)
            <tr class="group hover:bg-surface2/50 transition-colors duration-150">

              {{-- No --}}
              <td class="px-6 py-4">
                <span class="text-faint text-xs">{{ $users->firstItem() + $index }}</span>
              </td>

              {{-- User Info --}}
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="w-8 h-8 rounded-full bg-gold/10 border border-gold/20 flex items-center justify-center shrink-0">
                    <span class="text-gold text-xs font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                  </div>
                  <div class="min-w-0">
                    <p class="text-ink text-xs font-medium truncate">{{ $user->name }}</p>
                    <p class="text-faint text-[10px] truncate">{{ $user->email }}</p>
                  </div>
                </div>
              </td>

              {{-- No HP --}}
              <td class="px-6 py-4 hidden md:table-cell">
                <span class="text-muted text-xs">{{ $user->no_hp ?? '-' }}</span>
              </td>

              {{-- Alamat --}}
              <td class="px-6 py-4 hidden lg:table-cell">
                <span class="text-muted text-xs max-w-[160px] truncate block">{{ $user->alamat ?? '-' }}</span>
              </td>

              {{-- Orders Count --}}
              <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2.5 py-1 rounded-full
                  {{ $user->orders_count > 0 ? 'bg-gold/10 text-gold border border-gold/20' : 'bg-surface2 text-faint border border-white/10' }}">
                  {{ $user->orders_count }} order
                </span>
              </td>

              {{-- Tanggal Bergabung --}}
              <td class="px-6 py-4 hidden sm:table-cell">
                <span class="text-faint text-xs">{{ $user->created_at->format('d M Y') }}</span>
              </td>

              {{-- Aksi --}}
              <td class="px-6 py-4">
                <a href="{{ route('admin.users.show', $user->id) }}"
                  class="inline-flex items-center gap-1.5 text-[10px] font-medium px-2.5 py-1.5 rounded-sm
                         bg-surface2 border border-white/10 text-muted hover:text-gold hover:border-gold/30 transition-all duration-150">
                  <i data-feather="eye" class="w-3 h-3"></i>
                  Detail
                </a>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-16 text-center">
                <i data-feather="users" class="w-10 h-10 text-faint mx-auto mb-3"></i>
                <p class="text-faint text-sm">Belum ada user terdaftar</p>
                @if (request('search'))
                  <p class="text-faint text-xs mt-1">Tidak ditemukan hasil untuk "<span
                      class="text-muted">{{ request('search') }}</span>"</p>
                @endif
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if ($users->hasPages())
      <div class="px-6 py-4 border-t border-white/5 flex items-center justify-between gap-4 flex-wrap">
        <p class="text-faint text-xs">
          Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user
        </p>
        <div class="flex items-center gap-1">
          {{-- Prev --}}
          @if ($users->onFirstPage())
            <span class="px-3 py-1.5 text-xs text-faint bg-surface2 border border-white/5 rounded-sm cursor-not-allowed opacity-40">
              <i data-feather="chevron-left" class="w-3.5 h-3.5"></i>
            </span>
          @else
            <a href="{{ $users->previousPageUrl() }}"
              class="px-3 py-1.5 text-xs text-muted bg-surface2 border border-white/5 rounded-sm hover:border-gold/30 hover:text-gold transition-all">
              <i data-feather="chevron-left" class="w-3.5 h-3.5"></i>
            </a>
          @endif

          {{-- Pages --}}
          @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
            @if ($page == $users->currentPage())
              <span class="px-3 py-1.5 text-xs font-semibold text-bg bg-gold rounded-sm">{{ $page }}</span>
            @else
              <a href="{{ $url }}"
                class="px-3 py-1.5 text-xs text-muted bg-surface2 border border-white/5 rounded-sm hover:border-gold/30 hover:text-gold transition-all">
                {{ $page }}
              </a>
            @endif
          @endforeach

          {{-- Next --}}
          @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}"
              class="px-3 py-1.5 text-xs text-muted bg-surface2 border border-white/5 rounded-sm hover:border-gold/30 hover:text-gold transition-all">
              <i data-feather="chevron-right" class="w-3.5 h-3.5"></i>
            </a>
          @else
            <span class="px-3 py-1.5 text-xs text-faint bg-surface2 border border-white/5 rounded-sm cursor-not-allowed opacity-40">
              <i data-feather="chevron-right" class="w-3.5 h-3.5"></i>
            </span>
          @endif
        </div>
      </div>
    @endif

  </div>

@endsection
