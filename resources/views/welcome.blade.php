<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tenun Gallery | Ikat Ethnic</title>

    <!-- Fonts: Playfair Display & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-obsidian-900 text-obsidian-50 font-sans antialiased selection:bg-gold-500 selection:text-obsidian-900 flex flex-col min-h-screen">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2 cursor-pointer">
                    <span class="font-serif font-bold text-2xl tracking-wider text-gold-500 uppercase">Tenun Gallery</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-12">
                    <a href="#" class="text-xs tracking-[0.15em] font-medium text-gold-500 border-b border-gold-500 pb-1 uppercase">Collections</a>
                    <a href="#" class="text-xs tracking-[0.15em] font-medium text-obsidian-300 hover:text-gold-400 transition-colors uppercase">Artisans</a>
                    <a href="#" class="text-xs tracking-[0.15em] font-medium text-obsidian-300 hover:text-gold-400 transition-colors uppercase">Heritage</a>
                </div>

                <!-- Icons & Auth -->
                <div class="flex items-center space-x-6">
                    <button class="text-obsidian-200 hover:text-gold-400 transition-colors">
                        <i data-feather="search" class="w-5 h-5"></i>
                    </button>
                    <a href="#" class="text-obsidian-200 hover:text-gold-400 transition-colors relative">
                        <i data-feather="shopping-bag" class="w-5 h-5"></i>
                        <span class="absolute -top-1.5 -right-1.5 bg-gold-500 text-obsidian-900 text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center">0</span>
                    </a>
                    
                    @if (Route::has('login'))
                        <div class="hidden sm:flex items-center ml-4 pl-4 border-l border-obsidian-700">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-xs tracking-wider text-obsidian-200 hover:text-gold-400 uppercase transition-colors">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-xs tracking-wider text-obsidian-200 hover:text-gold-400 uppercase transition-colors">Log in</a>
                            @endauth
                        </div>
                    @endif

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden text-obsidian-200 hover:text-gold-400 transition-colors">
                        <i data-feather="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative w-full h-screen min-h-[600px] flex items-center justify-center overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 w-full h-full">
            <img src="https://images.unsplash.com/photo-1605518216938-7c31b7b14ad0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Ikat Weaving Process" class="absolute inset-0 w-full h-full object-cover object-center transform scale-105" />
            <div class="absolute inset-0 bg-linear-to-b from-obsidian-900/60 via-obsidian-900/80 to-obsidian-900"></div>
            <!-- Texture Overlay -->
            <div class="absolute inset-0 opacity-[0.03] mix-blend-overlay" style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-start justify-center pt-20">
            <div class="max-w-2xl">
                <p class="text-gold-400 text-xs sm:text-sm font-semibold tracking-[0.2em] uppercase mb-4 pl-1">The Sumba Collection</p>
                <h1 class="font-serif text-5xl sm:text-6xl md:text-7xl font-medium text-white leading-[1.1] mb-6 text-balance">
                    Woven Stories of <br/><span class="text-gold-100">Ancient Wisdom</span>
                </h1>
                <p class="text-obsidian-200 text-base sm:text-lg mb-10 max-w-lg font-light leading-relaxed">
                    Discover the prestigious craftsmanship of Sumba's master weavers. Each thread carries a legacy of culture, meticulously hand-dyed with natural pigments.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#catalog" class="inline-flex items-center justify-center px-8 py-3.5 bg-gold-500 hover:bg-gold-400 text-obsidian-950 text-sm font-medium tracking-wide uppercase transition-all duration-300">
                        Explore Sumba
                    </a>
                    <a href="#" class="inline-flex items-center justify-center px-8 py-3.5 bg-transparent border border-gold-500/50 hover:border-gold-400 text-gold-400 text-sm font-medium tracking-wide uppercase transition-all duration-300">
                        View Gallery
                    </a>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex flex-col items-center animate-bounce">
            <span class="text-[10px] tracking-[0.2em] uppercase text-obsidian-300 mb-2">Scroll</span>
            <i data-feather="chevron-down" class="text-gold-500 w-4 h-4"></i>
        </div>
    </header>

    <!-- Main Content: Product Catalog -->
    <main id="catalog" class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative z-20">
        
        <!-- Section Header & Filter/Search -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-16">
            <div class="flex-1">
                <div class="flex items-center justify-between w-full">
                    <!-- Filters -->
                    <div class="flex flex-wrap gap-3">
                        <button class="px-5 py-2 rounded-full bg-gold-500 text-obsidian-950 text-xs font-semibold tracking-wider uppercase transition-colors">
                            All Pieces
                        </button>
                        <button class="px-5 py-2 rounded-full bg-obsidian-800 border border-obsidian-700 hover:border-gold-500/50 text-obsidian-200 hover:text-gold-400 text-xs font-semibold tracking-wider uppercase transition-colors">
                            Sumba
                        </button>
                        <button class="px-5 py-2 rounded-full bg-obsidian-800 border border-obsidian-700 hover:border-gold-500/50 text-obsidian-200 hover:text-gold-400 text-xs font-semibold tracking-wider uppercase transition-colors">
                            Toraja
                        </button>
                        <button class="px-5 py-2 rounded-full bg-obsidian-800 border border-obsidian-700 hover:border-gold-500/50 text-obsidian-200 hover:text-gold-400 text-xs font-semibold tracking-wider uppercase transition-colors">
                            Ikat
                        </button>
                        <button class="px-5 py-2 rounded-full bg-obsidian-800 border border-obsidian-700 hover:border-gold-500/50 text-obsidian-200 hover:text-gold-400 text-xs font-semibold tracking-wider uppercase transition-colors">
                            Batik Silk
                        </button>
                    </div>
                    
                    <!-- Search & Sort -->
                    <div class="hidden lg:flex items-center gap-6">
                        <div class="flex items-center gap-2 text-xs text-obsidian-300 uppercase tracking-widest">
                            <span>Sort By:</span>
                            <button class="font-medium text-gold-400 flex items-center gap-1 hover:text-gold-300">
                                Newest Arrivals <i data-feather="chevron-down" class="w-3 h-3"></i>
                            </button>
                        </div>
                        
                        <div class="relative group">
                            <input type="text" placeholder="Search heritage..." class="w-64 bg-obsidian-800 border border-obsidian-700 focus:border-gold-500 text-obsidian-100 text-sm px-4 py-2.5 pl-10 rounded-sm outline-none transition-colors placeholder:text-obsidian-500">
                            <i data-feather="search" class="w-4 h-4 text-obsidian-400 absolute left-3 top-1/2 transform -translate-y-1/2 group-focus-within:text-gold-500 transition-colors"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
            
            <!-- Product Item 1 -->
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden bg-obsidian-800 aspect-4/5 mb-5">
                    <img src="https://images.unsplash.com/photo-1584989658253-731cc91cce66?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Hinggi Kombu" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
                    <!-- Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="bg-obsidian-950/80 backdrop-blur-sm text-gold-400 text-[10px] font-bold tracking-[0.2em] uppercase px-3 py-1">Masterpiece</span>
                    </div>
                    <!-- Hover Action -->
                    <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                        <button class="w-full bg-gold-500 hover:bg-gold-400 text-obsidian-950 font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-obsidian-400 text-xs tracking-widest uppercase mb-1">Sumba, East Nusa Tenggara</span>
                    <h3 class="font-serif text-lg text-obsidian-50 group-hover:text-gold-400 transition-colors mb-2">Hinggi Kombu Heritage</h3>
                    <p class="text-gold-500 font-medium tracking-wide">IDR 12,500,000</p>
                </div>
            </div>

            <!-- Product Item 2 -->
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden bg-obsidian-800 aspect-4/5 mb-5">
                    <img src="https://images.unsplash.com/photo-1603525281516-773a4b67f1de?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Batik Silk" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
                    <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                        <button class="w-full bg-gold-500 hover:bg-gold-400 text-obsidian-950 font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-obsidian-400 text-xs tracking-widest uppercase mb-1">Toraja, South Sulawesi</span>
                    <h3 class="font-serif text-lg text-obsidian-50 group-hover:text-gold-400 transition-colors mb-2">Pa'tedong Gold Thread</h3>
                    <p class="text-gold-500 font-medium tracking-wide">IDR 8,200,000</p>
                </div>
            </div>

            <!-- Product Item 3 -->
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden bg-obsidian-800 aspect-4/5 mb-5">
                    <img src="https://images.unsplash.com/photo-1616423458667-1721b017b209?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Ikat Silk" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="bg-obsidian-950/80 backdrop-blur-sm text-gold-400 text-[10px] font-bold tracking-[0.2em] uppercase px-3 py-1">New Arrival</span>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                        <button class="w-full bg-gold-500 hover:bg-gold-400 text-obsidian-950 font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-obsidian-400 text-xs tracking-widest uppercase mb-1">Sikka, Flores</span>
                    <h3 class="font-serif text-lg text-obsidian-50 group-hover:text-gold-400 transition-colors mb-2">Sikka Silk Sarong</h3>
                    <p class="text-gold-500 font-medium tracking-wide">IDR 15,000,000</p>
                </div>
            </div>

            <!-- Product Item 4 -->
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden bg-obsidian-800 aspect-4/5 mb-5">
                    <img src="https://images.unsplash.com/photo-1600889240409-eb5b7960fc5a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Lurik Weave" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100 grayscale hover:grayscale-0">
                    <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                        <button class="w-full bg-gold-500 hover:bg-gold-400 text-obsidian-950 font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-obsidian-400 text-xs tracking-widest uppercase mb-1">Baduy, West Java</span>
                    <h3 class="font-serif text-lg text-obsidian-50 group-hover:text-gold-400 transition-colors mb-2">Saroja Midnight Weave</h3>
                    <p class="text-gold-500 font-medium tracking-wide">IDR 4,500,000</p>
                </div>
            </div>

            <!-- Product Item 5 -->
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden bg-obsidian-800 aspect-4/5 mb-5">
                    <img src="https://images.unsplash.com/photo-1596489381665-661cc2e55728?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Endek Bali" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100 sepia-[.3]">
                    <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                        <button class="w-full bg-gold-500 hover:bg-gold-400 text-obsidian-950 font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-obsidian-400 text-xs tracking-widest uppercase mb-1">Klungkung, Bali</span>
                    <h3 class="font-serif text-lg text-obsidian-50 group-hover:text-gold-400 transition-colors mb-2">Endek Surya Amber</h3>
                    <p class="text-gold-500 font-medium tracking-wide">IDR 6,800,000</p>
                </div>
            </div>

            <!-- Product Item 6 -->
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden bg-obsidian-800 aspect-4/5 mb-5">
                    <img src="https://images.unsplash.com/photo-1629198688000-71f23e745b6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Songket Palembang" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
                    <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                        <button class="w-full bg-gold-500 hover:bg-gold-400 text-obsidian-950 font-medium text-sm py-3 uppercase tracking-wider">Add to Bag</button>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-obsidian-400 text-xs tracking-widest uppercase mb-1">Palembang, South Sumatra</span>
                    <h3 class="font-serif text-lg text-obsidian-50 group-hover:text-gold-400 transition-colors mb-2">Songket Limar Gold</h3>
                    <p class="text-gold-500 font-medium tracking-wide">IDR 22,000,000</p>
                </div>
            </div>

        </div>

        <!-- Pagination -->
        <div class="mt-20 flex justify-center">
            <nav class="flex items-center gap-2">
                <button class="w-10 h-10 flex items-center justify-center text-obsidian-400 hover:text-gold-400 transition-colors cursor-not-allowed" disabled>
                    <i data-feather="chevron-left" class="w-4 h-4"></i>
                </button>
                <button class="w-10 h-10 flex items-center justify-center rounded-full bg-gold-500 text-obsidian-950 font-medium text-sm">1</button>
                <button class="w-10 h-10 flex items-center justify-center rounded-full text-obsidian-300 hover:text-gold-400 hover:bg-obsidian-800 font-medium text-sm transition-colors">2</button>
                <button class="w-10 h-10 flex items-center justify-center rounded-full text-obsidian-300 hover:text-gold-400 hover:bg-obsidian-800 font-medium text-sm transition-colors">3</button>
                <button class="w-10 h-10 flex items-center justify-center text-obsidian-300 hover:text-gold-400 transition-colors">
                    <i data-feather="chevron-right" class="w-4 h-4"></i>
                </button>
            </nav>
        </div>

    </main>

    <!-- Editorial / Feature Section -->
    <section class="border-t border-obsidian-800 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="relative h-[400px] overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1618080183181-a67b93859816?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Artisan Weaver" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                    <div class="absolute inset-0 bg-linear-to-t from-obsidian-950 via-obsidian-900/40 to-transparent"></div>
                    <div class="absolute bottom-8 left-8 pr-8">
                        <span class="text-gold-400 text-xs font-semibold tracking-[0.2em] uppercase mb-2 block">Meet the Maker</span>
                        <h3 class="font-serif text-3xl text-white mb-3">Ibu Ratna: Guardian of Sumba</h3>
                        <p class="text-obsidian-200 text-sm leading-relaxed max-w-md font-light">A third-generation master weaver preserving the sacred symbolism of Hinggi Kombu for over 40 years.</p>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center bg-gold-200/5 h-[400px] p-12 text-center border border-gold-500/10">
                    <i data-feather="shield" class="text-gold-500 w-8 h-8 mb-6"></i>
                    <h3 class="font-serif text-2xl text-gold-100 mb-4">Authenticity Guaranteed</h3>
                    <p class="text-obsidian-300 text-sm leading-relaxed font-light mb-8 max-w-xs">Every piece comes with a signed certificate of origin and artisan profile.</p>
                    <a href="#" class="text-gold-500 text-xs tracking-[0.15em] uppercase font-medium border-b border-gold-500/30 hover:border-gold-500 pb-1 transition-colors">Learn Our Process</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-obsidian-950 pt-20 pb-10 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center">
                <span class="font-serif font-bold text-xl tracking-wider text-gold-500 uppercase mb-8">Tenun Gallery</span>
                
                <div class="flex flex-wrap justify-center gap-8 mb-12 text-xs tracking-widest text-obsidian-400 font-medium uppercase">
                    <a href="#" class="hover:text-gold-400 transition-colors">Shopping</a>
                    <a href="#" class="hover:text-gold-400 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-gold-400 transition-colors">Contact</a>
                    <a href="#" class="hover:text-gold-400 transition-colors">Terms</a>
                </div>

                <div class="flex space-x-6 mb-12">
                    <a href="#" class="text-obsidian-500 hover:text-gold-400 transition-colors">
                        <i data-feather="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-obsidian-500 hover:text-gold-400 transition-colors">
                        <i data-feather="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-obsidian-500 hover:text-gold-400 transition-colors">
                        <i data-feather="twitter" class="w-5 h-5"></i>
                    </a>
                </div>

                <p class="text-obsidian-600 text-[10px] tracking-widest uppercase">
                    &copy; {{ date('Y') }} Tenun Digital Gallery. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Initialize Feather Icons & Navbar Scroll Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Icons
            feather.replace();

            // Navbar scroll effect
            const nav = document.querySelector('nav');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    nav.classList.add('shadow-md', 'bg-obsidian-950/95');
                    nav.classList.remove('bg-obsidian-900/80');
                } else {
                    nav.classList.remove('shadow-md', 'bg-obsidian-950/95');
                    nav.classList.add('bg-obsidian-900/80');
                }
            });
        });
    </script>
</body>
</html>
