<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';

    protected $fillable = [
        'reference_no',
        'date',
        'purpose',
        'user_id',
        'notes',
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
}
