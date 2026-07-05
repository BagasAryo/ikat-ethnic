@extends('layouts.admin')

@section('title', 'Tambah Admin')
@section('breadcrumb')
  <a href="{{ route('admin.admins.index') }}" class="text-muted hover:text-ink">Admin</a>
  <i data-feather="chevron-right" class="w-3 h-3 mx-1 inline-block"></i>
  <span class="text-ink">Tambah Admin</span>
@endsection

@section('content')
  <div class="max-w-2xl mx-auto">
    <div class="mb-6 flex justify-end">
      <a href="{{ route('admin.admins.index') }}"
        class="inline-flex items-center gap-2 text-sm text-muted hover:text-gold transition-colors">
        <i data-feather="arrow-left" class="w-4 h-4"></i>
        <span>Kembali</span>
      </a>
    </div>

    <div class="bg-surface border border-white/5 rounded-sm p-6">
      <h2 class="text-lg font-bold text-ink mb-6">Tambah Admin Baru</h2>

      <form action="{{ route('admin.admins.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
          <label for="name" class="block text-sm font-medium text-muted mb-1.5">Nama Lengkap <span
              class="text-danger">*</span></label>
          <input type="text" id="name" name="name" value="{{ old('name') }}" required
            class="w-full bg-surface2 border border-white/10 rounded-sm px-4 py-2.5 text-ink focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold transition-colors placeholder:text-faint"
            placeholder="Masukkan nama lengkap">
          @error('name')
            <p class="text-danger text-xs mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-muted mb-1.5">Email <span
              class="text-danger">*</span></label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required
            class="w-full bg-surface2 border border-white/10 rounded-sm px-4 py-2.5 text-ink focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold transition-colors placeholder:text-faint"
            placeholder="Masukkan alamat email">
          @error('email')
            <p class="text-danger text-xs mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-muted mb-1.5">Password <span
              class="text-danger">*</span></label>
          <div class="relative">
            <input type="password" id="password" name="password" required
              class="w-full bg-surface2 border border-white/10 rounded-sm px-4 py-2.5 text-ink focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold transition-colors placeholder:text-faint pr-8"
              placeholder="Minimal 8 karakter">
            <button type="button" onclick="togglePassword()"
              class="absolute right-4 top-1/2 transform -translate-y-1/2 text-muted hover:text-ink transition-colors cursor-pointer">
              <i data-feather="eye" id="eye-open" class="w-4 h-4 hidden"></i>
              <i data-feather="eye-off" id="eye-close" class="w-4 h-4"></i>
            </button>
          </div>
          @error('password')
            <p class="text-danger text-xs mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        <div class="pt-4 border-t border-white/5 flex items-center justify-start gap-3">
          <button type="reset"
            class="px-4 py-2 bg-surface2 hover:bg-white/5 text-sm font-medium text-muted hover:text-ink cursor-pointer transition-colors">Reset</button>
          <button type="submit"
            class="px-6 py-2 bg-gold text-bg text-sm font-medium rounded-sm hover:bg-gold/90 transition-colors">
            Simpan Admin
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const eyeOpen = document.getElementById('eye-open');
      const eyeClosed = document.getElementById('eye-close');

      if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
      } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
      }
    }
  </script>
@endpush
