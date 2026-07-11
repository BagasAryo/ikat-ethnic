@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('breadcrumb', 'User')
@section('meta-description', 'Daftar semua user terdaftar di Ikat Ethnic')

@section('content')

  {{-- Page Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
      <h1 class="text-lg lg:text-xl font-semibold text-ink tracking-wide">Manajemen User</h1>
      <p class="text-muted text-xs lg:text-sm mt-0.5">Kelola semua pengguna yang terdaftar</p>
    </div>
    <div class="flex items-center gap-2 text-xs text-ink bg-surface border border-black/10 rounded-sm px-3 py-2">
      <i data-feather="users" class="w-3.5 h-3.5 text-ink"></i>
      <span>{{ $users->total() }} user terdaftar</span>
    </div>
  </div>

  {{-- Search Bar --}}
  <div class="mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-3">
      <div class="flex items-center gap-2 bg-surface border border-black/10 rounded-sm px-4 py-2.5 flex-1 sm:max-w-sm">
        <i data-feather="search" class="w-4 h-4 text-faint shrink-0"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
          class="bg-transparent text-ink text-sm outline-none placeholder:text-faint w-full">
      </div>
      @if (request('search'))
        <a href="{{ route('admin.users.index') }}"
          class="text-xs text-muted hover:text-ink transition-colors flex items-center gap-1 shrink-0">
          <i data-feather="x" class="w-3.5 h-3.5"></i>
          <span class="hidden sm:inline">Reset</span>
        </a>
      @endif
    </form>
  </div>

  {{-- ══════════════════════════════════════════════
       MOBILE LAYOUT (< md) — card list
  ══════════════════════════════════════════════ --}}
  <div class="md:hidden space-y-3">
    @forelse ($users as $index => $user)
      <div class="bg-surface border border-black/10 rounded-sm p-4">
        <div class="flex items-start gap-3">
          <div class="w-9 h-9 rounded-full bg-gold/10 border border-gold/20 flex items-center justify-center shrink-0">
            <span class="text-muted text-xs font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-ink text-sm font-medium truncate">{{ $user->name }}</p>
            <p class="text-muted text-xs truncate">{{ $user->email }}</p>
          </div>
          <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2.5 py-1 rounded-full shrink-0
            {{ $user->orders_count > 0 ? 'bg-gold/10 text-muted border border-gold/20' : 'bg-surface2 text-muted border border-black/10' }}">
            {{ $user->orders_count }} order
          </span>
        </div>

        <div class="mt-3 pt-3 border-t border-black/10 grid grid-cols-2 gap-2 text-xs">
          <div>
            <span class="text-faint text-[10px] uppercase tracking-wider block">No. HP</span>
            <span class="text-muted">{{ $user->no_hp ?? '-' }}</span>
          </div>
          <div>
            <span class="text-faint text-[10px] uppercase tracking-wider block">Bergabung</span>
            <span class="text-muted">{{ $user->created_at->isoFormat('D MMM Y') }}</span>
          </div>
          <div class="col-span-2">
            <span class="text-faint text-[10px] uppercase tracking-wider block">Alamat</span>
            <span class="text-muted truncate block">{{ $user->alamat ?? '-' }}</span>
          </div>
        </div>

        <a href="{{ route('admin.users.show', $user->id) }}"
          class="mt-3 inline-flex items-center justify-center gap-1.5 text-xs font-medium px-3 py-2 rounded-sm w-full
                 bg-surface2 border border-black/10 text-muted hover:text-gold hover:border-gold/30 transition-all duration-150">
          <i data-feather="eye" class="w-3.5 h-3.5"></i>
          Lihat Detail
        </a>
      </div>
    @empty
      <div class="bg-surface border border-black/10 rounded-sm px-6 py-16 text-center">
        <i data-feather="users" class="w-10 h-10 text-faint mx-auto mb-3"></i>
        <p class="text-faint text-sm">Belum ada user terdaftar</p>
        @if (request('search'))
          <p class="text-faint text-xs mt-1">Tidak ditemukan hasil untuk "<span class="text-muted">{{ request('search') }}</span>"</p>
        @endif
      </div>
    @endforelse

    {{-- Pagination mobile: simple prev/next --}}
    @if ($users->hasPages())
      <div class="flex items-center justify-between gap-3 pt-2">
        @if ($users->onFirstPage())
          <span class="flex-1 text-center px-3 py-2 text-xs text-faint bg-surface2 border border-black/10 rounded-sm opacity-40">Sebelumnya</span>
        @else
          <a href="{{ $users->previousPageUrl() }}" class="flex-1 text-center px-3 py-2 text-xs text-muted bg-surface2 border border-black/10 rounded-sm hover:border-gold/30 hover:text-gold transition-all">Sebelumnya</a>
        @endif
        <span class="text-xs text-faint shrink-0">{{ $users->currentPage() }} / {{ $users->lastPage() }}</span>
        @if ($users->hasMorePages())
          <a href="{{ $users->nextPageUrl() }}" class="flex-1 text-center px-3 py-2 text-xs text-muted bg-surface2 border border-black/10 rounded-sm hover:border-gold/30 hover:text-gold transition-all">Selanjutnya</a>
        @else
          <span class="flex-1 text-center px-3 py-2 text-xs text-faint bg-surface2 border border-black/10 rounded-sm opacity-40">Selanjutnya</span>
        @endif
      </div>
    @endif
  </div>

  {{-- ══════════════════════════════════════════════
       DESKTOP LAYOUT (>= md) — table asli
  ══════════════════════════════════════════════ --}}
  <div class="hidden md:block bg-surface border border-black/10 rounded-sm">

    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-black/10">
            <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold tracking-[0.15em]">#</th>
            <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold tracking-[0.15em]">User</th>
            <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold tracking-[0.15em]">No. HP</th>
            <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold tracking-[0.15em] hidden lg:table-cell">Alamat</th>
            <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold tracking-[0.15em]">Orders</th>
            <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold tracking-[0.15em]">Bergabung</th>
            <th class="text-left px-6 py-3 text-ink text-[10px] font-semibold tracking-[0.15em]">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse ($users as $index => $user)
            <tr class="group hover:bg-surface2/50 border-b border-black/10 transition-colors duration-150">

              {{-- No --}}
              <td class="px-6 py-4">
                <span class="text-muted text-xs">{{ $users->firstItem() + $index }}</span>
              </td>

              {{-- User Info --}}
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="w-8 h-8 rounded-full bg-gold/10 border border-gold/20 flex items-center justify-center shrink-0">
                    <span class="text-muted text-xs font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                  </div>
                  <div class="min-w-0">
                    <p class="text-ink text-xs font-medium truncate">{{ $user->name }}</p>
                    <p class="text-muted text-[10px] truncate">{{ $user->email }}</p>
                  </div>
                </div>
              </td>

              {{-- No HP --}}
              <td class="px-6 py-4">
                <span class="text-muted text-xs">{{ $user->no_hp ?? '-' }}</span>
              </td>

              {{-- Alamat --}}
              <td class="px-6 py-4 hidden lg:table-cell">
                <span class="text-muted text-xs max-w-[160px] truncate block">{{ $user->alamat ?? '-' }}</span>
              </td>

              {{-- Orders Count --}}
              <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2.5 py-1 rounded-full
                  {{ $user->orders_count > 0 ? 'bg-gold/10 text-muted border border-gold/20' : 'bg-surface2 text-muted border border-black/10' }}">
                  {{ $user->orders_count }} order
                </span>
              </td>

              {{-- Tanggal Bergabung --}}
              <td class="px-6 py-4">
                <span class="text-muted text-xs">{{ $user->created_at->isoFormat('D MMMM Y') }}</span>
              </td>

              {{-- Aksi --}}
              <td class="px-6 py-4">
                <a href="{{ route('admin.users.show', $user->id) }}"
                  class="inline-flex items-center gap-1.5 text-[10px] font-medium px-2.5 py-1.5 rounded-sm
                         bg-surface2 border border-black/10 text-muted hover:text-gold hover:border-gold/30 transition-all duration-150">
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
      <div class="px-6 py-4 border-t border-black/10 flex items-center justify-between gap-4 flex-wrap">
        <p class="text-faint text-xs">
          Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }} user
        </p>
        <div class="flex items-center gap-1">
          {{-- Prev --}}
          @if ($users->onFirstPage())
            <span class="px-3 py-1.5 text-xs text-faint bg-surface2 border border-black/10 rounded-sm cursor-not-allowed opacity-40">
              <i data-feather="chevron-left" class="w-3.5 h-3.5"></i>
            </span>
          @else
            <a href="{{ $users->previousPageUrl() }}"
              class="px-3 py-1.5 text-xs text-muted bg-surface2 border border-black/10 rounded-sm hover:border-gold/30 hover:text-gold transition-all">
              <i data-feather="chevron-left" class="w-3.5 h-3.5"></i>
            </a>
          @endif

          {{-- Pages --}}
          @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
            @if ($page == $users->currentPage())
              <span class="px-3 py-1.5 text-xs font-semibold text-ink bg-gold rounded-sm">{{ $page }}</span>
            @else
              <a href="{{ $url }}"
                class="px-3 py-1.5 text-xs text-muted bg-surface2 border border-black/10 rounded-sm hover:border-gold/30 hover:text-gold transition-all">
                {{ $page }}
              </a>
            @endif
          @endforeach

          {{-- Next --}}
          @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}"
              class="px-3 py-1.5 text-xs text-muted bg-surface2 border border-black/10 rounded-sm hover:border-gold/30 hover:text-gold transition-all">
              <i data-feather="chevron-right" class="w-3.5 h-3.5"></i>
            </a>
          @else
            <span class="px-3 py-1.5 text-xs text-faint bg-surface2 border border-black/10 rounded-sm cursor-not-allowed opacity-40">
              <i data-feather="chevron-right" class="w-3.5 h-3.5"></i>
            </span>
          @endif
        </div>
      </div>
    @endif

  </div>

@endsection