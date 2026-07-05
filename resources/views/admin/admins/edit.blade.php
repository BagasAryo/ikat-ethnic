@extends('layouts.admin')

@section('title', 'Edit Admin')
@section('breadcrumb', 'Edit Admin')

@section('content')
  <div class="max-w-2xl mx-auto">
    <div class="mb-6">
      <a href="{{ route('admin.admins.index') }}"
        class="inline-flex items-center gap-2 text-sm text-muted hover:text-gold transition-colors">
        <i data-feather="arrow-left" class="w-4 h-4"></i>
        <span>Kembali</span>
      </a>
    </div>

    <div class="bg-surface border border-white/5 rounded-sm p-6">
      <h2 class="text-lg font-bold text-ink mb-6">Edit Admin: {{ $admin->name }}</h2>

      <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" class="space-y-5"
        onsubmit="confirmEdit(event, 'Admin')">
        @csrf
        @method('PUT')

        <div>
          <label for="name" class="block text-sm font-medium text-muted mb-1.5">Nama Lengkap <span
              class="text-danger">*</span></label>
          <input type="text" id="name" name="name" value="{{ old('name', $admin->name) }}" required
            class="w-full bg-surface2 border border-white/10 rounded-sm px-4 py-2.5 text-ink focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold transition-colors placeholder:text-faint"
            placeholder="Masukkan nama lengkap">
          @error('name')
            <p class="text-danger text-xs mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-muted mb-1.5">Email <span
              class="text-danger">*</span></label>
          <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" required
            class="w-full bg-surface2 border border-white/10 rounded-sm px-4 py-2.5 text-ink focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold transition-colors placeholder:text-faint"
            placeholder="Masukkan alamat email">
          @error('email')
            <p class="text-danger text-xs mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-muted mb-1.5">Password <span
              class="text-faint font-normal">(Kosongkan jika tidak ingin mengubah)</span></label>
          <input type="password" id="password" name="password"
            class="w-full bg-surface2 border border-white/10 rounded-sm px-4 py-2.5 text-ink focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold transition-colors placeholder:text-faint"
            placeholder="Minimal 8 karakter">
          @error('password')
            <p class="text-danger text-xs mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        <div class="pt-4 border-t border-white/5 flex items-center justify-end gap-3">
          <a href="{{ route('admin.admins.index') }}"
            class="px-4 py-2 text-sm font-medium text-muted hover:text-ink transition-colors">Batal</a>
          <button type="submit"
            class="px-6 py-2 bg-gold text-bg text-sm font-medium rounded-sm hover:bg-gold/90 transition-colors">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
