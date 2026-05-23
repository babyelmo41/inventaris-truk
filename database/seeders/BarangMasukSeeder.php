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
                'invoice_no' => 'IN-2026-0523-001',
                'date' => '2026-05-23',
                'time' => '08:30',
                'supplier_id' => 1, // PT Borneo Sparepart Mandiri
                'user_id' => 1, // Admin Gudang
                'notes' => 'Pengiriman filter oli dan kampas rem',
            ],
            [
                'invoice_no' => 'IN-2026-0522-002',
                'date' => '2026-05-22',
                'time' => '14:15',
                'supplier_id' => 2, // CV Diesel Prima
                'user_id' => 1, // Admin Gudang
                'notes' => 'Restock lampu dan aki truk',
            ],
            [
                'invoice_no' => 'IN-2026-0522-003',
                'date' => '2026-05-22',
                'time' => '09:00',
                'supplier_id' => 3, // PT Chakra Parts Support
                'user_id' => 3, // Operator Gudang
                'notes' => 'Pesanan seal dan baut',
            ],
            [
                'invoice_no' => 'IN-2026-0521-004',
                'date' => '2026-05-21',
                'time' => '16:45',
                'supplier_id' => 1, // PT Borneo Sparepart Mandiri
                'user_id' => 1, // Admin Gudang
                'notes' => 'Pengiriman ban dan velg',
            ],
        ];

        foreach ($transactions as $transaction) {
            BarangMasuk::create($transaction);
        }
    }
}
