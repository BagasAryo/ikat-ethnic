<footer class="bg-bg pt-20 pb-10 border-t border-white/5">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col items-center">
      <span class="font-bold text-xl tracking-wider text-gold uppercase mb-8">Tenun Gallery</span>

      <div class="flex flex-wrap justify-center gap-8 mb-12 text-xs tracking-widest text-muted font-medium uppercase">
        <a href="{{ url('/') }}" class="hover:text-gold-lt transition-colors">Home</a>
        <a href="{{ url('/products') }}" class="hover:text-gold-lt transition-colors">Products</a>
        <a href="{{ url('/about') }}" class="hover:text-gold-lt transition-colors">About</a>
        <a href="#" class="hover:text-gold-lt transition-colors">Terms</a>
      </div>

      <div class="flex space-x-6 mb-12">
        <a href="#" class="text-muted hover:text-gold-lt transition-colors">
          <i data-feather="instagram" class="w-5 h-5"></i>
        </a>
        <a href="#" class="text-muted hover:text-gold-lt transition-colors">
          <i data-feather="phone" class="w-5 h-5"></i>
        </a>
      </div>

      <p class="text-faint text-[10px] tracking-widest uppercase">
        &copy; {{ date('Y') }} Tenun Digital Gallery. All rights reserved.
      </p>
    </div>
  </div>
</footer>

<!-- Initialize Feather Icons -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    if (typeof feather !== 'undefined') {
      feather.replace();
    }
  });
</script>
