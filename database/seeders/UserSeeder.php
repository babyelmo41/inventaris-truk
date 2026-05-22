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
                'name' => 'Admin Gudang',
                'email' => 'admin@gudang.com',
                'password' => Hash::make('password'),
                'role' => 'admin_gudang',
            ],
            [
                'name' => 'Pimpinan',
                'email' => 'pimpinan@perusahaan.com',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
            ],
            [
                'name' => 'Operator Gudang',
                'email' => 'operator@gudang.com',
                'password' => Hash::make('password'),
                'role' => 'admin_gudang',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
