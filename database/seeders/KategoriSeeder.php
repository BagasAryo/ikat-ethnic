<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'name' => 'Fashion Accessories',
                'slug' => 'fashion-accessories',
            ],
            [
                'name' => 'Outer Wear',
                'slug' => 'outer-wear',
            ],
            [
                'name' => 'Kimono and Robes',
                'slug' => 'kimono-and-robes',
            ],
            [
                'name' => 'Dresses',
                'slug' => 'dresses',
            ],
            [
                'name' => 'Shirts',
                'slug' => 'shirts',
            ],
            [
                'name' => 'Songket',
                'slug' => 'songket',
            ]
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
