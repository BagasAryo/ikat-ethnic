@extends('layouts.auth')

@section('title', 'Daftar | Tenun Heritage')


{{-- Right Panel Header --}}
@section('form-title', 'Daftar Akun')
@section('form-subtitle', 'Mulai perjalanan Anda di galeri digital kami.')

{{-- Form --}}
@section('form')
<form action="{{ route('register') }}" method="POST" class="space-y-5">
    @csrf

    {{-- Full Name --}}
    <div class="space-y-2">
        <label for="name" class="block text-[10px] font-bold tracking-widest text-ink uppercase">
            Nama Lengkap
        </label>
        <input type="text" id="name" name="name"
               value="{{ old('name') }}"
               class="w-full bg-transparent border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 outline-none transition-colors placeholder:text-muted/40"
               placeholder="Contoh: Raden Wijaya"
               required autofocus>
        @error('name')
            <p class="text-danger text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div class="space-y-2">
        <label for="email" class="block text-[10px] font-bold tracking-widest text-ink uppercase">
            Email
        </label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               class="w-full bg-transparent border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 outline-none transition-colors placeholder:text-muted/40"
               placeholder="nama@heritage.com"
               required>
        @error('email')
            <p class="text-danger text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Phone --}}
    <div class="space-y-2">
        <label for="phone" class="block text-[10px] font-bold tracking-widest text-ink uppercase">
            Nomor Telepon
        </label>
        <input type="tel" id="phone" name="phone"
               value="{{ old('phone') }}"
               class="w-full bg-transparent border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 outline-none transition-colors placeholder:text-muted/40"
               placeholder="+62 812 3456 7890">
        @error('phone')
            <p class="text-danger text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password --}}
    <div class="space-y-2">
        <label for="password" class="block text-[10px] font-bold tracking-widest text-ink uppercase">
            Kata Sandi
        </label>
        <input type="password" id="password" name="password"
               class="w-full bg-transparent border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 outline-none transition-colors placeholder:text-muted/40 tracking-widest"
               placeholder="••••••••"
               required>
        @error('password')
            <p class="text-danger text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Submit --}}
    <div class="pt-4">
        <button type="submit"
                class="w-full bg-gold hover:bg-gold-lt text-bg text-[11px] font-bold tracking-[0.2em] uppercase py-4 transition-colors flex items-center justify-center gap-2">
            <span>Bergabung Sebagai Kolektor</span>
            <i data-feather="user-plus" class="w-3.5 h-3.5"></i>
        </button>
    </div>
</form>

{{-- Login Link --}}
<div class="mt-8 text-center border-t border-faint/30 pt-8">
    <p class="text-muted text-xs">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="text-gold hover:text-gold-lt font-semibold transition-colors ml-1">
            Masuk Galeri
        </a>
    </p>
</div>
@endsection
