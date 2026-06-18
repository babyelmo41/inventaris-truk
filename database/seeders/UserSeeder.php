<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gudang.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Pimpinan',
                'email' => 'pimpinan@perusahaan.com',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
            ],
            [
                'name' => 'Izza',
                'email' => 'izza@inventaris.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
            ],
            [
                'name' => 'Yanor',
                'email' => 'yanor@inventaris.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
            ],
            [
                'name' => 'Hafidz',
                'email' => 'hafidz@inventaris.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
            ],
            [
                'name' => 'Ruli',
                'email' => 'ruli@inventaris.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
