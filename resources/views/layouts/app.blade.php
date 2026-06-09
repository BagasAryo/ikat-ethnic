<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yield('title', 'Ikat Ethnic')</title>
  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- Styles / Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
  class="bg-bg text-ink font-body antialiased selection:bg-gold selection:text-bg flex flex-col min-h-screen">

  <!-- Navigation -->
  <x-navbar />

  <!-- Main Content -->
  @yield('content')

  <!-- Footer -->
  <x-footer />

  <!-- Initialize Feather Icons -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      feather.replace();
    });
  </script>

  @stack('scripts')
</body>

</html>
