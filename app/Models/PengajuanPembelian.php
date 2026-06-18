<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPembelian extends Model
{
    protected $table = 'pengajuan_pembelian';

    protected $fillable = [
        'ajuan_no',
        'date',
        'user_id',
        'approved_by',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(DetailPengajuanPembelian::class);
    }
}
