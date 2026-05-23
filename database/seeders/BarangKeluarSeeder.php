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
                'reference_no' => 'OUT-2026-0523-001',
                'date' => '2026-05-23',
                'time' => '10:00',
                'purpose' => 'Perawatan Truk DT-014',
                'user_id' => 1, // Admin Gudang
                'notes' => 'Penggantian rutin filter dan kampas rem',
            ],
            [
                'reference_no' => 'OUT-2026-0522-002',
                'date' => '2026-05-22',
                'time' => '11:30',
                'purpose' => 'Perbaikan Truk DT-008',
                'user_id' => 1, // Admin Gudang
                'notes' => 'Ganti lampu dan perbaiki suspensi',
            ],
            [
                'reference_no' => 'OUT-2026-0521-003',
                'date' => '2026-05-21',
                'time' => '13:45',
                'purpose' => 'Perawatan Berkala Truk DT-021',
                'user_id' => 3, // Operator Gudang
                'notes' => 'Service rutin 10.000 km',
            ],
            [
                'reference_no' => 'OUT-2026-0520-004',
                'date' => '2026-05-20',
                'time' => '15:20',
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
