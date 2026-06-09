@extends('layouts.auth')

@section('title', 'Daftar | Ikat Ethnic')

@section('form-title', 'Create Account')
@section('form-subtitle', 'Create your account to get started with us')

@section('form')
  <form action="{{ route('register') }}" method="POST" class="space-y-2">
    @csrf

    <div class="">
      <label for="name" class="block text-xs font-bold tracking-widest text-ink">
        Full Name
      </label>
      <input type="text" id="name" name="name" value="{{ old('name') }}"
        class="w-full bg-transparent border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 outline-none transition-colors placeholder:text-muted/40 tracking-wider"
        placeholder="Enter your full name" required autofocus>
      @error('name')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div class="">
      <label for="email" class="block text-xs font-bold tracking-widest text-ink">
        Email
      </label>
      <input type="email" id="email" name="email" value="{{ old('email') }}"
        class="w-full bg-transparent border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 outline-none transition-colors placeholder:text-muted/40 tracking-wider"
        placeholder="Enter your email" required>
      @error('email')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div class="">
      <label for="password" class="block text-xs font-bold tracking-widest text-ink">
        Password
      </label>
      <input type="password" id="password" name="password"
        class="w-full bg-transparent border-b border-faint focus:border-gold text-ink text-sm px-0 py-3 outline-none transition-colors placeholder:text-muted/40 tracking-wider" placeholder="••••••••" required>
      @error('password')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Submit --}}
    <div class="pt-4">
      <button type="submit"
        class="w-full bg-gold hover:bg-gold-lt text-bg text-xs font-bold tracking-wider py-3 transition-colors flex items-center justify-center gap-2">
        <span>Create New Account</span>
      </button>
    </div>
  </form>

  {{-- Login Link --}}
  <div class="mt-2 text-center border-t border-faint/30 pt-4">
    <p class="text-muted text-xs">
      Already have an Account?
      <a href="{{ route('login') }}" class="text-gold hover:text-gold-lt font-semibold transition-colors ml-1">
        Login Here
      </a>
    </p>
  </div>
@endsection
