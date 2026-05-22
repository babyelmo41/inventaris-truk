<?php

namespace Database\Seeders;

use App\Models\DetailBarangMasuk;
use Illuminate\Database\Seeder;

class DetailBarangMasukSeeder extends Seeder
{
    public function run(): void
    {
        $details = [
            // IN-2026-0515-001 (Borneo Sparepart)
            ['barang_masuk_id' => 1, 'sparepart_id' => 1, 'quantity' => 12, 'price' => 185000],  // Filter Oli Hino
            ['barang_masuk_id' => 1, 'sparepart_id' => 2, 'quantity' => 10, 'price' => 165000],  // Filter Solar

            // IN-2026-0515-002 (Chakra Parts)
            ['barang_masuk_id' => 2, 'sparepart_id' => 18, 'quantity' => 20, 'price' => 155000], // Bushing Per Daun
            ['barang_masuk_id' => 2, 'sparepart_id' => 14, 'quantity' => 40, 'price' => 25000],  // Baut Roda

            // IN-2026-0514-003 (Diesel Prima)
            ['barang_masuk_id' => 3, 'sparepart_id' => 10, 'quantity' => 8, 'price' => 325000],  // Lampu Headlamp
            ['barang_masuk_id' => 3, 'sparepart_id' => 11, 'quantity' => 6, 'price' => 850000],  // Aki Truk

            // IN-2026-0513-004 (Borneo Sparepart)
            ['barang_masuk_id' => 4, 'sparepart_id' => 13, 'quantity' => 16, 'price' => 1250000], // Ban Truk
            ['barang_masuk_id' => 4, 'sparepart_id' => 15, 'quantity' => 8, 'price' => 750000],   // Velg Truk
        ];

        foreach ($details as $detail) {
            DetailBarangMasuk::create($detail);
        }
    }
}
