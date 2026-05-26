<?php

namespace Database\Seeders;

use App\Models\DetailBarangKeluar;
use Illuminate\Database\Seeder;

class DetailBarangKeluarSeeder extends Seeder
{
    public function run(): void
    {
        $details = [
            // Data awal (ID 1-4)
            ['barang_keluar_id' => 1, 'sparepart_id' => 1, 'quantity' => 2],
            ['barang_keluar_id' => 1, 'sparepart_id' => 4, 'quantity' => 4],
            ['barang_keluar_id' => 2, 'sparepart_id' => 10, 'quantity' => 2],
            ['barang_keluar_id' => 2, 'sparepart_id' => 17, 'quantity' => 2],
            ['barang_keluar_id' => 3, 'sparepart_id' => 2, 'quantity' => 1],
            ['barang_keluar_id' => 3, 'sparepart_id' => 3, 'quantity' => 1],
            ['barang_keluar_id' => 3, 'sparepart_id' => 12, 'quantity' => 4],
            ['barang_keluar_id' => 4, 'sparepart_id' => 13, 'quantity' => 6],
            ['barang_keluar_id' => 4, 'sparepart_id' => 14, 'quantity' => 24],

            // ID 5: Perawatan DT-002 (filter oli dan solar)
            ['barang_keluar_id' => 5, 'sparepart_id' => 1, 'quantity' => 2],
            ['barang_keluar_id' => 5, 'sparepart_id' => 2, 'quantity' => 2],

            // ID 6: Perbaikan DT-011 (kampas rem)
            ['barang_keluar_id' => 6, 'sparepart_id' => 4, 'quantity' => 4],
            ['barang_keluar_id' => 6, 'sparepart_id' => 5, 'quantity' => 2],

            // ID 7: Perawatan DT-017 (filter)
            ['barang_keluar_id' => 7, 'sparepart_id' => 1, 'quantity' => 1],
            ['barang_keluar_id' => 7, 'sparepart_id' => 3, 'quantity' => 1],

            // ID 8: Perbaikan DT-003 (lampu headlamp)
            ['barang_keluar_id' => 8, 'sparepart_id' => 10, 'quantity' => 2],

            // ID 9: Penggantian ban DT-009
            ['barang_keluar_id' => 9, 'sparepart_id' => 13, 'quantity' => 4],
            ['barang_keluar_id' => 9, 'sparepart_id' => 14, 'quantity' => 16],

            // ID 10: Perawatan DT-015 (filter udara dan seal)
            ['barang_keluar_id' => 10, 'sparepart_id' => 3, 'quantity' => 2],
            ['barang_keluar_id' => 10, 'sparepart_id' => 8, 'quantity' => 4],

            // ID 11: Perbaikan DT-006 (shockbreaker)
            ['barang_keluar_id' => 11, 'sparepart_id' => 17, 'quantity' => 2],

            // ID 12: Perawatan DT-020 (service 20.000 km)
            ['barang_keluar_id' => 12, 'sparepart_id' => 1, 'quantity' => 2],
            ['barang_keluar_id' => 12, 'sparepart_id' => 2, 'quantity' => 2],
            ['barang_keluar_id' => 12, 'sparepart_id' => 3, 'quantity' => 2],
            ['barang_keluar_id' => 12, 'sparepart_id' => 4, 'quantity' => 4],

            // ID 13: Perbaikan DT-012 (aki)
            ['barang_keluar_id' => 13, 'sparepart_id' => 11, 'quantity' => 2],

            // ID 14: Penggantian velg DT-007
            ['barang_keluar_id' => 14, 'sparepart_id' => 15, 'quantity' => 4],
            ['barang_keluar_id' => 14, 'sparepart_id' => 14, 'quantity' => 16],

            // ID 15: Perawatan DT-019 (bohlam)
            ['barang_keluar_id' => 15, 'sparepart_id' => 12, 'quantity' => 6],
            ['barang_keluar_id' => 15, 'sparepart_id' => 7, 'quantity' => 4],

            // ID 16: Perbaikan DT-004 (bushing)
            ['barang_keluar_id' => 16, 'sparepart_id' => 18, 'quantity' => 4],

            // ID 17: Perawatan DT-010 (kampas rem belakang)
            ['barang_keluar_id' => 17, 'sparepart_id' => 5, 'quantity' => 4],

            // ID 18: Perbaikan DT-016 (filter)
            ['barang_keluar_id' => 18, 'sparepart_id' => 1, 'quantity' => 2],
            ['barang_keluar_id' => 18, 'sparepart_id' => 2, 'quantity' => 2],

            // ID 19: Penggantian ban DT-013
            ['barang_keluar_id' => 19, 'sparepart_id' => 13, 'quantity' => 6],

            // ID 20: Perawatan DT-018 (service 5.000 km)
            ['barang_keluar_id' => 20, 'sparepart_id' => 1, 'quantity' => 2],
            ['barang_keluar_id' => 20, 'sparepart_id' => 3, 'quantity' => 1],
            ['barang_keluar_id' => 20, 'sparepart_id' => 12, 'quantity' => 2],
        ];

        foreach ($details as $detail) {
            DetailBarangKeluar::create($detail);
        }
    }
}
