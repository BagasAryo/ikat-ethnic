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
     * getAllCouriers sudah mengembalikan flat array yang siap dikonsumsi frontend.
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

        // getAllCouriers sudah mengembalikan flat array yang sudah diformat dan diurutkan
        $options = $this->rajaOngkir->getAllCouriers($destinationCityId, $weight);

        return response()->json($options);
    }
}

