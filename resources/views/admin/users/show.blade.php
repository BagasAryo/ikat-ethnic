@extends('layouts.admin')

@section('title', 'Detail User — ' . $user->name)
@section('breadcrumb')
  <a href="{{ route('admin.users.index') }}" class="hover:text-ink transition-colors">User</a>
  <i data-feather="chevron-right" class="w-3 h-3 mx-1 inline-block"></i>
  <span class="text-muted">Detail User</span>
@endsection
@section('meta-description', 'Detail profil dan riwayat order user ' . $user->name)

@section('content')

  {{-- Back + Header --}}
  <div class="flex items-center justify-between gap-4 mb-8">
    <div>
      <h1 class="text-lg lg:text-xl font-semibold text-ink tracking-wide">Detail User</h1>
      <p class="text-muted text-xs lg:text-sm mt-0.5">Profil & riwayat order</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
      class="flex text-xs bg-black/5 hover:bg-black/10 border border-black/10 px-3 py-1.5 rounded-sm transition-all items-center gap-1.5 shrink-0">
      <i data-feather="arrow-left" class="w-3.5 h-3.5"></i>
      <span class="hidden sm:inline">Kembali</span>
    </a>
  </div>

  @php
    $statusMeta = function ($status) {
        $st = strtolower($status);
        if (in_array($st, ['completed', 'selesai'])) {
            return [
                'label' => 'Selesai',
                'class' => 'bg-success/10 text-success border-success/20',
                'dot' => 'bg-success',
            ];
        } elseif (in_array($st, ['processing', 'diproses'])) {
            return ['label' => 'Diproses', 'class' => 'bg-gold/10 text-gold border-gold/20', 'dot' => 'bg-gold'];
        } elseif ($st === 'shipped') {
            return [
                'label' => 'Dikirim',
                'class' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                'dot' => 'bg-blue-400',
            ];
        }
        return ['label' => 'Pending', 'class' => 'bg-danger/10 text-danger border-danger/20', 'dot' => 'bg-danger'];
    };
    $totalSpent = $orders->whereIn('status', ['Processing', 'Shipped', 'Completed'])->sum('total_amount');
  @endphp

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ── Profile Card (1/3) ── --}}
    <div class="xl:col-span-1 space-y-4">

      {{-- Profile Info --}}
      <div class="bg-surface border border-black/10 rounded-sm p-6">
        {{-- Avatar --}}
        <div class="flex flex-col items-center text-center mb-6">
          <div class="w-16 h-16 rounded-full bg-gold/10 border-2 border-gold/30 flex items-center justify-center mb-3">
            <span class="text-gold text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
          </div>
          <h2 class="text-ink font-semibold text-base">{{ $user->name }}</h2>
          <p class="text-faint text-xs mt-0.5">{{ $user->email }}</p>
        </div>

        {{-- Details --}}
        <div class="space-y-4 text-xs">
          <div class="flex items-start gap-3">
            <i data-feather="phone" class="w-3.5 h-3.5 text-gold shrink-0 mt-0.5"></i>
            <div>
              <p class="text-faint text-[10px] uppercase tracking-wider mb-0.5">No. HP</p>
              <p class="text-ink">{{ $user->no_hp ?? '-' }}</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <i data-feather="map-pin" class="w-3.5 h-3.5 text-gold shrink-0 mt-0.5"></i>
            <div>
              <p class="text-faint text-[10px] uppercase tracking-wider mb-0.5">Alamat</p>
              <p class="text-ink leading-relaxed">{{ $user->alamat ?? '-' }}</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <i data-feather="calendar" class="w-3.5 h-3.5 text-gold shrink-0 mt-0.5"></i>
            <div>
              <p class="text-faint text-[10px] uppercase tracking-wider mb-0.5">Bergabung</p>
              <p class="text-ink">{{ $user->created_at->translatedFormat('d F Y') }}</p>
            </div>
          </div>
        </div>
      </div>

      {{-- Stats --}}
      <div class="bg-surface border border-black/10 rounded-sm p-5">
        <p class="text-faint text-[10px] uppercase tracking-[0.15em] font-semibold mb-4">Statistik</p>
        <div class="grid grid-cols-2 gap-3">
          <div class="bg-surface2 rounded-sm p-3 text-center">
            <p class="text-sm font-semibold text-ink">{{ $user->orders_count }}</p>
            <p class="text-faint text-[10px] uppercase tracking-wider mt-0.5">Total Order</p>
          </div>
          <div class="bg-surface2 rounded-sm p-3 text-center">
            <p class="font-semibold text-gold text-md">Rp{{ number_format($totalSpent, 0, ',', '.') }}</p>
            <p class="text-faint text-[10px] uppercase tracking-wider mt-0.5">Total Belanja</p>
          </div>
        </div>
      </div>

    </div>

    {{-- ── Order History (2/3) ── --}}
    <div class="xl:col-span-2">
      <div class="bg-surface border border-black/10 rounded-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-black/10">
          <div>
            <h2 class="text-sm font-semibold text-ink tracking-wide">Riwayat Order</h2>
            <p class="text-faint text-xs mt-0.5">Semua transaksi oleh user ini</p>
          </div>
          <div class="w-7 h-7 rounded-sm bg-gold/10 border border-gold/20 flex items-center justify-center shrink-0">
            <i data-feather="shopping-bag" class="w-3.5 h-3.5 text-gold"></i>
          </div>
        </div>

        {{-- ══════ MOBILE (< md): card list ══════ --}}
        <div class="md:hidden">
          @forelse ($orders as $order)
            @php $meta = $statusMeta($order->status); @endphp
            <div class="px-5 py-4 {{ !$loop->last ? 'border-b border-black/10' : '' }}">
              <div class="flex items-center justify-between gap-2 mb-2">
                <span class="text-gold text-xs font-mono font-medium truncate">{{ $order->order_number }}</span>
                <span
                  class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full border shrink-0 {{ $meta['class'] }}">
                  <span class="w-1.5 h-1.5 rounded-full {{ $meta['dot'] }}"></span>{{ $meta['label'] }}
                </span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-faint text-[10px]">{{ $order->created_at->isoFormat('D MMMM Y') }}</p>
                  <p class="text-ink text-sm font-semibold mt-0.5">
                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('admin.orders.show', $order->id) }}"
                  class="inline-flex items-center gap-1.5 text-[10px] font-medium px-2.5 py-1.5 rounded-sm
                         bg-surface2 border border-black/10 text-muted hover:text-gold hover:border-gold/30 transition-all duration-150 shrink-0">
                  <i data-feather="eye" class="w-3 h-3"></i>
                  Lihat
                </a>
              </div>
            </div>
          @empty
            <div class="px-6 py-12 text-center">
              <i data-feather="shopping-bag" class="w-8 h-8 text-faint mx-auto mb-2"></i>
              <p class="text-faint text-xs">User ini belum memiliki order</p>
            </div>
          @endforelse
        </div>

        {{-- ══════ DESKTOP (>= md): tabel asli ══════ --}}
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-black/10">
                <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Order ID
                </th>
                <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Tanggal
                </th>
                <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Total
                </th>
                <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Status
                </th>
                <th class="text-left px-6 py-3 text-faint text-[10px] font-semibold uppercase tracking-[0.15em]">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
              @forelse ($orders as $order)
                @php $meta = $statusMeta($order->status); @endphp
                <tr class="group hover:bg-surface2/50 transition-colors duration-150">
                  <td class="px-6 py-4">
                    <span class="text-gold text-xs font-mono font-medium">{{ $order->order_number }}</span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="text-faint text-xs">{{ $order->created_at->isoFormat('D MMMM Y') }}</span>
                  </td>
                  <td class="px-6 py-4">
                    <span
                      class="text-ink text-xs font-semibold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                  </td>
                  <td class="px-6 py-4">
                    <span
                      class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full border {{ $meta['class'] }}">
                      <span class="w-1.5 h-1.5 rounded-full {{ $meta['dot'] }}"></span>{{ $meta['label'] }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <a href="{{ route('admin.orders.show', $order->id) }}"
                      class="inline-flex items-center gap-1.5 text-[10px] font-medium px-2.5 py-1.5 rounded-sm
                             bg-surface2 border border-black/10 text-muted hover:text-gold hover:border-gold/30 transition-all duration-150">
                      <i data-feather="eye" class="w-3 h-3"></i>
                      Lihat
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="px-6 py-12 text-center">
                    <i data-feather="shopping-bag" class="w-8 h-8 text-faint mx-auto mb-2"></i>
                    <p class="text-faint text-xs">User ini belum memiliki order</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

@endsection
