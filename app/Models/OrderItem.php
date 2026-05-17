<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'produk_id',
        'produk_size_id',
        'product_name',
        'size_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function size()
    {
        return $this->belongsTo(ProdukSize::class, 'produk_size_id');
    }
}
