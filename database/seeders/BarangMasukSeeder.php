<?php

namespace Database\Seeders;

use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\DetailBarangMasuk;
use App\Models\DetailBarangKeluar;
use App\Models\Sparepart;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class BarangMasukSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        BarangMasuk::truncate();
        BarangKeluar::truncate();
        DetailBarangMasuk::truncate();
        DetailBarangKeluar::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $spareparts = Sparepart::all();
        $suppliers = Supplier::all();
        $users = User::all();

        if ($spareparts->isEmpty() || $suppliers->isEmpty() || $users->isEmpty()) {
            $this->command->error('Run SupplierSeeder, SparepartSeeder, and create users first.');
            return;
        }

        $startDate = \Carbon\Carbon::parse('2025-01-01');
        $endDate = \Carbon\Carbon::parse('2026-05-31');
        $currentDate = $startDate->copy();

        $bmCounter = 1;
        $bkCounter = 1;

        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');

            // === BARANG MASUK: setiap hari pasti ada ===
            $jumlahMasuk = rand(1, 2);
            for ($m = 0; $m < $jumlahMasuk; $m++) {
                $hour = rand(7, 16);
                $timeStr = sprintf('%02d:%02d:00', $hour, rand(0, 59));
                $invoiceNo = 'BM-' . $dateStr . '-' . str_pad($bmCounter++, 3, '0', STR_PAD_LEFT);

                $numItems = rand(2, 4);
                $selectedItems = $spareparts->random($numItems);

                $barangMasuk = BarangMasuk::create([
                    'invoice_no' => $invoiceNo,
                    'date' => $dateStr,
                    'time' => $timeStr,
                    'supplier_id' => $suppliers->random()->id,
                    'user_id' => $users->random()->id,
                    'notes' => '',
                ]);

                foreach ($selectedItems as $sp) {
                    $qty = rand(5, 50);
                    DetailBarangMasuk::create([
                        'barang_masuk_id' => $barangMasuk->id,
                        'sparepart_id' => $sp->id,
                        'quantity' => $qty,
                        'price' => $this->getPriceForSparepart($sp->id),
                    ]);
                }
            }

            // === BARANG KELUAR: ~60% hari ada transaksi ===
            if (rand(0, 100) < 60) {
                $keluarHour = rand(8, 15);
                $keluarTime = sprintf('%02d:%02d:00', $keluarHour, rand(0, 59));
                $referenceOut = 'BK-' . $dateStr . '-' . str_pad($bkCounter++, 3, '0', STR_PAD_LEFT);

                $numItemsOut = rand(2, 4);
                $selectedItemsOut = $spareparts->random($numItemsOut);

                $barangKeluar = BarangKeluar::create([
                    'reference_no' => $referenceOut,
                    'date' => $dateStr,
                    'time' => $keluarTime,
                    'purpose' => $this->getRandomPurpose(),
                    'notes' => '',
                    'user_id' => $users->random()->id,
                ]);

                foreach ($selectedItemsOut as $sp) {
                    $qtyOut = rand(1, 15);
                    DetailBarangKeluar::create([
                        'barang_keluar_id' => $barangKeluar->id,
                        'sparepart_id' => $sp->id,
                        'quantity' => $qtyOut,
                    ]);
                }
            }

            $currentDate->addDay();
        }

        $this->command->info('✅ Generated ' . ($bmCounter - 1) . ' BM + ' . ($bkCounter - 1) . ' BK (Jan 2025 - Mei 2026)');
    }

    private function getPriceForSparepart(int $id): int
    {
        static $map = [
            1 => 185000, 2 => 165000, 3 => 195000, 4 => 250000, 5 => 85000,
            6 => 145000, 7 => 320000, 8 => 175000, 9 => 95000, 10 => 65000,
            11 => 125000, 12 => 45000, 13 => 55000, 14 => 210000, 15 => 75000,
            16 => 380000, 17 => 155000, 18 => 90000, 19 => 420000, 20 => 135000,
        ];
        return $map[$id] ?? rand(50000, 400000);
    }

    private function getRandomPurpose(): string
    {
        $purposes = [
            'Perawatan rutin armada', 'Perbaikan unit rusak',
            'Penggantian komponen', 'Persiapan perjalanan jauh',
            'Perbaikan darurat', 'Service berkala',
            'Penggantian sparepart aus', 'Perbaikan unit kecelakaan',
            'Maintenance fleet', 'Perbaikan mesin unit',
            'Servis rem dan kanvas', 'Penggantian ban dan filter',
        ];
        return $purposes[array_rand($purposes)];
    }
}
