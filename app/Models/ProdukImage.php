<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukImage extends Model
{
    protected $fillable = [
        'image_url',
        'is_thumbnail',
        'produk_id',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
