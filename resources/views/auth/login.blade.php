@extends('layouts.auth')

@section('title', 'Login | Ikat Ethnic')


{{-- Right Panel Header --}}
@section('form-title', 'Selamat Datang Kembali')
@section('form-subtitle', 'Silakan masuk untuk mengakses akun Anda.')

{{-- Form --}}
@section('form')
  <form action="{{ route('login') }}" method="POST" class="space-y-2">
    @csrf

    {{-- Email --}}
    <div class="space-y-1">
      <label for="email" class="block text-xs font-bold tracking-widest text-ink">
        Alamat Email
      </label>
      <input type="email" id="email" name="email" value="{{ old('email') }}"
        class="w-full border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 outline-none transition-colors placeholder:text-muted/40 tracking-widest"
        placeholder="Masukkan email Anda" required autofocus>
      @error('email')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Password --}}
    <div class="space-y-1">
      <label for="password" class="block text-xs font-bold tracking-widest text-ink">
        Kata Sandi
      </label>
      {{-- Password --}}
      <div class="relative">
        <input type="password" id="password" name="password"
          class="w-full border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 pr-8 outline-none transition-colors placeholder:text-muted/40 tracking-widest mb-2"
          placeholder="••••••••" required>
        <button type="button" onclick="togglePassword()"
          class="absolute right-0 top-3 text-muted hover:text-ink transition-colors cursor-pointer">
          <i data-feather="eye" id="eye-open" class="w-4 h-4 hidden"></i>
          <i data-feather="eye-off" id="eye-close" class="w-4 h-4"></i>
        </button>
      </div>
      @error('password')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>


    {{-- Submit --}}
    <div class="pt-2">
      <button type="submit"
        class="w-full bg-gold hover:bg-gold-lt text-bg text-xs font-bold tracking-wider py-3 rounded transition-colors flex items-center justify-center gap-2 cursor-pointer">
        <span>Masuk</span>
      </button>
    </div>
  </form>

  {{-- Register Link --}}
  <div class="mt-2 text-center border-t border-faint/30 pt-4">
    <p class="text-muted text-xs">
      Belum punya akun?
      <a href="{{ route('register') }}" class="text-gold hover:text-gold-lt font-semibold transition-colors ml-1">
        Daftar di sini
      </a>
    </p>
  </div>
@endsection
