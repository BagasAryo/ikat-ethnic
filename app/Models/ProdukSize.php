<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukSize extends Model
{
    protected $fillable = [
        'name',
        'stock',
        'produk_id'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
