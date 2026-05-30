<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Profile | Ikat Ethnic</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
    rel="stylesheet">

  <script src="https://unpkg.com/feather-icons"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
  class="bg-bg text-ink font-body antialiased selection:bg-gold selection:text-obsidian-900 flex flex-col min-h-screen">

  <x-navbar />

  <!-- Page Header -->
  <header class="pt-32 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full text-center">
    <h1 class="font-body text-3xl md:text-4xl font-medium text-white">Edit Profile</h1>
    <p class="text-muted text-sm mt-2 font-light">Update your personal information and security settings</p>
  </header>

  <!-- Content -->
  <main class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Left Sidebar - Profile Summary -->
      <div class="flex flex-col gap-6">
        <!-- Profile Card -->
        <div class="bg-surface border border-surface2 p-8 flex flex-col items-center gap-6">
          <!-- Avatar -->
          <div class="w-20 h-20 rounded-full bg-linear-to-br from-gold to-gold-lt flex items-center justify-center">
            <span class="text-xl font-medium text-bg uppercase">
              {{ substr($user->name, 0, 1) }}
            </span>
          </div>

          <!-- User Info -->
          <div class="text-center w-full">
            <h2 class="text-xl font-medium text-white mb-1">{{ $user->name }}</h2>
            <p class="text-muted text-sm break-all">{{ $user->email }}</p>
            <span
              class="inline-block mt-3 px-3 py-1 text-xs tracking-widest uppercase bg-gold/20 text-gold rounded-full">
              {{ ucfirst($user->role ?? 'customer') }}
            </span>
          </div>

          <!-- Quick Stats -->
          <div class="w-full grid grid-cols-2 gap-4 pt-6 border-t border-surface2">
            <div class="text-center">
              <div class="text-gold text-xl font-medium">{{ Auth::user()->orders->count() }}</div>
              <div class="text-muted text-xs tracking-widest uppercase mt-1">Orders</div>
            </div>
            <div class="text-center">
              <div class="text-gold text-xl font-medium">Member</div>
              <div class="text-muted text-xs tracking-widest uppercase mt-1">Since
                {{ $user->created_at->format('M Y') }}
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="flex flex-col gap-3">
          <a href="{{ route('profile') }}"
            class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('profile*') ? 'border-gold text-gold bg-gold/10' : 'bg-surface border border-surface2 hover:border-gold text-white hover:text-gold' }} transition-colors rounded-sm">
            <i data-feather="user" class="w-4 h-4"></i>
            <span class="text-sm font-medium">Profile</span>
          </a>
          <a href="{{ route('orders') }}"
            class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('orders*') ? 'border-gold text-gold bg-gold/10' : 'bg-surface border border-surface2 hover:border-gold text-white hover:text-gold' }} transition-colors rounded-sm">
            <i data-feather="shopping-bag" class="w-4 h-4"></i>
            <span class="text-sm font-medium">Orders</span>
          </a>
          <form action="{{ route('logout') }}" method="POST" class="flex items-center gap-3 px-4 py-3 bg-surface border border-surface2 hover:border-gold text-white hover:text-gold transition-colors rounded-sm">
            @csrf
            <i data-feather="log-out" class="w-4 h-4"></i>
            <button type="submit" class="text-sm font-medium cursor-pointer">Logout</button>
          </form>
        </div>
      </div>

      <!-- Main Content - Edit Profile Form -->
      <div class="lg:col-span-2 flex flex-col gap-8">
        
        <!-- Back Button -->
        <a href="{{ route('profile') }}" class="inline-flex items-center gap-2 text-xs text-muted hover:text-gold uppercase tracking-wider transition-colors w-fit">
          <i data-feather="arrow-left" class="w-4 h-4"></i>
          Back to Profile
        </a>

        <!-- Display Session Error Alerts if Any -->
        @if ($errors->any())
          <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-sm text-sm">
            <div class="flex items-center gap-2 font-medium mb-1">
              <i data-feather="alert-circle" class="w-4 h-4"></i>
              <span>Please correct the errors below:</span>
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
            <h3 class="text-lg font-medium text-white">Personal Information</h3>
          </div>

          <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name & Email -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <label for="name" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                  class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors">
                @error('name')
                  <p class="text-red-400 text-xs mt-1.5 font-light">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="email" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Email Address</label>
                <input type="email" id="email" value="{{ $user->email }}" disabled
                  class="w-full bg-bg/50 border border-surface2 text-muted text-sm px-4 py-3 rounded-sm cursor-not-allowed outline-none">
                <span class="text-[10px] text-muted font-light mt-1.5 block">Email address cannot be changed.</span>
              </div>
            </div>

            <!-- Phone Number -->
            <div>
              <label for="no_hp" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Phone Number</label>
              <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="e.g. 081234567890"
                class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors">
              @error('no_hp')
                <p class="text-red-400 text-xs mt-1.5 font-light">{{ $message }}</p>
              @enderror
            </div>

            <!-- Shipping Address -->
            <div>
              <label for="alamat" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Shipping Address</label>
              <textarea name="alamat" id="alamat" rows="4" placeholder="Enter your complete shipping address"
                class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors resize-y">{{ old('alamat', $user->alamat) }}</textarea>
              @error('alamat')
                <p class="text-red-400 text-xs mt-1.5 font-light">{{ $message }}</p>
              @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-surface2 flex justify-end gap-4">
              <a href="{{ route('profile') }}"
                class="inline-flex items-center justify-center px-6 py-3 border border-surface2 hover:border-gold hover:text-gold text-muted text-xs font-semibold tracking-wider uppercase transition-colors rounded-sm">
                Cancel
              </a>
              <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gold hover:bg-gold-lt text-bg text-xs font-semibold tracking-wider uppercase transition-all duration-300 rounded-sm cursor-pointer">
                <i data-feather="save" class="w-4 h-4"></i>
                Save Changes
              </button>
            </div>

          </form>
        </div>

        <!-- Form 2: Change Password -->
        <div class="bg-surface border border-surface2 p-8">
          <div class="flex items-center gap-3 mb-8 pb-6 border-b border-surface2">
            <i data-feather="lock" class="w-5 h-5 text-gold"></i>
            <h3 class="text-lg font-medium text-white">Change Password</h3>
          </div>

          <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
              <label for="current_password" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Current Password</label>
              <input type="password" name="current_password" id="current_password" required
                class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors">
              @error('current_password')
                <p class="text-red-400 text-xs mt-1.5 font-light">{{ $message }}</p>
              @enderror
            </div>

            <!-- New Password & Confirm New Password -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <label for="password" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">New Password</label>
                <input type="password" name="password" id="password" required
                  class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors">
                @error('password')
                  <p class="text-red-400 text-xs mt-1.5 font-light">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="password_confirmation" class="block text-xs text-muted tracking-widest uppercase mb-2 font-medium">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                  class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors">
              </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-surface2 flex justify-end">
              <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gold hover:bg-gold-lt text-bg text-xs font-semibold tracking-wider uppercase transition-all duration-300 rounded-sm cursor-pointer">
                <i data-feather="key" class="w-4 h-4"></i>
                Update Password
              </button>
            </div>

          </form>
        </div>

      </div>

    </div>
  </main>

  <x-footer />

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      feather.replace();
    });
  </script>
</body>

</html>
