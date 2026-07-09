<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPembelian extends Model
{
    protected $table = 'pengajuan_pembelian';

    protected $fillable = [
        'ajuan_no',
        'date',
        'time',
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

    // Total estimasi harga semua item
    public function getTotalEstimasiAttribute(): float
    {
        return $this->details->sum(fn ($d) => $d->quantity * $d->price);
    }

    // Format total estimasi
    public function getTotalEstimasiFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_estimasi, 0, ',', '.');
    }
}
