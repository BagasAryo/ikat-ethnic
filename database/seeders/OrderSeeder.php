<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                'user_id' => 2,
                'order_number' => 'ORD-260513-001',
                'subtotal' => 250000,
                'shipping_cost' => 15000,
                'total_amount' => 265000,
                'status' => 'Pending',
            ],
            [
                'user_id' => 3,
                'order_number' => 'ORD-260513-002',
                'subtotal' => 300000,
                'shipping_cost' => 15000,
                'total_amount' => 315000,
                'status' => 'Processing',
            ]
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
