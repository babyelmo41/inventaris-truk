<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';

    protected $fillable = [
        'reference_no',
        'date',
        'time',
        'purpose',
        'user_id',
        'notes',
        'requested_by',
        'truck_name',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relasi: barang keluar diinput oleh 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: barang keluar punya banyak detail item
    public function details()
    {
        return $this->hasMany(DetailBarangKeluar::class);
    }

    // Relasi: yang meminta barang keluar (karyawan/mekanik)
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // Helper: apakah semua item sudah punya foto after?
    public function allItemsHaveAfterPhoto(): bool
    {
        return $this->details()->whereNull('after_photo')->count() === 0;
    }

    // Helper: jumlah item yang sudah punya foto after
    public function completedItemsCount(): int
    {
        return $this->details()->whereNotNull('after_photo')->count();
    }

    // Helper: total items
    public function totalItemsCount(): int
    {
        return $this->details()->count();
    }

    // Helper: progress string "2/3"
    public function completionProgress(): string
    {
        return $this->completedItemsCount() . '/' . $this->totalItemsCount();
    }
}
