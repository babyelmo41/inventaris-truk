<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use Illuminate\Database\Seeder;

class SparepartSeeder extends Seeder
{
    public function run(): void
    {
        $spareparts = [
            // Filter (category_id: 1)
            ['code' => 'SP-001', 'name' => 'Filter Oli Hino 500', 'category_id' => 1, 'supplier_id' => 1, 'stock' => 48, 'min_stock' => 12, 'unit' => 'pcs'],
            ['code' => 'SP-002', 'name' => 'Filter Solar Hino 500', 'category_id' => 1, 'supplier_id' => 1, 'stock' => 35, 'min_stock' => 10, 'unit' => 'pcs'],
            ['code' => 'SP-003', 'name' => 'Filter Udara Hino 500', 'category_id' => 1, 'supplier_id' => 2, 'stock' => 20, 'min_stock' => 8, 'unit' => 'pcs'],

            // Rem (category_id: 2)
            ['code' => 'SP-004', 'name' => 'Kampas Rem Belakang', 'category_id' => 2, 'supplier_id' => 2, 'stock' => 8, 'min_stock' => 10, 'unit' => 'set'],
            ['code' => 'SP-005', 'name' => 'Kampas Rem Depan', 'category_id' => 2, 'supplier_id' => 2, 'stock' => 15, 'min_stock' => 10, 'unit' => 'set'],
            ['code' => 'SP-006', 'name' => 'Master Rem Utama', 'category_id' => 2, 'supplier_id' => 3, 'stock' => 5, 'min_stock' => 3, 'unit' => 'pcs'],

            // Mesin (category_id: 3)
            ['code' => 'SP-007', 'name' => 'Piston Set Hino 500', 'category_id' => 3, 'supplier_id' => 1, 'stock' => 6, 'min_stock' => 4, 'unit' => 'set'],
            ['code' => 'SP-008', 'name' => 'Ring Piston Hino 500', 'category_id' => 3, 'supplier_id' => 1, 'stock' => 12, 'min_stock' => 6, 'unit' => 'set'],
            ['code' => 'SP-009', 'name' => 'Gasket Set Mesin', 'category_id' => 3, 'supplier_id' => 3, 'stock' => 10, 'min_stock' => 5, 'unit' => 'set'],

            // Kelistrikan (category_id: 4)
            ['code' => 'SP-010', 'name' => 'Lampu Headlamp Truk', 'category_id' => 4, 'supplier_id' => 2, 'stock' => 0, 'min_stock' => 6, 'unit' => 'pcs'],
            ['code' => 'SP-011', 'name' => 'Aki Truk 12V', 'category_id' => 4, 'supplier_id' => 2, 'stock' => 14, 'min_stock' => 5, 'unit' => 'pcs'],
            ['code' => 'SP-012', 'name' => 'Bohlam Lampu Sen', 'category_id' => 4, 'supplier_id' => 3, 'stock' => 50, 'min_stock' => 20, 'unit' => 'pcs'],

            // Ban (category_id: 5)
            ['code' => 'SP-013', 'name' => 'Ban Truk 10.00-20', 'category_id' => 5, 'supplier_id' => 1, 'stock' => 24, 'min_stock' => 8, 'unit' => 'pcs'],
            ['code' => 'SP-014', 'name' => 'Baut Roda Truk', 'category_id' => 5, 'supplier_id' => 3, 'stock' => 120, 'min_stock' => 40, 'unit' => 'pcs'],
            ['code' => 'SP-015', 'name' => 'Velg Truk Ring 20', 'category_id' => 5, 'supplier_id' => 1, 'stock' => 10, 'min_stock' => 4, 'unit' => 'pcs'],

            // Suspensi (category_id: 6)
            ['code' => 'SP-016', 'name' => 'Per Daun Belakang', 'category_id' => 6, 'supplier_id' => 3, 'stock' => 8, 'min_stock' => 4, 'unit' => 'set'],
            ['code' => 'SP-017', 'name' => 'Shockbreaker Belakang', 'category_id' => 6, 'supplier_id' => 2, 'stock' => 6, 'min_stock' => 4, 'unit' => 'pcs'],
            ['code' => 'SP-018', 'name' => 'Bushing Per Daun', 'category_id' => 6, 'supplier_id' => 3, 'stock' => 30, 'min_stock' => 10, 'unit' => 'pcs'],
        ];

        foreach ($spareparts as $sparepart) {
            Sparepart::create($sparepart);
        }
    }
}
