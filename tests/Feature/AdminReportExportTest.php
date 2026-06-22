<?php

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows admin to export sales report as csv stream', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    // Create category and product manually (no factory exists for Product)
    $category = Category::create([
        'name' => 'Tenun Sumba',
        'slug' => 'tenun-sumba',
    ]);

    $product = Product::create([
        'name' => 'Kemeja Tenun Sumba',
        'slug' => 'kemeja-tenun-sumba',
        'description' => 'Baju tenun asli',
        'price' => 250000,
        'category_id' => $category->id,
    ]);

    $size = ProductSize::create([
        'product_id' => $product->id,
        'name' => 'L',
        'stock' => 10
    ]);

    // Create an order
    $customer = User::factory()->create(['role' => 'user']);
    $order = Order::create([
        'user_id' => $customer->id,
        'order_number' => 'ORD-TEST-999',
        'subtotal' => 250000,
        'total_amount' => 265000,
        'shipping_cost' => 15000,
        'shipping_courier' => 'jne',
        'shipping_service' => 'REG',
        'shipping_name' => 'Budi',
        'shipping_phone' => '0812345678',
        'shipping_address' => 'Jl. Merdeka No. 10',
        'shipping_city_name' => 'Jakarta',
        'shipping_province' => 'DKI Jakarta',
        'status' => 'Completed',
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_size_id' => $size->id,
        'product_name' => $product->name,
        'product_size' => $size->name,
        'quantity' => 1,
        'unit_price' => 250000,
        'subtotal' => 250000,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.export', [
        'date_from' => now()->subDay()->format('Y-m-d'),
        'date_to' => now()->addDay()->format('Y-m-d'),
    ]));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/csv; charset=utf-8');
    $response->assertHeader('Content-Disposition', 'attachment; filename=Laporan_Penjualan_' . now()->subDay()->format('Y-m-d') . '_to_' . now()->addDay()->format('Y-m-d') . '.csv');
    
    // Check if the stream contains critical report strings
    ob_start();
    $response->sendContent();
    $content = ob_get_clean();

    expect($content)->toContain('LAPORAN PENJUALAN - IKAT ETHNIC');
    expect($content)->toContain('RINGKASAN');
    expect($content)->toContain('ORD-TEST-999');
    expect($content)->toContain('Kemeja Tenun Sumba (L) x1');
});
