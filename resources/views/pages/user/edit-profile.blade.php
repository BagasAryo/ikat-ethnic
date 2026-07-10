@extends('layouts.app')

@section('title', 'Edit Profile | Ikat Ethnic')

@section('content')
  <!-- Page Header -->
  <header class="pt-32 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full text-center">
    <h1 class="font-body text-3xl md:text-4xl font-medium text-ink">Edit Profile</h1>
    <p class="text-muted text-sm mt-2 font-light">Update Informasi Data Diri</p>
  </header>

  <!-- Content -->
  <main class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Left Sidebar - Profile Summary -->
      <div class="flex flex-col gap-6 border-b border-surface2 pb-8 lg:border-0 lg:pb-0">
        <!-- Profile Card -->
        <div class="bg-surface border border-surface2 p-8 flex flex-col items-center gap-6">
          <!-- Avatar -->
          <div class="w-20 h-20 rounded-full bg-linear-to-br from-gold to-gold-lt flex items-center justify-center">
            <span class="text-xl font-medium text-white uppercase">
              {{ substr($user->name, 0, 1) }}
            </span>
          </div>

          <!-- User Info -->
          <div class="text-center w-full">
            <h2 class="text-xl font-medium text-ink mb-1">{{ $user->name }}</h2>
            <p class="text-muted text-sm break-all">{{ $user->email }}</p>
          </div>

          <!-- Quick Stats -->
          <div class="w-full grid grid-cols-2 gap-4 pt-6 border-t border-surface2">
            <div class="text-center">
              <div class="text-gold text-xl font-medium">{{ Auth::user()->orders->count() }}</div>
              <div class="text-muted text-xs tracking-widest uppercase mt-1">Orders</div>
            </div>
            <div class="text-center">
              <div class="text-ink text-xl font-medium">Bergabung</div>
              <div class="text-muted text-xs tracking-widest uppercase mt-1">Sejak
                {{ $user->created_at->isoFormat('MMMM Y') }}
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-3 lg:flex lg:flex-col gap-2 lg:gap-3">
          <a href="{{ route('profile') }}"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-3 px-2 lg:px-4 py-3 {{ request()->routeIs('profile*') ? 'border-gold text-gold bg-gold/10' : 'bg-surface border border-surface2 hover:border-gold text-ink hover:text-gold' }} transition-colors rounded-sm text-center lg:text-left">
            <i data-feather="user" class="w-4 h-4 shrink-0"></i>
            <span class="text-[11px] lg:text-sm font-medium">Profile</span>
          </a>
          <a href="{{ route('orders') }}"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-3 px-2 lg:px-4 py-3 {{ request()->routeIs('orders*') ? 'border-gold text-gold bg-gold/10' : 'bg-surface border border-surface2 hover:border-gold text-ink hover:text-gold' }} transition-colors rounded-sm text-center lg:text-left">
            <i data-feather="shopping-bag" class="w-4 h-4 shrink-0"></i>
            <span class="text-[11px] lg:text-sm font-medium">Orders</span>
          </a>
          <form action="{{ route('logout') }}" method="POST" class="contents">
            @csrf
            <button type="submit"
              class="w-full flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-3 px-2 lg:px-4 py-3 bg-surface border border-surface2 hover:border-gold text-ink hover:text-gold transition-colors rounded-sm text-center lg:text-left cursor-pointer">
              <i data-feather="log-out" class="w-4 h-4 shrink-0"></i>
              <span class="text-[11px] lg:text-sm font-medium">Logout</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Main Content - Edit Profile Form -->
      <div class="lg:col-span-2 flex flex-col gap-8">
        
        <!-- Back Button -->
        <a href="{{ route('profile') }}" class="inline-flex items-center gap-2 text-xs text-muted hover:text-ink uppercase tracking-wider transition-colors w-fit">
          <i data-feather="arrow-left" class="w-4 h-4"></i>
          Kembali Ke Profil
        </a>

        <!-- Display Session Error Alerts if Any -->
        @if ($errors->any())
          <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-sm text-sm">
            <div class="flex items-center gap-2 font-medium mb-1">
              <i data-feather="alert-circle" class="w-4 h-4"></i>
              <span>Harap perbaiki error di bawah ini:</span>
            </div>
            <ul class="list-disc list-inside text-xs pl-2 space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- Form 1: Personal Details -->
        <div class="bg-surface border border-surface2 p-8">
          <div class="flex items-center gap-3 mb-8 pb-6 border-b border-surface2">
            <i data-feather="user" class="w-5 h-5 text-gold"></i>
            <h3 class="text-lg font-medium text-ink">Informasi Pribadi</h3>
          </div>

          <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name & Email -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <label for="name" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                  class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-ink transition-colors">
                @error('name')
                  <p class="text-red-400 text-xs mt-1.5 font-light">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="email" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Email</label>
                <input type="email" id="email" value="{{ $user->email }}" disabled
                  class="w-full bg-bg/50 border border-surface2 text-muted text-sm px-4 py-3 rounded-sm cursor-not-allowed outline-none">
                <span class="text-[10px] text-muted font-light mt-1.5 block">Email tidak dapat diubah.</span>
              </div>
            </div>

            <!-- Phone Number -->
            <div>
              <label for="no_hp" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Nomor Telepon</label>
              <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="e.g. 081234567890"
                class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-ink transition-colors">
              @error('no_hp')
                <p class="text-red-400 text-xs mt-1.5 font-light">{{ $message }}</p>
              @enderror
            </div>

            <!-- Shipping Address -->
            <div>
              <label for="alamat" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Alamat</label>
              <textarea name="alamat" id="alamat" rows="4" placeholder="Masukkan Alamat Anda"
                class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-ink transition-colors resize-y">{{ old('alamat', $user->alamat) }}</textarea>
              @error('alamat')
                <p class="text-red-400 text-xs mt-1.5 font-light">{{ $message }}</p>
              @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-surface2 flex justify-end gap-4">
              <a href="{{ route('profile') }}"
                class="inline-flex items-center justify-center px-6 py-3 border border-surface2 hover:border-ink hover:text-ink text-muted text-xs font-semibold tracking-wider uppercase transition-colors rounded-sm">
                Batal
              </a>
              <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gold hover:bg-gold-lt text-white text-xs font-semibold tracking-wider uppercase transition-all duration-300 rounded-sm cursor-pointer">
                <i data-feather="save" class="w-4 h-4"></i>
                Simpan Perubahan
              </button>
            </div>

          </form>
        </div>

        <!-- Form 2: Change Password -->
        <div class="bg-surface border border-surface2 p-8">
          <div class="flex items-center gap-3 mb-8 pb-6 border-b border-surface2">
            <i data-feather="lock" class="w-5 h-5 text-gold"></i>
            <h3 class="text-lg font-medium text-ink">Ubah Password</h3>
          </div>

          <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
              <label for="current_password" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Password Saat Ini</label>
              <input type="password" name="current_password" id="current_password" required
                class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-ink transition-colors">
              @error('current_password')
                <p class="text-red-400 text-xs mt-1.5 font-light">{{ $message }}</p>
              @enderror
            </div>

            <!-- New Password & Confirm New Password -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <label for="password" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Password Baru</label>
                <input type="password" name="password" id="password" required
                  class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-ink transition-colors">
                @error('password')
                  <p class="text-red-400 text-xs mt-1.5 font-light">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="password_confirmation" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                  class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-ink transition-colors">
              </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-surface2 flex justify-end">
              <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gold hover:bg-gold-lt text-white text-xs font-semibold tracking-wider uppercase transition-all duration-300 rounded-sm cursor-pointer">
                <i data-feather="key" class="w-4 h-4"></i>
                Update Password
              </button>
            </div>

          </form>
        </div>

      </div>

    </div>
  </main>
@endsection
