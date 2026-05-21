<?php

namespace Database\Seeders;

use App\Models\CartItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cartItems = [
            [
                'cart_id' => 1,
                'product_id' => 1,
                'product_size_id' => 1,
                'quantity' => rand(1, 5)
            ],
            [
                'cart_id' => 1,
                'product_id' => 2,
                'product_size_id' => 2,
                'quantity' => rand(1, 5)
            ],
            [
                'cart_id' => 1,
                'product_id' => 3,
                'product_size_id' => 1,
                'quantity' => rand(1, 2)
            ],
            [
                'cart_id' => 2,
                'product_id' => 3,
                'product_size_id' => 1,
                'quantity' => rand(1, 2)
            ],
        ];

        foreach ($cartItems as $cartItem) {
            CartItem::create($cartItem);
        }
    }
}
