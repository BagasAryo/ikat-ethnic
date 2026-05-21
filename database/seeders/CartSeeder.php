<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carts = [
            ['user_id' => 2],
            ['user_id' => 3],
        ];

        foreach ($carts as $cart) {
            Cart::create($cart);
        }
    }
}
