<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produks = [
            [
                'name' => 'Kain Tenun Motif Sumba',
                'slug' => 'kain-tenun-motif-sumba',
                'description' => 'Kain tenun motif sumba',
                'price' => 100000,
                'stock' => 10,
                'kategori_id' => 1,
            ],
            [
                'name' => 'Kain Tenun Motif NTT',
                'slug' => 'kain-tenun-motif-ntt',
                'description' => 'Kain tenun motif NTT',
                'price' => 200000,
                'stock' => 20,
                'kategori_id' => 1,
            ],
            [
                'name' => 'Tas anyaman dari lidi aren',
                'slug' => 'tas-anyaman-dari-lidi-aren',
                'description' => 'Tas anyaman dari lidi aren',
                'price' => 150000,
                'stock' => 20,
                'kategori_id' => 2,
            ],
            [
                'name' => 'Kemeja Pria Casual Prima',
                'slug' => 'kemeja-pria-casual-prima',
                'description' => 'Kemeja pria casual prima',
                'price' => 250000,
                'stock' => 15,
                'kategori_id' => 3,
            ],
            [
                'name' => 'Kemeja Pria Casual Pendek Motif Asmat',
                'slug' => 'kemeja-pria-casual-pendek-motif-asmat',
                'description' => 'Kemeja pria casual pendek motif asmat',
                'price' => 250000,
                'stock' => 20,
                'kategori_id' => 3,
            ],
            [
                'name' => 'Kimono Unisex Kombinasi Katun Triyono',
                'slug' => 'kimono-unisex-kombinasi-katun-triyono',
                'description' => 'Kimono unisex kombinasi katun triyono',
                'price' => 250000,
                'stock' => 20,
                'kategori_id' => 3,
            ],
            [
                'name' => 'tas anyaman dari lidi aren',
                'slug' => 'tas-anyaman-dari-lidi-aren',
                'description' => 'Tas anyaman dari lidi aren',
                'price' => 150000,
                'stock' => 20,
                'kategori_id' => 2,
            ],
            [
                'name' => 'tas anyaman dari lidi aren',
                'slug' => 'tas-anyaman-dari-lidi-aren',
                'description' => 'Tas anyaman dari lidi aren',
                'price' => 150000,
                'stock' => 20,
                'kategori_id' => 2,
            ],
            [
                'name' => 'tas anyaman dari lidi aren',
                'slug' => 'tas-anyaman-dari-lidi-aren',
                'description' => 'Tas anyaman dari lidi aren',
                'price' => 150000,
                'stock' => 20,
                'kategori_id' => 2,
            ],
            [
                'name' => 'tas anyaman dari lidi aren',
                'slug' => 'tas-anyaman-dari-lidi-aren',
                'description' => 'Tas anyaman dari lidi aren',
                'price' => 150000,
                'stock' => 20,
                'kategori_id' => 2,
            ],
        ];

        foreach ($produks as $produk) {
            Produk::create($produk);
        }
    }
}
