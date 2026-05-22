<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBarangMasuk extends Model
{
    protected $table = 'detail_barang_masuk';

    protected $fillable = [
        'barang_masuk_id',
        'sparepart_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relasi: detail milik 1 barang masuk
    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class);
    }

    // Relasi: detail merujuk 1 sparepart
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    // Helper: hitung subtotal
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }
}
