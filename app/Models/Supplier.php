<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
    ];

    // Relasi: 1 supplier punya banyak sparepart
    public function spareparts()
    {
        return $this->hasMany(Sparepart::class);
    }

    // Relasi: 1 supplier bisa jadi pemasok di banyak barang masuk
    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }
}
