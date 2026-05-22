<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT Borneo Sparepart Mandiri',
                'address' => 'Jl. A. Yani Km 32, Banjarbaru',
                'phone' => '0511-674223',
                'email' => 'sales@borneosparepart.co.id',
            ],
            [
                'name' => 'CV Diesel Prima',
                'address' => 'Jl. Trikora No. 18, Banjarbaru',
                'phone' => '0511-732114',
                'email' => 'admin@dieselprima.id',
            ],
            [
                'name' => 'PT Chakra Parts Support',
                'address' => 'Jl. Gubernur Syarkawi, Kabupaten Banjar',
                'phone' => '0511-881029',
                'email' => 'support@chakraparts.id',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
