<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Penerapan Mass Assignment untuk menentukan atribut yang dapat diisi secara otomatis.
    protected $fillable = [
        'user_id',
        'product_id',
        'product_snapshot',
        'harga_satuan',
        'jumlah',
        'total_harga',
        'nama_customer',
        'alamat',
        'email',
        'whatsapp',
        'metode_pembayaran',
        'catatan',
        'bukti_pembayaran',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}