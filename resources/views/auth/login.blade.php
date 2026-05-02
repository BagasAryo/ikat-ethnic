@extends('layouts.auth')

@section('title', 'Login | Tenun Heritage')

{{-- Left Panel --}}
@section('panel-image', 'https://images.unsplash.com/photo-1600889240409-eb5b7960fc5a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')
@section('panel-image-alt', 'Heritage Weave')
@section('panel-label', 'Est. 1924')
@section('panel-heading', "Woven Legacy,<br>Modern Luxury.")
@section('panel-subtext', "Step into the digital sanctuary of Indonesia's finest hand-woven masterpieces.")

{{-- Right Panel Header --}}
@section('form-title', 'Welcome Back')
@section('form-subtitle', 'Please enter your details to access the exclusive collection gallery.')

{{-- Form --}}
@section('form')
<form action="{{ route('login') }}" method="POST" class="space-y-6">
    @csrf

    {{-- Email --}}
    <div class="space-y-2">
        <label for="email" class="block text-[10px] font-bold tracking-widest text-ink uppercase">
            Email Address
        </label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               class="w-full bg-surface border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 outline-none transition-colors placeholder:text-muted/40 bg-transparent"
               placeholder="artisan@tenunheritage.com"
               required autofocus>
        @error('email')
            <p class="text-danger text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password --}}
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <label for="password" class="block text-[10px] font-bold tracking-widest text-ink uppercase">
                Password
            </label>
            <a href="#" class="text-[10px] font-bold tracking-wider text-gold hover:text-gold-lt uppercase transition-colors">
                Forgot Password?
            </a>
        </div>
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
            <span>Masuk ke Galeri</span>
            <i data-feather="arrow-right" class="w-3.5 h-3.5"></i>
        </button>
    </div>
</form>

{{-- Register Link --}}
<div class="mt-8 text-center border-t border-faint/30 pt-8">
    <p class="text-muted text-xs">
        Not a member yet?
        <a href="{{ route('register') }}" class="text-gold hover:text-gold-lt font-semibold transition-colors ml-1">
            Request Invitation
        </a>
    </p>
</div>
@endsection
