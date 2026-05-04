<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tenun Heritage')</title>

    <!-- Fonts: Playfair Display & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Feather Icons CDN -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="bg-bg text-ink font-body antialiased selection:bg-gold selection:text-bg h-full flex">

    <!-- Two-Column Split Layout -->
    <div class="flex w-full min-h-screen">

        <!-- ── Left Panel: Imagery + Brand ──────────────────── -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-surface overflow-hidden group">
            <!-- Background Image -->
            <img src="https://images.unsplash.com/photo-1600889240409-eb5b7960fc5a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                 alt="Heritage Weave"
                 class="absolute inset-0 w-full h-full object-cover object-center sepia-[.4] opacity-50 transition-transform duration-[20s] group-hover:scale-110">

            <!-- Gradient Overlays -->
            <div class="absolute inset-0 bg-linear-to-r from-bg via-bg/70 to-transparent"></div>
            <div class="absolute inset-0 bg-linear-to-b from-transparent via-transparent to-bg/90"></div>

            <!-- Panel Content -->
            <div class="relative z-10 p-16 flex flex-col justify-between h-full">
                <!-- Brand Logo (left panel) -->
                <a href="{{ url('/') }}"
                   class="font-display font-bold text-xl tracking-[0.2em] text-gold uppercase hover:text-gold-lt transition-colors">
                    Tenun Heritage
                </a>

                <!-- Bottom Quote / Tagline -->
                <div class="max-w-md">
                    <span class="text-gold text-[10px] font-bold tracking-[0.3em] uppercase mb-4 block">
                        Warisan Budaya
                    </span>
                    <h2 class="font-display text-4xl leading-tight text-white mb-6">
                        Woven Legacy, Modern Luxury.
                    </h2>
                    <p class="text-muted font-light leading-relaxed">
                        Step into the digital sanctuary of Indonesia's finest hand-woven masterpieces.
                    </p>
                </div>
            </div>
        </div>

        <!-- ── Right Panel: Form ─────────────────────────────── -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-surface2 relative">

            <!-- Subtle Geometric Texture -->
            <div class="absolute inset-0 opacity-[0.02]"
                 style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M54.627 0l.83.83-54.627 54.627-.83-.83L54.627 0zM30 0l24.627 24.627-.83.83L29.17 0H30zM0 30l24.627 24.627-.83.83L0 30.83V30zm0-30l30 30v.83L-.83 0h.83z\' fill=\'%23ffffff\' fill-rule=\'evenodd\'/%3E%3C/svg%3E');">
            </div>

            <div class="w-full max-w-md relative z-10">

                <!-- Mobile Brand Logo -->
                <a href="{{ url('/') }}"
                   class="lg:hidden font-display font-bold text-xl tracking-[0.2em] text-gold uppercase mb-12 block text-center hover:text-gold-lt transition-colors">
                    Tenun Heritage
                </a>

                <!-- Form Header -->
                <div class="">
                    <h1 class="font-display text-3xl text-ink">@yield('form-title')</h1>
                    <p class="text-muted text-sm font-light leading-relaxed">@yield('form-subtitle')</p>
                </div>

                <!-- Form Content Slot -->
                @yield('form')
            </div>
        </div>
    </div>

    <!-- Initialize Feather Icons -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            feather.replace();
        });
    </script>

    @stack('scripts')
</body>
</html>
