<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi: 1 user bisa input banyak barang masuk
    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }

    // Relasi: 1 user bisa input banyak barang keluar
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class);
    }
}
