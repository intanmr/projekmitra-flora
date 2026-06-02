<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Product extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_produk',
        'kategori',
        'harga',
        'stok',
        'tanggal_masuk',
        'gambar',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getStatusAttribute(): string
    {
        if ($this->stok <= 0) {
            return 'habis';
        }

        if ($this->stok <= 3) {
            return 'hampir_habis';
        }

        return 'tersedia';
    }
}