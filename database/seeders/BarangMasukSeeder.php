<?php

namespace Database\Seeders;

use App\Models\BarangMasuk;
use Illuminate\Database\Seeder;

class BarangMasukSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = [
            [
                'invoice_no' => 'IN-2026-0515-001',
                'date' => '2026-05-15',
                'time' => '08:30',
                'supplier_id' => 1, // PT Borneo Sparepart Mandiri
                'user_id' => 1, // Admin Gudang
                'notes' => 'Pengiriman rutin bulanan',
            ],
            [
                'invoice_no' => 'IN-2026-0515-002',
                'date' => '2026-05-15',
                'time' => '10:15',
                'supplier_id' => 3, // PT Chakra Parts Support
                'user_id' => 1, // Admin Gudang
                'notes' => 'Restock seal dan baut',
            ],
            [
                'invoice_no' => 'IN-2026-0514-003',
                'date' => '2026-05-14',
                'time' => '14:00',
                'supplier_id' => 2, // CV Diesel Prima
                'user_id' => 1, // Admin Gudang
                'notes' => 'Pesanan lampu dan aki',
            ],
            [
                'invoice_no' => 'IN-2026-0513-004',
                'date' => '2026-05-13',
                'time' => '09:45',
                'supplier_id' => 1, // PT Borneo Sparepart Mandiri
                'user_id' => 3, // Operator Gudang
                'notes' => 'Pengiriman ban dan velg',
            ],
        ];

        foreach ($transactions as $transaction) {
            BarangMasuk::create($transaction);
        }
    }
}
