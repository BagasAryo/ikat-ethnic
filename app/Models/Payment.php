<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'status',
        'snap_token',
        'transaction_id',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getSpecificChannelAttribute()
    {
        $response = $this->raw_response;
        
        if (!$response || !is_array($response)) {
            return strtoupper(str_replace('_', ' ', $this->payment_method));
        }

        $type = $response['payment_type'] ?? $this->payment_method;

        if ($type === 'bank_transfer') {
            if (isset($response['va_numbers'][0]['bank'])) {
                return strtoupper($response['va_numbers'][0]['bank']) . ' VA';
            }
            if (isset($response['permata_va_number'])) {
                return 'PERMATA VA';
            }
        } elseif ($type === 'echannel') {
            return 'MANDIRI VA';
        } elseif ($type === 'cstore') {
            if (isset($response['store'])) {
                return strtoupper($response['store']);
            }
        }

        return strtoupper(str_replace('_', ' ', $type));
    }
}
