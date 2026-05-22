<?php

namespace Database\Seeders;

use App\Models\DetailBarangKeluar;
use Illuminate\Database\Seeder;

class DetailBarangKeluarSeeder extends Seeder
{
    public function run(): void
    {
        $details = [
            // OUT-2026-0515-001 (Perawatan Truk DT-014)
            ['barang_keluar_id' => 1, 'sparepart_id' => 1, 'quantity' => 2],  // Filter Oli Hino
            ['barang_keluar_id' => 1, 'sparepart_id' => 4, 'quantity' => 4],  // Kampas Rem Belakang

            // OUT-2026-0515-002 (Perbaikan Truk DT-008)
            ['barang_keluar_id' => 2, 'sparepart_id' => 10, 'quantity' => 2], // Lampu Headlamp
            ['barang_keluar_id' => 2, 'sparepart_id' => 17, 'quantity' => 2], // Shockbreaker Belakang

            // OUT-2026-0514-003 (Perawatan Truk DT-021)
            ['barang_keluar_id' => 3, 'sparepart_id' => 2, 'quantity' => 1],  // Filter Solar
            ['barang_keluar_id' => 3, 'sparepart_id' => 3, 'quantity' => 1],  // Filter Udara
            ['barang_keluar_id' => 3, 'sparepart_id' => 12, 'quantity' => 4], // Bohlam Lampu Sen

            // OUT-2026-0513-004 (Penggantian Ban Truk DT-005)
            ['barang_keluar_id' => 4, 'sparepart_id' => 13, 'quantity' => 6], // Ban Truk
            ['barang_keluar_id' => 4, 'sparepart_id' => 14, 'quantity' => 24], // Baut Roda
        ];

        foreach ($details as $detail) {
            DetailBarangKeluar::create($detail);
        }
    }
}
