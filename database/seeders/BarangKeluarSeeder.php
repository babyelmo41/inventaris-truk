<?php

namespace Database\Seeders;

use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use App\Models\Sparepart;
use Illuminate\Database\Seeder;

class BarangKeluarSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        BarangKeluar::truncate();
        DetailBarangKeluar::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $spareparts = Sparepart::all();
        $userIds = [1, 3];

        $purposes = [
            'Perawatan Truk DT-{:d}',
            'Perbaikan Truk DT-{:d}',
            'Perawatan Berkala Truk DT-{:d}',
            'Penggantian Ban Truk DT-{:d}',
            'Service Rutin Truk DT-{:d}',
            'Perbaikan Mendesak Truk DT-{:d}',
            'Inspeksi & Ganti Truk DT-{:d}',
        ];

        $notesList = [
            'Penggantian rutin filter dan kampas rem',
            'Ganti lampu dan perbaiki suspensi',
            'Service rutin 10.000 km',
            'Ban aus, perlu diganti',
            'Kampas rem aus, ganti baru',
            'Ganti filter oli dan solar',
            'Lampu headlamp pecah',
            'Shockbreaker bocor, perlu ganti',
            'Aki soak, ganti baru',
            'Bushing aus, ganti baru',
            'Service 20.000 km',
            'Velg retak, ganti untuk keselamatan',
            'Ban bocor, ganti ban cadangan',
            'Penggantian kampas rem belakang',
            'Ganti bohlam sen dan rem',
            'Service 5.000 km',
            'Ganti seal klep bocor',
            'Perbaikan sistem kelistrikan',
            'Ganti bearing roda',
            'Penggantian filter udara kotor',
        ];

        $counter = 0;
        $months = 6;

        for ($m = $months - 1; $m >= 0; $m--) {
            $monthStart = now()->subMonths($m)->startOfMonth();
            $daysInMonth = $monthStart->daysInMonth;
            $txCount = rand(3, 4);

            for ($i = 0; $i < $txCount; $i++) {
                $counter++;
                $day = rand(1, min(28, $daysInMonth));
                $date = $monthStart->copy()->addDays($day - 1);
                $userId = $userIds[array_rand($userIds)];
                $hour = str_pad(rand(8, 16), 2, '0', STR_PAD_LEFT);
                $minute = str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);

                $truckNum = str_pad(rand(1, 25), 3, '0', STR_PAD_LEFT);
                $purpose = str_replace('{:d}', $truckNum, $purposes[array_rand($purposes)]);
                $note = $notesList[array_rand($notesList)];

                $barangKeluar = BarangKeluar::create([
                    'reference_no' => 'OUT-' . $date->format('Ymd') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT),
                    'date' => $date->toDateString(),
                    'time' => $hour . ':' . $minute,
                    'purpose' => $purpose,
                    'user_id' => $userId,
                    'notes' => $note,
                ]);

                // 2-3 detail items per transaction
                $detailCount = rand(2, 3);
                $pickedIds = [];
                for ($j = 0; $j < $detailCount; $j++) {
                    do {
                        $sp = $spareparts->random();
                    } while (in_array($sp->id, $pickedIds) && count($pickedIds) < $spareparts->count());
                    $pickedIds[] = $sp->id;

                    $qty = match (true) {
                        in_array($sp->id, [13, 15]) => rand(2, 6),     // ban, velg — low
                        in_array($sp->id, [11, 16, 17]) => rand(1, 3), // aki, shockbreaker — very low
                        in_array($sp->id, [12, 14]) => rand(4, 20),     // bohlam, baut — high
                        default => rand(1, 4),
                    };

                    DetailBarangKeluar::create([
                        'barang_keluar_id' => $barangKeluar->id,
                        'sparepart_id' => $sp->id,
                        'quantity' => $qty,
                    ]);
                }
            }
        }

        $this->command?->info("✓ {$counter} transaksi Barang Keluar + detail (6 bulan terakhir)");
    }
}
