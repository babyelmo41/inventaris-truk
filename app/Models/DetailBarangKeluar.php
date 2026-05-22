<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBarangKeluar extends Model
{
    protected $table = 'detail_barang_keluar';

    protected $fillable = [
        'barang_keluar_id',
        'sparepart_id',
        'quantity',
    ];

    // Relasi: detail milik 1 barang keluar
    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    // Relasi: detail merujuk 1 sparepart
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}
