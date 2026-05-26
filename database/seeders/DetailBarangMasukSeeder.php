<?php

namespace Database\Seeders;

use App\Models\DetailBarangMasuk;
use Illuminate\Database\Seeder;

class DetailBarangMasukSeeder extends Seeder
{
    public function run(): void
    {
        $details = [
            // Data awal (ID 1-4)
            ['barang_masuk_id' => 1, 'sparepart_id' => 1, 'quantity' => 12, 'price' => 185000],
            ['barang_masuk_id' => 1, 'sparepart_id' => 2, 'quantity' => 10, 'price' => 165000],
            ['barang_masuk_id' => 2, 'sparepart_id' => 18, 'quantity' => 20, 'price' => 155000],
            ['barang_masuk_id' => 2, 'sparepart_id' => 14, 'quantity' => 40, 'price' => 25000],
            ['barang_masuk_id' => 3, 'sparepart_id' => 10, 'quantity' => 8, 'price' => 325000],
            ['barang_masuk_id' => 3, 'sparepart_id' => 11, 'quantity' => 6, 'price' => 850000],
            ['barang_masuk_id' => 4, 'sparepart_id' => 13, 'quantity' => 16, 'price' => 1250000],
            ['barang_masuk_id' => 4, 'sparepart_id' => 15, 'quantity' => 8, 'price' => 750000],

            // ID 5: Restock filter oli
            ['barang_masuk_id' => 5, 'sparepart_id' => 1, 'quantity' => 20, 'price' => 185000],
            ['barang_masuk_id' => 5, 'sparepart_id' => 2, 'quantity' => 15, 'price' => 165000],

            // ID 6: Kampas rem
            ['barang_masuk_id' => 6, 'sparepart_id' => 4, 'quantity' => 24, 'price' => 275000],
            ['barang_masuk_id' => 6, 'sparepart_id' => 5, 'quantity' => 18, 'price' => 245000],

            // ID 7: Bushing dan shockbreaker
            ['barang_masuk_id' => 7, 'sparepart_id' => 18, 'quantity' => 30, 'price' => 155000],
            ['barang_masuk_id' => 7, 'sparepart_id' => 17, 'quantity' => 10, 'price' => 650000],

            // ID 8: Ban truk
            ['barang_masuk_id' => 8, 'sparepart_id' => 13, 'quantity' => 20, 'price' => 1250000],

            // ID 9: Bohlam lampu
            ['barang_masuk_id' => 9, 'sparepart_id' => 12, 'quantity' => 50, 'price' => 15000],

            // ID 10: Filter solar
            ['barang_masuk_id' => 10, 'sparepart_id' => 2, 'quantity' => 18, 'price' => 165000],

            // ID 11: Seal dan ring
            ['barang_masuk_id' => 11, 'sparepart_id' => 8, 'quantity' => 40, 'price' => 18000],
            ['barang_masuk_id' => 11, 'sparepart_id' => 9, 'quantity' => 60, 'price' => 5000],

            // ID 12: Aki truk
            ['barang_masuk_id' => 12, 'sparepart_id' => 11, 'quantity' => 8, 'price' => 850000],

            // ID 13: Kampas rem depan
            ['barang_masuk_id' => 13, 'sparepart_id' => 4, 'quantity' => 20, 'price' => 275000],

            // ID 14: Baut roda
            ['barang_masuk_id' => 14, 'sparepart_id' => 14, 'quantity' => 100, 'price' => 25000],

            // ID 15: Velg truk
            ['barang_masuk_id' => 15, 'sparepart_id' => 15, 'quantity' => 12, 'price' => 750000],

            // ID 16: Filter udara
            ['barang_masuk_id' => 16, 'sparepart_id' => 3, 'quantity' => 14, 'price' => 195000],

            // ID 17: Shockbreaker
            ['barang_masuk_id' => 17, 'sparepart_id' => 17, 'quantity' => 12, 'price' => 650000],

            // ID 18: Lampu sen
            ['barang_masuk_id' => 18, 'sparepart_id' => 12, 'quantity' => 30, 'price' => 15000],

            // ID 19: Campuran
            ['barang_masuk_id' => 19, 'sparepart_id' => 1, 'quantity' => 10, 'price' => 185000],
            ['barang_masuk_id' => 19, 'sparepart_id' => 6, 'quantity' => 8, 'price' => 15000],

            // ID 20: Aki dan bohlam
            ['barang_masuk_id' => 20, 'sparepart_id' => 11, 'quantity' => 6, 'price' => 850000],
            ['barang_masuk_id' => 20, 'sparepart_id' => 12, 'quantity' => 40, 'price' => 15000],

            // ID 21: Bushing per daun
            ['barang_masuk_id' => 21, 'sparepart_id' => 18, 'quantity' => 25, 'price' => 155000],

            // ID 22: Ban dan kampas rem
            ['barang_masuk_id' => 22, 'sparepart_id' => 13, 'quantity' => 10, 'price' => 1250000],
            ['barang_masuk_id' => 22, 'sparepart_id' => 5, 'quantity' => 12, 'price' => 245000],

            // ID 23: Filter solar dan oli
            ['barang_masuk_id' => 23, 'sparepart_id' => 1, 'quantity' => 15, 'price' => 185000],
            ['barang_masuk_id' => 23, 'sparepart_id' => 2, 'quantity' => 12, 'price' => 165000],

            // ID 24: Seal klep
            ['barang_masuk_id' => 24, 'sparepart_id' => 8, 'quantity' => 30, 'price' => 18000],

            // ID 25: Velg dan baut
            ['barang_masuk_id' => 25, 'sparepart_id' => 15, 'quantity' => 6, 'price' => 750000],
            ['barang_masuk_id' => 25, 'sparepart_id' => 14, 'quantity' => 50, 'price' => 25000],

            // ID 26: Lampu headlamp
            ['barang_masuk_id' => 26, 'sparepart_id' => 10, 'quantity' => 10, 'price' => 325000],

            // ID 27: Shockbreaker depan
            ['barang_masuk_id' => 27, 'sparepart_id' => 16, 'quantity' => 8, 'price' => 580000],

            // ID 28: Filter udara tambahan
            ['barang_masuk_id' => 28, 'sparepart_id' => 3, 'quantity' => 10, 'price' => 195000],

            // ID 29: Bohlam sen dan rem
            ['barang_masuk_id' => 29, 'sparepart_id' => 12, 'quantity' => 35, 'price' => 15000],
            ['barang_masuk_id' => 29, 'sparepart_id' => 7, 'quantity' => 20, 'price' => 12000],
        ];

        foreach ($details as $detail) {
            DetailBarangMasuk::create($detail);
        }
    }
}
