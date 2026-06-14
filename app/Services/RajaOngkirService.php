<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $originCityId;

    public function __construct()
    {
        $this->apiKey      = config('services.rajaongkir.api_key');
        $this->baseUrl     = config('services.rajaongkir.base_url');
        $this->originCityId = (int) config('services.rajaongkir.origin_city_id');
    }

    /**
     * Ambil semua provinsi. Di-cache selama 24 jam.
     */
    public function getProvinces(): array
    {
        $provinces = Cache::get('rajaongkir_provinces');

        if (!$provinces) {
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->get("{$this->baseUrl}/province");

            if ($response->successful()) {
                $provinces = $response->json('rajaongkir.results', []);
                if (!empty($provinces)) {
                    Cache::put('rajaongkir_provinces', $provinces, now()->addHours(24));
                }
            }
        }

        return $provinces ?: [];
    }

    /**
     * Ambil kota berdasarkan province_id. Di-cache selama 24 jam.
     */
    public function getCities(?int $provinceId = null): array
    {
        $cacheKey = 'rajaongkir_cities_' . ($provinceId ?? 'all');
        $cities = Cache::get($cacheKey);

        if (!$cities) {
            $query = [];
            if ($provinceId) {
                $query['province'] = $provinceId;
            }

            $response = Http::withHeaders(['key' => $this->apiKey])
                ->get("{$this->baseUrl}/city", $query);

            if ($response->successful()) {
                $cities = $response->json('rajaongkir.results', []);
                if (!empty($cities)) {
                    Cache::put($cacheKey, $cities, now()->addHours(24));
                }
            }
        }

        return $cities ?: [];
    }

    /**
     * Hitung ongkos kirim ke kota tujuan dengan kurir tertentu.
     *
     * @param int    $destinationCityId  ID kota tujuan dari RajaOngkir
     * @param int    $weightGram         Berat total dalam gram
     * @param string $courier            Kode kurir: jne | pos | tiki
     * @return array                     Array hasil cost dari RajaOngkir
     */
    public function getCost(int $destinationCityId, int $weightGram, string $courier): array
    {
        $response = Http::withHeaders(['key' => $this->apiKey])
            ->post("{$this->baseUrl}/cost", [
                'origin'      => $this->originCityId,
                'destination' => $destinationCityId,
                'weight'      => $weightGram,
                'courier'     => $courier,
            ]);

        if ($response->successful()) {
            $results = $response->json('rajaongkir.results', []);
            if (!empty($results)) {
                return $results[0]['costs'] ?? [];
            }
        }

        return [];
    }

    /**
     * Ambil ongkir dari semua kurir yang didukung sekaligus.
     *
     * @param int $destinationCityId
     * @param int $weightGram
     * @return array  [ 'jne' => [...], 'pos' => [...], 'tiki' => [...] ]
     */
    public function getAllCouriers(int $destinationCityId, int $weightGram): array
    {
        $couriers = ['jne', 'pos', 'tiki'];
        $result   = [];

        foreach ($couriers as $courier) {
            $costs = $this->getCost($destinationCityId, $weightGram, $courier);
            if (!empty($costs)) {
                $result[$courier] = $costs;
            }
        }

        return $result;
    }
}
