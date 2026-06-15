<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
     * Endpoint: GET /destination/province
     * Response API V2: { "meta": {...}, "data": [ { "id": 1, "name": "..." } ] }
     */
    public function getProvinces(): array
    {
        $provinces = Cache::get('rajaongkir_provinces');

        if (!$provinces) {
            $response = Http::withHeaders(['Key' => $this->apiKey])
                ->get("{$this->baseUrl}/destination/province");

            Log::info('RajaOngkir getProvinces', [
                'url'    => "{$this->baseUrl}/destination/province",
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if ($response->successful()) {
                // API V2 mengembalikan { "meta": {...}, "data": [...] }
                $provinces = $response->json('data', []);
                if (!empty($provinces)) {
                    Cache::put('rajaongkir_provinces', $provinces, now()->addHours(24));
                }
            } else {
                Log::error('RajaOngkir getProvinces gagal', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            }
        }

        return $provinces ?: [];
    }

    /**
     * Ambil kota berdasarkan province_id. Di-cache selama 24 jam.
     * Endpoint: GET /destination/city/{province_id}
     * Response API V2: { "meta": {...}, "data": [ { "id": 1, "name": "..." } ] }
     */
    public function getCities(?int $provinceId = null): array
    {
        $cacheKey = 'rajaongkir_cities_' . ($provinceId ?? 'all');
        $cities = Cache::get($cacheKey);

        if (!$cities) {
            // API V2: province_id sebagai path parameter, bukan query string
            $url = $provinceId
                ? "{$this->baseUrl}/destination/city/{$provinceId}"
                : "{$this->baseUrl}/destination/city";

            $response = Http::withHeaders(['Key' => $this->apiKey])
                ->get($url);

            Log::info('RajaOngkir getCities', [
                'url'    => $url,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if ($response->successful()) {
                // API V2 mengembalikan { "meta": {...}, "data": [...] }
                $cities = $response->json('data', []);
                if (!empty($cities)) {
                    Cache::put($cacheKey, $cities, now()->addHours(24));
                }
            } else {
                Log::error('RajaOngkir getCities gagal', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            }
        }

        return $cities ?: [];
    }

    /**
     * Hitung ongkos kirim ke kota tujuan — semua kurir sekaligus.
     * Endpoint: POST /calculate/district/domestic-cost
     * Content-Type: application/x-www-form-urlencoded
     *
     * API V2 mengembalikan flat array:
     * { "data": [ { "code":"jne", "name":"JNE", "service":"REG", "description":"...", "cost":12000, "etd":"3 day" } ] }
     *
     * @param int    $destinationCityId  ID kota/district tujuan dari RajaOngkir
     * @param int    $weightGram         Berat total dalam gram
     * @param string $courier            Kode kurir dipisahkan ':' (e.g. 'jne:pos:tiki')
     * @return array                     Flat array opsi pengiriman dari RajaOngkir
     */
    public function getCost(int $destinationCityId, int $weightGram, string $courier): array
    {
        // API V2 pakai asForm() karena Content-Type: application/x-www-form-urlencoded
        $response = Http::withHeaders(['Key' => $this->apiKey])
            ->asForm()
            ->post("{$this->baseUrl}/calculate/district/domestic-cost", [
                'origin'      => $this->originCityId,
                'destination' => $destinationCityId,
                'weight'      => $weightGram,
                'courier'     => $courier,
                'price'       => 'lowest',
            ]);

        Log::info('RajaOngkir getCost', [
            'url'    => "{$this->baseUrl}/calculate/district/domestic-cost",
            'status' => $response->status(),
        ]);

        if ($response->successful()) {
            return $response->json('data', []);
        }

        Log::error('RajaOngkir getCost gagal', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return [];
    }

    /**
     * Ambil semua opsi pengiriman dari kurir JNE, POS, TIKI dalam 1 request.
     * Mengembalikan flat array yang siap dikonsumsi frontend.
     *
     * Response format (API V2 flat):
     * [ { 'courier'=>'JNE', 'courier_key'=>'jne', 'service'=>'REG',
     *     'description'=>'...', 'cost'=>12000, 'etd'=>'3 day' }, ... ]
     *
     * @param int $destinationCityId
     * @param int $weightGram
     * @return array
     */
    public function getAllCouriers(int $destinationCityId, int $weightGram): array
    {
        // API V2 mendukung multi-kurir dalam satu request dengan separator ':'
        $rawData = $this->getCost($destinationCityId, $weightGram, 'jne:pos:tiki');

        // API V2 mengembalikan flat array — setiap item adalah 1 opsi layanan
        // { "code": "jne", "name": "Jalur Nugraha...", "service": "REG",
        //   "description": "Layanan Reguler", "cost": 12000, "etd": "3 day" }
        $formatted = [];
        foreach ($rawData as $item) {
            $code = $item['code'] ?? '';
            if (!$code) continue;

            $formatted[] = [
                'courier'     => strtoupper($code),
                'courier_key' => $code,
                'service'     => $item['service'] ?? '',
                'description' => $item['description'] ?? '',
                'cost'        => (int) ($item['cost'] ?? 0),
                'etd'         => $item['etd'] ?? '-',
            ];
        }

        // Urutkan dari yang termurah
        usort($formatted, fn($a, $b) => $a['cost'] <=> $b['cost']);

        return $formatted;
    }
}
