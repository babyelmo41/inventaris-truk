<?php

namespace Database\Seeders;

use App\Models\BarangKeluar;
use Illuminate\Database\Seeder;

class BarangKeluarSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = [
            [
                'reference_no' => 'OUT-2026-0515-001',
                'date' => '2026-05-15',
                'time' => '09:00',
                'purpose' => 'Perawatan Truk DT-014',
                'user_id' => 1, // Admin Gudang
                'notes' => 'Penggantian rutin filter dan kampas rem',
            ],
            [
                'reference_no' => 'OUT-2026-0515-002',
                'date' => '2026-05-15',
                'time' => '11:30',
                'purpose' => 'Perbaikan Truk DT-008',
                'user_id' => 1, // Admin Gudang
                'notes' => 'Ganti lampu dan perbaiki suspensi',
            ],
            [
                'reference_no' => 'OUT-2026-0514-003',
                'date' => '2026-05-14',
                'time' => '13:15',
                'purpose' => 'Perawatan Berkala Truk DT-021',
                'user_id' => 3, // Operator Gudang
                'notes' => 'Service rutin 10.000 km',
            ],
            [
                'reference_no' => 'OUT-2026-0513-004',
                'date' => '2026-05-13',
                'time' => '15:45',
                'purpose' => 'Penggantian Ban Truk DT-005',
                'user_id' => 1, // Admin Gudang
                'notes' => 'Ban aus, perlu diganti',
            ],
        ];

        foreach ($transactions as $transaction) {
            BarangKeluar::create($transaction);
        }
    }
}
