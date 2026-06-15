<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city_id',
        'shipping_city_name',
        'shipping_province',
        'shipping_courier',
        'shipping_service',
        'shipping_etd',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
