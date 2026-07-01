<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Tenun Heritage')</title>

  <!-- Fonts: Playfair Display & Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

  <!-- Feather Icons CDN -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- Styles & Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @stack('head')
</head>

<body class="bg-bg text-ink font-body antialiased selection:bg-gold selection:text-bg h-full flex">

  <!-- Simple Centered Layout -->
  <div class="flex w-full min-h-screen items-center justify-center p-4">

    <!-- Card Container -->
    <div class="w-full max-w-md bg-surface2 p-4 sm:p-8 relative shadow-2xl border border-faint/20 rounded-md">

      <div class="relative z-10">
        <!-- Form Header -->
        <div class="mb-2 text-center">
          <h1 class="font-display text-2xl text-ink mb-2">@yield('form-title')</h1>
          <p class="text-muted text-xs font-light leading-relaxed">@yield('form-subtitle')</p>
        </div>

        <!-- Form Content Slot -->
        @yield('form')
      </div>
    </div>
  </div>

  <script>
    // Initialize Feather Icons
    feather.replace();

    // Toggle password visibility
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
</body>

</html>
