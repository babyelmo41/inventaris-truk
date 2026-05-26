<?php

namespace Database\Seeders;

use App\Models\BarangKeluar;
use Illuminate\Database\Seeder;

class BarangKeluarSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = [
            // Data awal (4)
            ['reference_no' => 'OUT-2026-0523-001', 'date' => '2026-05-23', 'time' => '10:00', 'purpose' => 'Perawatan Truk DT-014', 'user_id' => 1, 'notes' => 'Penggantian rutin filter dan kampas rem'],
            ['reference_no' => 'OUT-2026-0522-002', 'date' => '2026-05-22', 'time' => '11:30', 'purpose' => 'Perbaikan Truk DT-008', 'user_id' => 1, 'notes' => 'Ganti lampu dan perbaiki suspensi'],
            ['reference_no' => 'OUT-2026-0521-003', 'date' => '2026-05-21', 'time' => '13:45', 'purpose' => 'Perawatan Berkala Truk DT-021', 'user_id' => 3, 'notes' => 'Service rutin 10.000 km'],
            ['reference_no' => 'OUT-2026-0520-004', 'date' => '2026-05-20', 'time' => '15:20', 'purpose' => 'Penggantian Ban Truk DT-005', 'user_id' => 1, 'notes' => 'Ban aus, perlu diganti'],

            // Tambah: 20-30 Mei 2026 (18 transaksi)
            ['reference_no' => 'OUT-2026-0530-005', 'date' => '2026-05-30', 'time' => '08:45', 'purpose' => 'Perawatan Truk DT-002', 'user_id' => 1, 'notes' => 'Ganti filter oli dan solar'],
            ['reference_no' => 'OUT-2026-0529-006', 'date' => '2026-05-29', 'time' => '10:15', 'purpose' => 'Perbaikan Truk DT-011', 'user_id' => 3, 'notes' => 'Kampas rem aus, ganti baru'],
            ['reference_no' => 'OUT-2026-0529-007', 'date' => '2026-05-29', 'time' => '14:30', 'purpose' => 'Perawatan Truk DT-017', 'user_id' => 1, 'notes' => 'Service rutin penggantian filter'],
            ['reference_no' => 'OUT-2026-0528-008', 'date' => '2026-05-28', 'time' => '09:00', 'purpose' => 'Perbaikan Truk DT-003', 'user_id' => 1, 'notes' => 'Ganti lampu headlamp pecah'],
            ['reference_no' => 'OUT-2026-0527-009', 'date' => '2026-05-27', 'time' => '11:45', 'purpose' => 'Penggantian Ban Truk DT-009', 'user_id' => 3, 'notes' => 'Ban bocor, ganti ban cadangan'],
            ['reference_no' => 'OUT-2026-0526-010', 'date' => '2026-05-26', 'time' => '13:00', 'purpose' => 'Perawatan Truk DT-015', 'user_id' => 1, 'notes' => 'Ganti filter udara dan seal'],
            ['reference_no' => 'OUT-2026-0525-011', 'date' => '2026-05-25', 'time' => '15:30', 'purpose' => 'Perbaikan Truk DT-006', 'user_id' => 1, 'notes' => 'Shockbreaker bocor, perlu ganti'],
            ['reference_no' => 'OUT-2026-0524-012', 'date' => '2026-05-24', 'time' => '08:30', 'purpose' => 'Perawatan Berkala Truk DT-020', 'user_id' => 3, 'notes' => 'Service 20.000 km'],
            ['reference_no' => 'OUT-2026-0523-013', 'date' => '2026-05-23', 'time' => '16:00', 'purpose' => 'Perbaikan Truk DT-012', 'user_id' => 1, 'notes' => 'Aki soak, ganti baru'],
            ['reference_no' => 'OUT-2026-0522-014', 'date' => '2026-05-22', 'time' => '09:15', 'purpose' => 'Penggantian Velg Truk DT-007', 'user_id' => 1, 'notes' => 'Velg retak, ganti untuk keselamatan'],
            ['reference_no' => 'OUT-2026-0520-015', 'date' => '2026-05-20', 'time' => '12:00', 'purpose' => 'Perawatan Truk DT-019', 'user_id' => 3, 'notes' => 'Ganti bohlam sen dan rem'],
            ['reference_no' => 'OUT-2026-0518-016', 'date' => '2026-05-18', 'time' => '10:30', 'purpose' => 'Perbaikan Truk DT-004', 'user_id' => 1, 'notes' => 'Bushings aus, ganti baru'],
            ['reference_no' => 'OUT-2026-0516-017', 'date' => '2026-05-16', 'time' => '14:00', 'purpose' => 'Perawatan Truk DT-010', 'user_id' => 1, 'notes' => 'Penggantian kampas rem belakang'],
            ['reference_no' => 'OUT-2026-0514-018', 'date' => '2026-05-14', 'time' => '08:00', 'purpose' => 'Perbaikan Truk DT-016', 'user_id' => 3, 'notes' => 'Ganti filter solar dan oli'],
            ['reference_no' => 'OUT-2026-0512-019', 'date' => '2026-05-12', 'time' => '11:00', 'purpose' => 'Penggantian Ban Truk DT-013', 'user_id' => 1, 'notes' => 'Ban aus karena perjalanan jauh'],
            ['reference_no' => 'OUT-2026-0510-020', 'date' => '2026-05-10', 'time' => '15:45', 'purpose' => 'Perawatan Berkala Truk DT-018', 'user_id' => 1, 'notes' => 'Service rutin 5.000 km'],
        ];

        foreach ($transactions as $transaction) {
            BarangKeluar::create($transaction);
        }
    }
}
