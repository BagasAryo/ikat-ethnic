<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorys = [
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

        foreach ($categorys as $category) {
            Category::create($category);
        }
    }
}
