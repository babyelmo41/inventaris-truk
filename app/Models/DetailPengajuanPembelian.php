<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengajuanPembelian extends Model
{
    protected $table = 'detail_pengajuan_pembelian';

    protected $fillable = [
        'pengajuan_pembelian_id',
        'sparepart_id',
        'quantity',
        'price',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPembelian::class, 'pengajuan_pembelian_id');
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    // Hitung total per item
    public function getTotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    // Format harga satuan
    public function getPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Format total
    public function getTotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}
