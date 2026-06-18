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
        'notes',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPembelian::class, 'pengajuan_pembelian_id');
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}
