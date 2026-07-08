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
        'before_photo',
        'after_photo',
        'item_status',
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

    // Accessor: URL foto before
    public function getBeforePhotoUrlAttribute(): ?string
    {
        return $this->before_photo ? asset('storage/' . $this->before_photo) : null;
    }

    // Accessor: URL foto after
    public function getAfterPhotoUrlAttribute(): ?string
    {
        return $this->after_photo ? asset('storage/' . $this->after_photo) : null;
    }

    // Helper: apakah item sudah lengkap foto after-nya?
    public function hasAfterPhoto(): bool
    {
        return !empty($this->after_photo);
    }

    // Helper: badge status
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->item_status) {
            'completed' => 'success',
            'processed' => 'warning',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->item_status) {
            'completed' => 'Selesai',
            'processed' => 'Diproses',
            default => 'Menunggu',
        };
    }
}
