<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Kain Tenun Motif Sumba',
                'slug' => 'kain-tenun-motif-sumba',
                'description' => 'Kain tenun motif sumba',
                'price' => 100000,
                'sizes' => [
                    [
                        'name' => 'All Size',
                        'stock' => 20,
                    ]
                ],
                'images' => [
                    'products/kain-tenun-bunga.webp',
                ],
                'category_id' => 1,
            ],
            [
                'name' => 'Kain Tenun Motif NTT',
                'slug' => 'kain-tenun-motif-ntt',
                'description' => 'Kain tenun motif NTT',
                'price' => 200000,
                'sizes' => [
                    [
                        'name' => 'All Size',
                        'stock' => 20,
                    ]
                ],
                'images' => [
                    'products/kain-tenun-bunga.webp',
                ],
                'category_id' => 1,
            ],
            [
                'name' => 'Tas anyaman dari lidi aren',
                'slug' => 'tas-anyaman-dari-lidi-aren',
                'description' => 'Tas anyaman dari lidi aren',
                'price' => 150000,
                'sizes' => [
                    [
                        'name' => 'All Size',
                        'stock' => 20,
                    ]
                ],
                'images' => [
                    'products/kain-tenun-bunga.webp',
                ],
                'category_id' => 2,
            ],
            [
                'name' => 'Kemeja Pria Casual Prima',
                'slug' => 'kemeja-pria-casual-prima',
                'description' => 'Kemeja pria casual prima',
                'price' => 250000,
                'sizes' => [
                    [
                        'name' => 'M',
                        'stock' => 5,
                    ],
                    [
                        'name' => 'L',
                        'stock' => 5,
                    ],
                    [
                        'name' => 'XL',
                        'stock' => 5,
                    ],
                ],
                'images' => [
                    'products/kain-tenun-bunga.webp',
                ],
                'category_id' => 3,
            ],
            [
                'name' => 'Kemeja Pria Casual Pendek Motif Asmat',
                'slug' => 'kemeja-pria-casual-pendek-motif-asmat',
                'description' => 'Kemeja pria casual pendek motif asmat',
                'price' => 250000,
                'sizes' => [
                    [
                        'name' => 'S',
                        'stock' => 5,
                    ],
                    [
                        'name' => 'M',
                        'stock' => 5,
                    ],
                    [
                        'name' => 'L',
                        'stock' => 5,
                    ],
                    [
                        'name' => 'XL',
                        'stock' => 5,
                    ],
                ],
                'images' => [
                    'products/kain-tenun-toraja.webp',
                ],
                'category_id' => 3,
            ],
            [
                'name' => 'Kimono Unisex Kombinasi Katun Triyono',
                'slug' => 'kimono-unisex-kombinasi-katun-triyono',
                'description' => 'Kimono unisex kombinasi katun triyono',
                'price' => 250000,
                'sizes' => [
                    [
                        'name' => 'L',
                        'stock' => 5,
                    ],
                    [
                        'name' => 'XL',
                        'stock' => 5,
                    ],
                    [
                        'name' => 'XXL',
                        'stock' => 5,
                    ],
                ],
                'images' => [
                    'products/kain-tenun-bunga2.webp',
                ],
                'category_id' => 3,
            ],
            [
                'name' => 'Kain Tenun Motif Toraja',
                'slug' => 'kain-tenun-motif-toraja',
                'description' => 'Kain tenun motif toraja',
                'price' => 150000,
                'sizes' => [
                    [
                        'name' => 'All Size',
                        'stock' => 10,
                    ]
                ],
                'images' => [
                    'products/kain-tenun-bunga2.webp',
                ],
                'category_id' => 1,
            ],
            [
                'name' => 'Kemeja Pria Batik Tenun',
                'slug' => 'kemeja-pria-batik-tenun',
                'description' => 'Kemeja pria batik tenun',
                'price' => 150000,
                'sizes' => [
                    [
                        'name' => 'S',
                        'stock' => 10,
                    ],
                    [
                        'name' => 'M',
                        'stock' => 10,
                    ],
                    [
                        'name' => 'L',
                        'stock' => 10,
                    ],
                    [
                        'name' => 'XL',
                        'stock' => 10,
                    ],
                ],
                'images' => [
                    'products/kain-tenun-bunga.webp',
                ],
                'category_id' => 3,
            ],
            [
                'name' => 'Kain Tenun Motif Rote NTT',
                'slug' => 'kain-tenun-motif-rote-ntt',
                'description' => 'Kain tenun motif Rote NTT',
                'price' => 150000,
                'sizes' => [
                    [
                        'name' => 'All Size',
                        'stock' => 20,
                    ]
                ],
                'images' => [
                    'products/kain-tenun-bunga.webp',
                ],
                'category_id' => 1,
            ],
            [
                'name' => 'Kain Tenun Motif Bunga',
                'slug' => 'kain-tenun-motif-bunga',
                'description' => 'Kain tenun motif bunga',
                'price' => 150000,
                'sizes' => [
                    [
                        'name' => 'All Size',
                        'stock' => 20,
                    ]
                ],
                'images' => [
                    'products/kain-tenun-bunga.webp',
                ],
                'category_id' => 1,
            ],
        ];

        foreach ($products as $item) {
            $product = Product::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => $item['description'],
                'price' => $item['price'],
                'category_id' => $item['category_id'],
            ]);

            foreach ($item['sizes'] as $size) {
                $product->sizes()->create([
                    'name' => $size['name'],
                    'stock' => $size['stock'],
                ]);
            }

            foreach ($item['images'] as $index => $image) {
                $product->images()->create([
                    'image_url' => $image,
                    'is_thumbnail' => $index === 0,
                ]);
            }
        }
    }
}
