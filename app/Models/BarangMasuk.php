<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';

    protected $fillable = [
        'invoice_no',
        'date',
        'time',
        'supplier_id',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relasi: barang masuk milik 1 supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi: barang masuk diinput oleh 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: barang masuk punya banyak detail item
    public function details()
    {
        return $this->hasMany(DetailBarangMasuk::class);
    }
}
