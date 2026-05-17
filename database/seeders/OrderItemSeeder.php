<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produk = Produk::find(1);
        $orderItems = [
            [
                'order_id' => 1,
                'produk_id' => $produk->id,
                'produk_size_id' => 1,
                'quantity' => 1,
                'unit_price' => $produk->price,
                'subtotal' => $produk->price * 1,
            ],
            [
                'order_id' => 2,
                'produk_id' => $produk->id,
                'produk_size_id' => 2,
                'quantity' => 2,
                'unit_price' => $produk->price,
                'subtotal' => $produk->price * 2,
            ],
        ];

        foreach ($orderItems as $orderItem) {
            OrderItem::create($orderItem);
        }
    }
}
