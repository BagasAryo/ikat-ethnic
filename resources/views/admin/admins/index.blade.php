@extends('layouts.admin')

@section('title', 'Kelola Admin')
@section('breadcrumb', 'Kelola Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-ink">Kelola Admin</h2>
        <p class="text-sm text-faint mt-1">Manajemen akun admin dan superadmin.</p>
    </div>
    <a href="{{ route('admin.admins.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gold text-bg text-sm font-medium rounded-sm hover:bg-gold/90 transition-colors">
        <i data-feather="plus" class="w-4 h-4"></i>
        <span>Tambah Admin</span>
    </a>
</div>

<div class="bg-surface border border-white/5 rounded-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-muted">
            <thead class="text-xs text-faint uppercase bg-surface2 border-b border-white/5">
                <tr>
                    <th scope="col" class="px-6 py-4 font-medium">Nama / Email</th>
                    <th scope="col" class="px-6 py-4 font-medium">Role</th>
                    <th scope="col" class="px-6 py-4 font-medium">Tanggal Dibuat</th>
                    <th scope="col" class="px-6 py-4 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse ($admins as $admin)
                    <tr class="hover:bg-surface2/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-ink font-medium">{{ $admin->name }}</p>
                            <p class="text-xs text-faint mt-0.5">{{ $admin->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($admin->role === 'superadmin')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-xs font-medium bg-gold/10 text-gold border border-gold/20">Superadmin</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-xs font-medium bg-white/5 text-muted border border-white/10">Admin</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs">
                            {{ $admin->created_at->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.admins.edit', $admin->id) }}" class="p-1.5 text-muted hover:text-gold bg-surface2 hover:bg-surface rounded transition-colors" title="Edit Admin">
                                    <i data-feather="edit-2" class="w-4 h-4"></i>
                                </a>
                                @if($admin->id !== auth()->id())
                                <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="inline-block" onsubmit="confirmDelete(event, 'Admin', '{{ $admin->name }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-muted hover:text-danger bg-surface2 hover:bg-surface rounded transition-colors" title="Hapus Admin">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-faint">
                            Belum ada data admin.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
