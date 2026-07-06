@extends('layouts.auth')

@section('title', 'Daftar | Ikat Ethnic')

@section('form-title', 'Buat Akun')
@section('form-subtitle', 'Buat akun Anda untuk mulai bersama kami')

@section('form')
  <form action="{{ route('register') }}" method="POST" class="space-y-2">
    @csrf

    <div class="space-y-1">
      <label for="name" class="block text-xs font-bold tracking-widest text-ink">
        Nama Lengkap
      </label>
      <input type="text" id="name" name="name" value="{{ old('name') }}"
        class="w-full bg-transparent border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 outline-none transition-colors placeholder:text-muted/40 tracking-wider"
        placeholder="Masukkan nama lengkap Anda" required autofocus>
      @error('name')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div class="space-y-1">
      <label for="email" class="block text-xs font-bold tracking-widest text-ink">
        Email
      </label>
      <input type="email" id="email" name="email" value="{{ old('email') }}"
        class="w-full bg-transparent border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 outline-none transition-colors placeholder:text-muted/40 tracking-wider"
        placeholder="Masukkan email Anda" required>
      @error('email')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div class="space-y-1">
      <label for="password" class="block text-xs font-bold tracking-widest text-ink">
        Kata Sandi
      </label>
      <div class="relative">
        <input type="password" id="password" name="password"
          class="w-full bg-transparent border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 pr-8 outline-none transition-colors placeholder:text-muted/40 tracking-wider" placeholder="••••••••" required>
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
    <div class="pt-4">
      <button type="submit"
        class="w-full bg-gold hover:bg-gold-lt text-white text-xs font-bold tracking-wider py-3 rounded transition-colors flex items-center justify-center gap-2">
        <span>Buat Akun Baru</span>
      </button>
    </div>
  </form>

  {{-- Login Link --}}
  <div class="mt-2 text-center border-t border-faint/30 pt-4">
    <p class="text-muted text-xs">
      Sudah punya akun?
      <a href="{{ route('login') }}" class="text-gold hover:text-gold-lt font-semibold transition-colors ml-1">
        Masuk di sini
      </a>
    </p>
  </div>
@endsection
