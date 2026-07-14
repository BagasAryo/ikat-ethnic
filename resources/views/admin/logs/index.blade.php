@extends('layouts.admin')

@section('title', 'Log Aktivitas Admin')
@section('breadcrumb', 'Log Aktivitas')

@section('content')
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-ink">Log Aktivitas</h2>
      <p class="text-sm text-faint mt-1">Riwayat tindakan yang dilakukan oleh admin.</p>
    </div>
  </div>

  <div class="bg-surface border border-black/10 rounded-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm text-muted">
        <thead class="text-xs text-faint uppercase bg-surface2 border-b border-black/10">
          <tr>
            <th scope="col" class="px-6 py-4 font-medium">Waktu</th>
            <th scope="col" class="px-6 py-4 font-medium">Admin</th>
            <th scope="col" class="px-6 py-4 font-medium">Aksi</th>
            <th scope="col" class="px-6 py-4 font-medium">Deskripsi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse ($logs as $log)
            <tr class="hover:bg-surface2/50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <p class="text-ink font-medium">{{ $log->created_at->translatedFormat('d M Y') }}</p>
                <p class="text-xs text-faint mt-0.5">{{ $log->created_at->format('H:i') }}</p>
              </td>
              <td class="px-6 py-4">
                @if ($log->user)
                  <div class="flex items-center gap-2">
                    <div
                      class="w-6 h-6 rounded-full bg-surface2 border border-black/10 flex items-center justify-center shrink-0">
                      <i data-feather="user" class="w-3 h-3 text-muted"></i>
                    </div>
                    <div>
                      <p class="text-ink text-sm font-medium">{{ $log->user->name }}</p>
                      <p class="text-xs text-faint">{{ $log->user->email }}</p>
                    </div>
                  </div>
                @else
                  <span class="text-faint italic">User terhapus</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @php
                  $badgeClass = 'bg-black/5 text-muted border-black/10';
                  if (str_contains($log->action, 'CREATE')) {
                      $badgeClass = 'bg-emerald-500/10 text-emerald-600 border-emerald-500/50';
                  } elseif (str_contains($log->action, 'UPDATE') || str_contains($log->action, 'EDIT')) {
                      $badgeClass = 'bg-blue-500/10 text-blue-600 border-blue-500/50';
                  } elseif (str_contains($log->action, 'DELETE')) {
                      $badgeClass = 'bg-rose-500/10 text-rose-600 border-rose-500/50';
                  }
                @endphp
                <span
                  class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-wider border {{ $badgeClass }}">
                  {{ str_replace('_', ' ', $log->action) }}
                </span>
              </td>
              <td class="px-6 py-4 min-w-[200px]">
                {{ $log->description }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-8 text-center text-faint">
                Belum ada log aktivitas.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($logs->hasPages())
      <div class="px-6 py-4 border-t border-black/10">
        {{ $logs->onEachSide(1)->links() }}
      </div>
    @endif
  </div>
@endsection
