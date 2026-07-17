@extends('layouts.app')

@section('title', 'Checkout | Ikat Ethnic')
@section('content')
  <!-- Page Header -->
  <header class="pt-32 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
    <h1 class="font-body text-3xl md:text-4xl font-medium text-ink">Checkout</h1>
    <p class="text-muted text-sm mt-2 font-light">Lengkapi informasi pengiriman dan selesaikan pembayaran.</p>
  </header>

  <!-- Checkout Content -->
  <main class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-20">
    <form id="checkout-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-start">
      @csrf

      <!-- Hidden fields untuk data RajaOngkir -->
      <input type="hidden" name="shipping_city_id" id="field_city_id">
      <input type="hidden" name="shipping_city_name" id="field_city_name">
      <input type="hidden" name="shipping_district_id" id="field_district_id">
      <input type="hidden" name="shipping_district_name" id="field_district_name">
      <input type="hidden" name="shipping_province" id="field_province_name">
      <input type="hidden" name="shipping_courier" id="field_courier">
      <input type="hidden" name="shipping_service" id="field_service">
      <input type="hidden" name="shipping_cost" id="field_shipping_cost" value="0">
      <input type="hidden" name="shipping_etd" id="field_etd">

      <!-- Hidden fields untuk cart items yang dipilih -->
      @foreach ($cartItems as $item)
        <input type="hidden" name="cart_items[]" value="{{ $item->id }}">
      @endforeach

      <!-- Shipping Information Form -->
      <div class="lg:col-span-2 flex flex-col gap-8">

        <!-- Receiver Info -->
        <div class="bg-surface border border-surface2 p-5 sm:p-8 flex flex-col gap-5 sm:gap-6">
          <h2 class="text-ink font-medium text-lg border-b border-surface2 pb-4 tracking-wide">Informasi Penerima</h2>
          <div class="flex flex-col gap-5">
            <!-- Receiver Name -->
            <div class="flex flex-col gap-2">
              <label for="shipping_name" class="text-ink text-xs uppercase tracking-wider font-medium">Nama
                Penerima</label>
              <input type="text" name="shipping_name" id="shipping_name"
                value="{{ old('shipping_name', $user->name) }}" required
                class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors">
            </div>

            <!-- Receiver Phone -->
            <div class="flex flex-col gap-2">
              <label for="shipping_phone" class="text-ink text-xs uppercase tracking-wider font-medium">Nomor
                Telepon</label>
              <input type="text" name="shipping_phone" id="shipping_phone"
                value="{{ old('shipping_phone', $user->no_hp) }}" required
                class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors">
            </div>

            <!-- Full Address -->
            <div class="flex flex-col gap-2">
              <label for="shipping_address" class="text-ink text-xs uppercase tracking-wider font-medium">Alamat
                Lengkap</label>
              <textarea name="shipping_address" id="shipping_address" rows="3" required
                class="w-full bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors resize-y">{{ old('shipping_address', $user->alamat) }}</textarea>
            </div>
          </div>
        </div>

        <!-- Shipping Destination -->
        <div class="bg-surface border border-surface2 p-5 sm:p-8 flex flex-col gap-5 sm:gap-6">
          <h2 class="text-ink font-medium text-lg border-b border-surface2 pb-4 tracking-wide">Tujuan Pengiriman</h2>
          <div class="flex flex-col gap-5">

            <!-- Province -->
            <div class="flex flex-col gap-2">
              <label for="select_province"
                class="text-ink text-xs uppercase tracking-wider font-medium">Provinsi</label>
              <div class="relative">
                <select id="select_province"
                  class="w-full appearance-none bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors cursor-pointer">
                  <option value="">— Pilih Provinsi —</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                  <i data-feather="chevron-down" class="w-4 h-4 text-muted"></i>
                </div>
              </div>
            </div>

            <!-- City -->
            <div class="flex flex-col gap-2">
              <label for="select_city" class="text-ink text-xs uppercase tracking-wider font-medium">Kota /
                Kabupaten</label>
              <div class="relative">
                <select id="select_city" disabled
                  class="w-full appearance-none bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                  <option value="">— Pilih Kota / Kabupaten —</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                  <i data-feather="chevron-down" class="w-4 h-4 text-muted"></i>
                </div>
              </div>
            </div>

            <!-- District / Kecamatan -->
            <div class="flex flex-col gap-2">
              <label for="select_district"
                class="text-ink text-xs uppercase tracking-wider font-medium">Kecamatan</label>
              <div class="relative">
                <select id="select_district" disabled
                  class="w-full appearance-none bg-bg border border-surface2 text-ink text-sm px-4 py-3 rounded-sm outline-none focus:border-gold transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                  <option value="">— Pilih Kecamatan —</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                  <i data-feather="chevron-down" class="w-4 h-4 text-muted"></i>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Courier Selection -->
        <div id="courier-section" class="bg-surface border border-surface2 p-5 sm:p-8 flex flex-col gap-4">
          <h2 class="text-ink font-medium text-lg border-b border-surface2 pb-4 tracking-wide">Metode Pengiriman</h2>

          <!-- Loading state -->
          <div id="courier-loading" class="flex items-center gap-3 py-4 text-muted text-sm">
            <i data-feather="loader" class="w-4 h-4 animate-spin"></i>
            <span>Menghitung Ongkos Kirim...</span>
          </div>

          <!-- Courier list -->
          <div id="courier-list" class="flex flex-col gap-3"></div>

          <!-- No courier -->
          <div id="courier-empty" class="hidden text-muted text-sm py-2">
            Tidak ada jasa pengiriman yang tersedia untuk tujuan ini.
          </div>
        </div>

      </div>

      <!-- Order Summary Card -->
      <div class="bg-surface border border-surface2 shadow-sm p-5 sm:p-8 flex flex-col gap-5 lg:sticky lg:top-24">
        <h2 class="text-ink font-medium tracking-wide text-base border-b border-surface2 pb-3">Rincian Pesanan</h2>

        <!-- Items list -->
        <div class="flex flex-col gap-4 max-h-64 overflow-y-auto pr-1">
          @foreach ($cartItems as $item)
            <div class="flex gap-4 items-center">
              <div class="w-12 h-14 shrink-0 overflow-hidden bg-bg border border-surface2">
                <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                  alt="{{ $item->product->name }}" class="w-full h-full object-cover">
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-xs text-ink font-medium truncate">{{ ucwords($item->product->name) }}</h4>
                <p class="text-[10px] text-muted font-light mt-0.5">Size: {{ $item->product_size->name }} | Qty:
                  {{ $item->quantity }}</p>
              </div>
              <span class="text-ink text-xs font-medium shrink-0">
                Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
              </span>
            </div>
          @endforeach
        </div>

        <!-- Costs Breakdowns -->
        <div class="flex flex-col gap-3 text-sm border-t border-b border-surface2 py-4 mt-2">
          <div class="flex justify-between">
            <span class="text-muted">Subtotal</span>
            <span class="text-ink">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-muted">Ongkos Kirim</span>
            <span class="text-ink" id="display_shipping_cost">
              <span class="text-muted text-xs italic">Pilih Kurir</span>
            </span>
          </div>
          <!-- Courier badge -->
          <div id="selected-courier-badge" class="flex justify-between text-xs">
            <span class="text-muted">Kurir</span>
            <span id="badge-courier-text" class="text-gold font-medium"></span>
          </div>
        </div>

        <!-- Grand Total -->
        <div class="flex justify-between font-medium">
          <span class="text-muted text-sm uppercase tracking-widest">Total</span>
          <span class="text-ink text-lg font-semibold" id="display_total">
            Rp{{ number_format($subtotal, 0, ',', '.') }}
          </span>
        </div>

        <!-- Submit Button -->
        <button type="submit" id="btn-pay" disabled
          class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-sm bg-gold hover:bg-gold-lt text-white text-sm font-semibold tracking-wider uppercase transition-all duration-300 text-center cursor-pointer select-none disabled:opacity-50 disabled:cursor-not-allowed">
          <span id="btn-text">Bayar Sekarang</span>
          <span id="btn-spinner" class="hidden ml-2 animate-spin">
            <i data-feather="loader" class="w-4 h-4"></i>
          </span>
        </button>

        <!-- Secure disclaimer -->
        <div class="flex items-center justify-center gap-2 text-muted text-[10px] tracking-widest uppercase">
          <i data-feather="lock" class="w-3 h-3"></i>
          <span>Secure Midtrans Payment Gateway</span>
        </div>
      </div>
    </form>
  </main>
@endsection

@push('scripts')
  <!-- Midtrans Snap JS SDK -->
  <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      feather.replace();

      // ── Routes ────────────────────────────────────────────────────────
      const ROUTE_PROVINCES = "{{ route('shipping.provinces') }}";
      const ROUTE_CITIES = "{{ route('shipping.cities') }}";
      const ROUTE_DISTRICTS = "{{ route('shipping.districts') }}";
      const ROUTE_COST = "{{ route('shipping.cost') }}";
      const ROUTE_CHECKOUT = "{{ route('checkout.store') }}";
      const ROUTE_SUCCESS = "{{ route('checkout.success') }}";
      const ROUTE_ORDERS = "{{ route('orders') }}";
      const SUBTOTAL = {{ $subtotal }};
      const CSRF_TOKEN = document.querySelector('input[name="_token"]').value;

      // ── DOM refs ──────────────────────────────────────────────────────
      const selProvince = document.getElementById('select_province');
      const selCity = document.getElementById('select_city');
      const selDistrict = document.getElementById('select_district');
      const courierSection = document.getElementById('courier-section');
      const courierLoading = document.getElementById('courier-loading');
      const courierList = document.getElementById('courier-list');
      const courierEmpty = document.getElementById('courier-empty');
      const displayShipping = document.getElementById('display_shipping_cost');
      const displayTotal = document.getElementById('display_total');
      const selectedBadge = document.getElementById('selected-courier-badge');
      const badgeCourierText = document.getElementById('badge-courier-text');
      const btnPay = document.getElementById('btn-pay');
      const btnText = document.getElementById('btn-text');
      const btnSpinner = document.getElementById('btn-spinner');
      const form = document.getElementById('checkout-form');

      // Hidden fields
      const fieldCityId = document.getElementById('field_city_id');
      const fieldCityName = document.getElementById('field_city_name');
      const fieldDistrictId = document.getElementById('field_district_id');
      const fieldDistrictName = document.getElementById('field_district_name');
      const fieldProvinceName = document.getElementById('field_province_name');
      const fieldCourier = document.getElementById('field_courier');
      const fieldService = document.getElementById('field_service');
      const fieldShippingCost = document.getElementById('field_shipping_cost');
      const fieldEtd = document.getElementById('field_etd');

      // ── Helpers ───────────────────────────────────────────────────────
      const formatRp = (num) => 'Rp' + Number(num).toLocaleString('id-ID');

      function updateTotal() {
        const cost = parseInt(fieldShippingCost.value) || 0;
        const total = SUBTOTAL + cost;
        displayTotal.textContent = formatRp(total);
      }

      function setShippingDisplay(cost) {
        displayShipping.innerHTML = cost > 0 ? formatRp(cost) :
          '<span class="text-muted text-xs italic">Select courier</span>';
      }

      // ── Load Provinces ────────────────────────────────────────────────
      fetch(ROUTE_PROVINCES)
        .then(r => r.json())
        .then(provinces => {
          provinces.forEach(p => {
            const opt = document.createElement('option');
            // API V2: { "id": 1, "name": "JAWA BARAT" }
            opt.value = p.id;
            opt.textContent = p.name;
            selProvince.appendChild(opt);
          });
          feather.replace();
        })
        .catch(() => console.error('Failed to load provinces'));

      // ── Province change → load Cities ────────────────────────────────
      selProvince.addEventListener('change', function() {
        const provinceId = this.value;
        const provinceName = this.options[this.selectedIndex]?.text ?? '';

        // Reset city
        selCity.innerHTML = '<option value="">— Select City —</option>';
        selCity.disabled = true;
        // Reset district
        selDistrict.innerHTML = '<option value="">— Select District —</option>';
        selDistrict.disabled = true;
        fieldProvinceName.value = provinceName;

        // Reset courier
        resetCourierSection();

        if (!provinceId) return;

        fetch(ROUTE_CITIES + '?province_id=' + provinceId)
          .then(r => r.json())
          .then(cities => {
            cities.forEach(c => {
              const opt = document.createElement('option');
              // API V2: { "id": 1, "name": "Kota Bandung" } atau sesuai response
              const cityName = c.name ?? (c.type ? c.type + ' ' + c.city_name : c.city_name);
              opt.value = c.id ?? c.city_id;
              opt.dataset.name = cityName;
              opt.textContent = cityName;
              selCity.appendChild(opt);
            });
            selCity.disabled = false;
            feather.replace();
          })
          .catch(() => console.error('Failed to load cities'));
      });

      // ── City change → load Districts ─────────────────────────────
      selCity.addEventListener('change', function() {
        const cityId = this.value;
        const cityName = this.options[this.selectedIndex]?.dataset.name ?? '';

        fieldCityId.value = cityId;
        fieldCityName.value = cityName;

        // Reset district
        selDistrict.innerHTML = '<option value="">— Select District —</option>';
        selDistrict.disabled = true;

        resetCourierSection();

        if (!cityId) return;

        fetch(ROUTE_DISTRICTS + '?city_id=' + cityId)
          .then(r => r.json())
          .then(districts => {
            districts.forEach(d => {
              const opt = document.createElement('option');
              opt.value = d.id;
              opt.dataset.name = d.name;
              opt.textContent = d.name;
              selDistrict.appendChild(opt);
            });
            selDistrict.disabled = false;
            feather.replace();
          })
          .catch(() => console.error('Failed to load districts'));
      });

      // ── District change → load Courier costs ─────────────────────────────
      selDistrict.addEventListener('change', function() {
        const districtId = this.value;
        const districtName = this.options[this.selectedIndex]?.dataset.name ?? '';

        fieldDistrictId.value = districtId;
        fieldDistrictName.value = districtName;

        resetCourierSection();

        if (!districtId) return;

        courierSection.classList.remove('hidden');
        courierLoading.classList.remove('hidden');
        courierList.innerHTML = '';
        courierEmpty.classList.add('hidden');

        fetch(ROUTE_COST, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': CSRF_TOKEN,
              'Accept': 'application/json',
            },
            body: JSON.stringify({
              destination_district_id: districtId,
              weight: 500
            })
          })
          .then(r => r.json())
          .then(costs => {
            courierLoading.classList.add('hidden');

            if (!costs || costs.length === 0) {
              courierEmpty.classList.remove('hidden');
              feather.replace();
              return;
            }

            costs.forEach((item, idx) => {
              const id = 'courier_opt_' + idx;
              const label = document.createElement('label');
              label.htmlFor = id;
              label.className =
                'flex items-center gap-4 p-4 border border-surface2 rounded-sm cursor-pointer hover:border-gold transition-colors group';
              label.innerHTML = `
                <input type="radio" id="${id}" name="courier_option" value="${idx}"
                  data-courier="${item.courier_key}" data-service="${item.service}"
                  data-cost="${item.cost}" data-etd="${item.etd}"
                  class="accent-yellow-500 w-4 h-4 shrink-0">
                <div class="flex-1">
                  <div class="flex items-center gap-2">
                    <span class="text-ink text-xs font-bold uppercase tracking-wider">${item.courier}</span>
                    <span class="text-muted text-xs">${item.service}</span>
                    <span class="text-muted text-[10px]">· ${item.description}</span>
                  </div>
                  <p class="text-muted text-[10px] mt-0.5">Estimated: ${item.etd}</p>
                </div>
                <span class="text-ink text-sm font-semibold shrink-0">${formatRp(item.cost)}</span>
              `;
              courierList.appendChild(label);

              // Radio change handler
              label.querySelector('input[type="radio"]').addEventListener('change', function() {
                fieldCourier.value = this.dataset.courier;
                fieldService.value = this.dataset.service;
                fieldShippingCost.value = this.dataset.cost;
                fieldEtd.value = this.dataset.etd;

                setShippingDisplay(parseInt(this.dataset.cost));
                updateTotal();

                badgeCourierText.textContent = item.courier + ' ' + item.service + ' (' + item.etd +
                  ' days)';
                selectedBadge.classList.remove('hidden');

                btnPay.removeAttribute('disabled');

                // Highlight selected
                document.querySelectorAll('[name="courier_option"]').forEach(r => {
                  r.closest('label').classList.remove('border-gold', 'bg-surface2');
                });
                this.closest('label').classList.add('border-gold', 'bg-surface2');
              });
            });

            feather.replace();
          })
          .catch(() => {
            courierLoading.classList.add('hidden');
            courierEmpty.classList.remove('hidden');
            feather.replace();
          });
      });

      // ── Reset helper ──────────────────────────────────────────────────
      function resetCourierSection() {
        courierSection.classList.add('hidden');
        courierList.innerHTML = '';
        courierLoading.classList.add('hidden');
        courierEmpty.classList.add('hidden');
        fieldCourier.value = '';
        fieldService.value = '';
        fieldShippingCost.value = '0';
        fieldEtd.value = '';
        setShippingDisplay(0);
        updateTotal();
        selectedBadge.classList.add('hidden');
        btnPay.setAttribute('disabled', 'true');
      }

      // ── Form submit → Midtrans ────────────────────────────────────────
      form.addEventListener("submit", (e) => {
        e.preventDefault();

        if (!fieldCourier.value || !fieldService.value) {
          alert('Please select a shipping courier before proceeding.');
          return;
        }

        btnPay.setAttribute("disabled", "true");
        btnText.textContent = "Processing...";
        btnSpinner.classList.remove("hidden");

        const formData = new FormData(form);

        fetch(ROUTE_CHECKOUT, {
            method: "POST",
            headers: {
              "X-CSRF-TOKEN": CSRF_TOKEN,
              "Accept": "application/json"
            },
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              return response.json().then(err => {
                throw err;
              });
            }
            return response.json();
          })
          .then(data => {
            window.snap.pay(data.snap_token, {
              onSuccess: function() {
                window.location.href = ROUTE_SUCCESS + "?order_id=" + data.order_id;
              },
              onPending: function() {
                window.location.href = ROUTE_ORDERS;
              },
              onError: function(result) {
                alert("Payment failed: " + (result.status_message || "Unknown error"));
                resetButtonState();
              },
              onClose: function() {
                alert("You closed the payment popup before completing the transaction.");
                resetButtonState();
              }
            });
          })
          .catch(err => {
            alert("Checkout error: " + (err.message || "Something went wrong"));
            resetButtonState();
          });
      });

      function resetButtonState() {
        btnPay.removeAttribute("disabled");
        btnText.textContent = "Pay Now";
        btnSpinner.classList.add("hidden");
      }
    });
  </script>
@endpush
