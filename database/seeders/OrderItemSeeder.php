<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderItems = [
            [
                'order_id' => 1,
                'produk_id' => 1,
                'produk_size_id' => 1,
                'quantity' => 1,
                'unit_price' => 250000,
                'subtotal' => 250000,
            ],
            [
                'order_id' => 2,
                'produk_id' => 2,
                'produk_size_id' => 2,
                'quantity' => 2,
                'unit_price' => 100000,
                'subtotal' => 200000,
            ],
        ];

        foreach ($orderItems as $orderItem) {
            OrderItem::create($orderItem);
        }
    }
}
