<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produk1 = Product::with('sizes')->find(1);
        $size1 = $produk1->sizes->first();

        $produk2 = Product::with('sizes')->find(2);
        $size2 = $produk2->sizes->first();

        $produk3 = Product::with('sizes')->find(3);
        $size3 = $produk3->sizes->first();

        $orderItems = [
            // Order 1 Items (Total Subtotal: Rp250.000)
            [
                'order_id' => 1,
                'product_id' => $produk1->id,
                'product_size_id' => $size1->id,
                'product_name' => $produk1->name,
                'product_size' => $size1->name,
                'quantity' => 1,
                'unit_price' => $produk1->price,
                'subtotal' => $produk1->price * 1,
            ],
            [
                'order_id' => 1,
                'product_id' => $produk3->id,
                'product_size_id' => $size3->id,
                'product_name' => $produk3->name,
                'product_size' => $size3->name,
                'quantity' => 1,
                'unit_price' => $produk3->price,
                'subtotal' => $produk3->price * 1,
            ],

            // Order 2 Items (Total Subtotal: Rp300.000)
            [
                'order_id' => 2,
                'product_id' => $produk2->id,
                'product_size_id' => $size2->id,
                'product_name' => $produk2->name,
                'product_size' => $size2->name,
                'quantity' => 1,
                'unit_price' => $produk2->price,
                'subtotal' => $produk2->price * 1,
            ],
            [
                'order_id' => 2,
                'product_id' => $produk1->id,
                'product_size_id' => $size1->id,
                'product_name' => $produk1->name,
                'product_size' => $size1->name,
                'quantity' => 1,
                'unit_price' => $produk1->price,
                'subtotal' => $produk1->price * 1,
            ],
        ];

        foreach ($orderItems as $orderItem) {
            OrderItem::create($orderItem);
        }
    }
}
