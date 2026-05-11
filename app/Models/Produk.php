<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'kategori_id',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function images()
    {
        return $this->hasMany(ProdukImage::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProdukSize::class);
    }
}
