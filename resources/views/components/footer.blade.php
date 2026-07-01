<footer class="bg-surface pt-16 pb-8 border-t border-white/5">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Footer Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

      <!-- Brand -->
      <div class="sm:col-span-2 lg:col-span-1">
        <span class="font-bold text-xl tracking-wider text-gold uppercase block mb-4">Ikat Ethnic</span>
        <p class="text-muted text-sm font-light leading-relaxed max-w-xs">
          Menyajikan koleksi kain tenun autentik dari para penenun terampil di seluruh Nusantara.
          Setiap helai benang menyimpan cerita warisan budaya.
        </p>
      </div>

      <!-- Quick Links -->
      <div>
        <h4 class="text-ink text-xs font-semibold tracking-widest uppercase mb-4">Navigasi</h4>
        <ul class="space-y-3">
          <li><a href="{{ url('/') }}" class="text-muted text-sm hover:text-gold-lt transition-colors font-light">Home</a></li>
          <li><a href="{{ url('/products') }}" class="text-muted text-sm hover:text-gold-lt transition-colors font-light">Produk</a></li>
          <li><a href="{{ url('/about') }}" class="text-muted text-sm hover:text-gold-lt transition-colors font-light">Tentang Kami</a></li>
          <li><a href="{{ url('/cart') }}" class="text-muted text-sm hover:text-gold-lt transition-colors font-light">Keranjang</a></li>
        </ul>
      </div>

      <!-- Customer Service -->
      <div>
        <h4 class="text-ink text-xs font-semibold tracking-widest uppercase mb-4">Layanan</h4>
        <ul class="space-y-3">
          <li><span class="text-muted text-sm font-light">Pengiriman Seluruh Indonesia</span></li>
          <li><span class="text-muted text-sm font-light">Pembayaran Aman via Midtrans</span></li>
          <li><span class="text-muted text-sm font-light">Produk Handmade Autentik</span></li>
          <li><span class="text-muted text-sm font-light">Garansi Kualitas Terjamin</span></li>
        </ul>
      </div>

      <!-- Contact -->
      <div>
        <h4 class="text-ink text-xs font-semibold tracking-widest uppercase mb-4">Hubungi Kami</h4>
        <ul class="space-y-3">
          <li class="flex items-center gap-2 text-muted text-sm font-light">
            <i data-feather="mail" class="w-4 h-4 text-gold shrink-0"></i>
            <span>info@ikatethnic.id</span>
          </li>
          <li class="flex items-center gap-2 text-muted text-sm font-light">
            <i data-feather="phone" class="w-4 h-4 text-gold shrink-0"></i>
            <span>+62 812 3456 7890</span>
          </li>
          <li class="flex items-center gap-2 text-muted text-sm font-light">
            <i data-feather="map-pin" class="w-4 h-4 text-gold shrink-0"></i>
            <span>Sumba, NTT, Indonesia</span>
          </li>
        </ul>

        <!-- Social -->
        <div class="flex gap-4 mt-5">
          <a href="#" class="text-muted hover:text-gold-lt transition-colors">
            <i data-feather="instagram" class="w-5 h-5"></i>
          </a>
          <a href="#" class="text-muted hover:text-gold-lt transition-colors">
            <i data-feather="facebook" class="w-5 h-5"></i>
          </a>
          <a href="#" class="text-muted hover:text-gold-lt transition-colors">
            <i data-feather="phone" class="w-5 h-5"></i>
          </a>
        </div>
      </div>

    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-white/5 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
      <p class="text-faint text-[10px] tracking-widest uppercase text-center sm:text-left">
        &copy; {{ date('Y') }} Ikat Ethnic. All rights reserved.
      </p>
      <div class="flex gap-6">
        <a href="#" class="text-faint text-[10px] tracking-widest uppercase hover:text-muted transition-colors">Syarat & Ketentuan</a>
        <a href="#" class="text-faint text-[10px] tracking-widest uppercase hover:text-muted transition-colors">Kebijakan Privasi</a>
      </div>
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
