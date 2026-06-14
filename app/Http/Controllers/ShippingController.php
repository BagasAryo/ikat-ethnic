<?php

namespace App\Http\Controllers;

use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function __construct(protected RajaOngkirService $rajaOngkir) {}

    /**
     * Ambil daftar provinsi.
     */
    public function provinces()
    {
        $provinces = $this->rajaOngkir->getProvinces();
        return response()->json($provinces);
    }

    /**
     * Ambil daftar kota berdasarkan province_id.
     */
    public function cities(Request $request)
    {
        $provinceId = $request->query('province_id');
        $cities = $this->rajaOngkir->getCities($provinceId ? (int) $provinceId : null);
        return response()->json($cities);
    }

    /**
     * Hitung ongkos kirim ke kota tujuan.
     * Mengembalikan semua pilihan kurir & layanan beserta tarifnya.
     */
    public function cost(Request $request)
    {
        $request->validate([
            'destination_city_id' => 'required|integer',
            'weight'              => 'nullable|integer|min:1',
        ]);

        $destinationCityId = (int) $request->input('destination_city_id');
        // Default berat 500g jika tidak disertakan
        $weight = (int) $request->input('weight', 500);

        $allCosts = $this->rajaOngkir->getAllCouriers($destinationCityId, $weight);

        // Format ulang agar lebih mudah dikonsumsi frontend
        $formatted = [];
        foreach ($allCosts as $courierCode => $services) {
            foreach ($services as $service) {
                $formatted[] = [
                    'courier'     => strtoupper($courierCode),
                    'courier_key' => $courierCode,
                    'service'     => $service['service'],
                    'description' => $service['description'],
                    'cost'        => $service['cost'][0]['value'] ?? 0,
                    'etd'         => $service['cost'][0]['etd'] ?? '-',
                ];
            }
        }

        // Urutkan dari termurah
        usort($formatted, fn($a, $b) => $a['cost'] <=> $b['cost']);

        return response()->json($formatted);
    }
}
