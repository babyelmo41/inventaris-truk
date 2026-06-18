<?php

namespace Database\Seeders;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\DetailPengajuanPembelian;
use App\Models\PengajuanPembelian;
use App\Models\Sparepart;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NewFeaturesSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // 1. TAMBAH USER KARYAWAN
        // =============================================
        $karyawanData = [
            ['name' => 'Izza',    'email' => 'izza@inventaris.com',    'role' => 'karyawan'],
            ['name' => 'Yanor',   'email' => 'yanor@inventaris.com',   'role' => 'karyawan'],
            ['name' => 'Hafidz',  'email' => 'hafidz@inventaris.com',  'role' => 'karyawan'],
            ['name' => 'Ruli',    'email' => 'ruli@inventaris.com',    'role' => 'karyawan'],
        ];

        foreach ($karyawanData as $k) {
            User::firstOrCreate(
                ['email' => $k['email']],
                [...$k, 'password' => Hash::make('password')]
            );
        }
        $this->command->info('✅ Karyawan users created');

        $admin = User::where('role', 'admin')->first();
        $pimpinan = User::where('role', 'pimpinan')->first();
        $karyawans = User::where('role', 'karyawan')->get();

        // =============================================
        // 2. BACKFILL barang_masuk.approved_by
        // =============================================
        $updated = BarangMasuk::whereNull('approved_by')
            ->update(['approved_by' => $pimpinan->id]);
        $this->command->info("✅ barang_masuk.approved_by backfilled: {$updated} rows");

        // =============================================
        // 3. BACKFILL barang_keluar.requested_by & truck_name
        // =============================================
        $truckNames = [
            'Hino 500 #01', 'Hino 500 #02', 'Hino 500 #03',
            'Mitsubishi Colt Diesel #01', 'Mitsubishi Colt Diesel #02',
            'Isuzu Elf #01', 'Isuzu Elf #02',
            'Canter #01', 'Canter #02', 'Fuso #01',
        ];

        $keluarRecords = BarangKeluar::whereNull('requested_by')->get();
        foreach ($keluarRecords as $bk) {
            $bk->update([
                'requested_by' => $karyawans->random()->id,
                'truck_name' => $truckNames[array_rand($truckNames)],
            ]);
        }
        $this->command->info("✅ barang_keluar backfilled: {$keluarRecords->count()} rows");

        // =============================================
        // 4. SEED PENGAJUAN PEMBELIAN
        // =============================================
        $spareparts = Sparepart::all();
        $startDate = Carbon::parse('2025-06-01');
        $endDate = Carbon::parse('2026-06-09');
        $pengajuanCounter = 1;

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            // ~2 pengajuan per minggu (Senin & Kamis)
            if (in_array($currentDate->dayOfWeek, [1, 4]) && rand(0, 100) < 75) {
                $dateStr = $currentDate->format('Y-m-d');
                $ajuanNo = 'PGJ-' . $currentDate->format('Ym') . '-' . str_pad($pengajuanCounter++, 3, '0', STR_PAD_LEFT);

                // Random status distribution
                $rand = rand(0, 100);
                if ($rand < 20) {
                    $status = 'pending';
                    $approvedBy = null;
                    $notes = 'Menunggu persetujuan pimpinan';
                } elseif ($rand < 80) {
                    $status = 'approved';
                    $approvedBy = $pimpinan->id;
                    $notes = 'Disetujui untuk dilakukan pembelian';
                } else {
                    $status = 'rejected';
                    $approvedBy = $pimpinan->id;
                    $notes = 'Stok masih mencukupi, ditunda sampai bulan depan';
                }

                $pengajuan = PengajuanPembelian::create([
                    'ajuan_no' => $ajuanNo,
                    'date' => $dateStr,
                    'user_id' => $admin->id,
                    'approved_by' => $approvedBy,
                    'status' => $status,
                    'notes' => $notes,
                ]);

                // 2-5 item per pengajuan
                $numItems = rand(2, 5);
                $selectedItems = $spareparts->random($numItems);

                foreach ($selectedItems as $sp) {
                    DetailPengajuanPembelian::create([
                        'pengajuan_pembelian_id' => $pengajuan->id,
                        'sparepart_id' => $sp->id,
                        'quantity' => rand(5, 50),
                        'notes' => rand(0, 1) ? 'Prioritas tinggi' : null,
                    ]);
                }
            }

            $currentDate->addDay();
        }
        $totalPengajuan = PengajuanPembelian::count();
        $totalDetail = DetailPengajuanPembelian::count();
        $this->command->info("✅ Pengajuan pembelian: {$totalPengajuan} records, {$totalDetail} detail items");

        // =============================================
        // 5. SEED STOCK OPNAME
        // =============================================
        $groups = ['A', 'B', 'C', 'D'];
        $opnameCounter = 1;

        // Stock opname per bulan dari Jan 2026 - Jun 2026
        for ($y = 2026; $y <= 2026; $y++) {
            for ($m = ($y === 2026 ? 1 : 1); $m <= ($y === 2026 ? 6 : 12); $m++) {
                $cycleMonth = sprintf('%04d-%02d', $y, $m);
                $opnameDate = Carbon::create($y, $m, rand(20, 28));

                if ($opnameDate->isFuture()) continue;

                // 2 groups per month
                foreach (array_slice($groups, 0, 2) as $i => $group) {
                    $opnameNo = 'SO-' . $cycleMonth . '-' . $group;
                    $opnameDateForGroup = $opnameDate->copy()->subDays(2 - $i);

                    // Status logic: older months are approved, current month mix
                    if ($opnameDateForGroup->lt(Carbon::now()->subMonth())) {
                        $status = 'approved';
                        $approvedBy = $pimpinan->id;
                    } elseif ($opnameDateForGroup->lt(Carbon::now()->subWeek())) {
                        $status = collect(['approved', 'submitted'])->random();
                        $approvedBy = $status === 'approved' ? $pimpinan->id : null;
                    } else {
                        $status = collect(['draft', 'submitted'])->random();
                        $approvedBy = null;
                    }

                    $opname = StockOpname::create([
                        'opname_no' => $opnameNo,
                        'date' => $opnameDateForGroup->format('Y-m-d'),
                        'cycle_month' => $cycleMonth,
                        'cycle_group' => $group,
                        'user_id' => $admin->id,
                        'approved_by' => $approvedBy,
                        'status' => $status,
                        'notes' => 'Stock opname rutin bulan ' . $opnameDateForGroup->format('M Y'),
                    ]);

                    // Detail: check all spareparts
                    foreach ($spareparts as $sp) {
                        $systemStock = $sp->stock;
                        // ~85% match exactly, ~10% small discrepancy, ~5% bigger discrepancy
                        $rand = rand(0, 100);
                        if ($rand < 85) {
                            $physicalStock = $systemStock;
                        } elseif ($rand < 95) {
                            $physicalStock = $systemStock + rand(-3, 3);
                        } else {
                            $physicalStock = $systemStock + rand(-8, -1);
                        }
                        $physicalStock = max(0, $physicalStock);
                        $discrepancy = $physicalStock - $systemStock;

                        StockOpnameDetail::create([
                            'stock_opname_id' => $opname->id,
                            'sparepart_id' => $sp->id,
                            'system_stock' => $systemStock,
                            'physical_stock' => $physicalStock,
                            'discrepancy' => $discrepancy,
                            'notes' => $discrepancy !== 0 ? 'Perlu verifikasi' : null,
                        ]);
                    }

                    $opnameCounter++;
                }
            }
        }

        $totalOpname = StockOpname::count();
        $totalOpnameDetail = StockOpnameDetail::count();
        $this->command->info("✅ Stock opname: {$totalOpname} records, {$totalOpnameDetail} detail items");

        $this->command->info('');
        $this->command->info('🎉 Semua data baru berhasil di-seed!');
    }
}
