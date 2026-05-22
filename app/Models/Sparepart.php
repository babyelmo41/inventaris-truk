<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'supplier_id',
        'stock',
        'min_stock',
        'unit',
    ];

    // Relasi: sparepart milik 1 kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: sparepart milik 1 supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi: sparepart bisa ada di banyak detail barang masuk
    public function detailMasuk()
    {
        return $this->hasMany(DetailBarangMasuk::class);
    }

    // Relasi: sparepart bisa ada di banyak detail barang keluar
    public function detailKeluar()
    {
        return $this->hasMany(DetailBarangKeluar::class);
    }

    // Helper: cek status stok
    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'Habis';
        }
        if ($this->stock <= $this->min_stock) {
            return 'Hampir Habis';
        }
        return 'Aman';
    }
}
