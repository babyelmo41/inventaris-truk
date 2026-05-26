<?php

namespace Database\Seeders;

use App\Models\BarangMasuk;
use Illuminate\Database\Seeder;

class BarangMasukSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = [
            // Data awal (4)
            ['invoice_no' => 'IN-2026-0523-001', 'date' => '2026-05-23', 'time' => '08:30', 'supplier_id' => 1, 'user_id' => 1, 'notes' => 'Pengiriman filter oli dan kampas rem'],
            ['invoice_no' => 'IN-2026-0522-002', 'date' => '2026-05-22', 'time' => '14:15', 'supplier_id' => 2, 'user_id' => 1, 'notes' => 'Restock lampu dan aki truk'],
            ['invoice_no' => 'IN-2026-0522-003', 'date' => '2026-05-22', 'time' => '09:00', 'supplier_id' => 3, 'user_id' => 3, 'notes' => 'Pesanan seal dan baut'],
            ['invoice_no' => 'IN-2026-0521-004', 'date' => '2026-05-21', 'time' => '16:45', 'supplier_id' => 1, 'user_id' => 1, 'notes' => 'Pengiriman ban dan velg'],

            // Tambah: 20-30 Mei 2026 (25 transaksi)
            ['invoice_no' => 'IN-2026-0530-005', 'date' => '2026-05-30', 'time' => '09:15', 'supplier_id' => 1, 'user_id' => 1, 'notes' => 'Restock filter oli bulanan'],
            ['invoice_no' => 'IN-2026-0530-006', 'date' => '2026-05-30', 'time' => '14:00', 'supplier_id' => 2, 'user_id' => 3, 'notes' => 'Pesanan kampas rem tambahan'],
            ['invoice_no' => 'IN-2026-0529-007', 'date' => '2026-05-29', 'time' => '10:30', 'supplier_id' => 3, 'user_id' => 1, 'notes' => 'Pengiriman bushing dan shockbreaker'],
            ['invoice_no' => 'IN-2026-0529-008', 'date' => '2026-05-29', 'time' => '15:45', 'supplier_id' => 1, 'user_id' => 1, 'notes' => 'Restock ban truk'],
            ['invoice_no' => 'IN-2026-0528-009', 'date' => '2026-05-28', 'time' => '08:00', 'supplier_id' => 2, 'user_id' => 3, 'notes' => 'Pesanan bohlam lampu'],
            ['invoice_no' => 'IN-2026-0528-010', 'date' => '2026-05-28', 'time' => '11:20', 'supplier_id' => 1, 'user_id' => 1, 'notes' => 'Pengiriman filter solar'],
            ['invoice_no' => 'IN-2026-0527-011', 'date' => '2026-05-27', 'time' => '13:10', 'supplier_id' => 3, 'user_id' => 1, 'notes' => 'Restock seal dan ring'],
            ['invoice_no' => 'IN-2026-0527-012', 'date' => '2026-05-27', 'time' => '16:30', 'supplier_id' => 2, 'user_id' => 3, 'notes' => 'Pesanan aki truk cadangan'],
            ['invoice_no' => 'IN-2026-0526-013', 'date' => '2026-05-26', 'time' => '09:45', 'supplier_id' => 1, 'user_id' => 1, 'notes' => 'Pengiriman kampas rem depan'],
            ['invoice_no' => 'IN-2026-0526-014', 'date' => '2026-05-26', 'time' => '14:50', 'supplier_id' => 3, 'user_id' => 1, 'notes' => 'Restock baut roda'],
            ['invoice_no' => 'IN-2026-0525-015', 'date' => '2026-05-25', 'time' => '08:20', 'supplier_id' => 2, 'user_id' => 3, 'notes' => 'Pesanan velg truk'],
            ['invoice_no' => 'IN-2026-0525-016', 'date' => '2026-05-25', 'time' => '12:00', 'supplier_id' => 1, 'user_id' => 1, 'notes' => 'Pengiriman filter udara'],
            ['invoice_no' => 'IN-2026-0524-017', 'date' => '2026-05-24', 'time' => '10:15', 'supplier_id' => 3, 'user_id' => 1, 'notes' => 'Restock shockbreaker'],
            ['invoice_no' => 'IN-2026-0524-018', 'date' => '2026-05-24', 'time' => '15:30', 'supplier_id' => 2, 'user_id' => 3, 'notes' => 'Pesanan lampu sen'],
            ['invoice_no' => 'IN-2026-0523-019', 'date' => '2026-05-23', 'time' => '11:00', 'supplier_id' => 1, 'user_id' => 1, 'notes' => 'Pengiriman sparepart campuran'],
            ['invoice_no' => 'IN-2026-0520-020', 'date' => '2026-05-20', 'time' => '09:30', 'supplier_id' => 2, 'user_id' => 1, 'notes' => 'Restock aki dan bohlam'],
            ['invoice_no' => 'IN-2026-0519-021', 'date' => '2026-05-19', 'time' => '13:45', 'supplier_id' => 3, 'user_id' => 3, 'notes' => 'Pesanan bushing per daun'],
            ['invoice_no' => 'IN-2026-0518-022', 'date' => '2026-05-18', 'time' => '08:45', 'supplier_id' => 1, 'user_id' => 1, 'notes' => 'Pengiriman ban dan kampas rem'],
            ['invoice_no' => 'IN-2026-0517-023', 'date' => '2026-05-17', 'time' => '16:00', 'supplier_id' => 2, 'user_id' => 1, 'notes' => 'Restock filter solar dan oli'],
            ['invoice_no' => 'IN-2026-0516-024', 'date' => '2026-05-16', 'time' => '10:00', 'supplier_id' => 3, 'user_id' => 3, 'notes' => 'Pesanan seal klep'],
            ['invoice_no' => 'IN-2026-0515-025', 'date' => '2026-05-15', 'time' => '14:30', 'supplier_id' => 1, 'user_id' => 1, 'notes' => 'Pengiriman velg dan baut roda'],
            ['invoice_no' => 'IN-2026-0514-026', 'date' => '2026-05-14', 'time' => '09:00', 'supplier_id' => 2, 'user_id' => 1, 'notes' => 'Restock lampu headlamp'],
            ['invoice_no' => 'IN-2026-0513-027', 'date' => '2026-05-13', 'time' => '11:30', 'supplier_id' => 3, 'user_id' => 3, 'notes' => 'Pesanan shockbreaker depan'],
            ['invoice_no' => 'IN-2026-0512-028', 'date' => '2026-05-12', 'time' => '15:15', 'supplier_id' => 1, 'user_id' => 1, 'notes' => 'Pengiriman filter udara tambahan'],
            ['invoice_no' => 'IN-2026-0510-029', 'date' => '2026-05-10', 'time' => '08:15', 'supplier_id' => 2, 'user_id' => 1, 'notes' => 'Restock bohlam sen dan rem'],
        ];

        foreach ($transactions as $transaction) {
            BarangMasuk::create($transaction);
        }
    }
}
