<?php

namespace Database\Seeders;

use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use App\Models\Sparepart;
use Illuminate\Database\Seeder;

class BarangMasukSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        BarangMasuk::truncate();
        DetailBarangMasuk::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $spareparts = Sparepart::all();
        $supplierIds = [1, 2, 3];
        $userIds = [1, 3];

        $notesList = [
            'Pengiriman sparepart rutin',
            'Restock bulanan gudang',
            'Pesanan supplier reguler',
            'Pengiriman tambahan',
            'Restock stok menipis',
            'Pesanan mendesak',
            'Pengiriman sparepart campuran',
            'Restock setelah audit stok',
            'Pesanan sparepart fast moving',
            'Pengiriman dari supplier utama',
        ];

        $supplierNotes = [
            1 => ['pengiriman', 'restock', 'filter', 'ban', 'oli'],
            2 => ['lampu', 'aki', 'bohlam', 'velg', 'elektrikal'],
            3 => ['seal', 'bushing', 'shockbreaker', 'baut', 'ring'],
        ];

        $counter = 0;
        $months = 6;

        for ($m = $months - 1; $m >= 0; $m--) {
            $monthStart = now()->subMonths($m)->startOfMonth();
            $daysInMonth = $monthStart->daysInMonth;
            $txCount = rand(4, 6);

            for ($i = 0; $i < $txCount; $i++) {
                $counter++;
                $day = rand(1, min(28, $daysInMonth));
                $date = $monthStart->copy()->addDays($day - 1);
                $supplierId = $supplierIds[array_rand($supplierIds)];
                $userId = $userIds[array_rand($userIds)];
                $hour = str_pad(rand(8, 16), 2, '0', STR_PAD_LEFT);
                $minute = str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);

                $supplierKeywords = $supplierNotes[$supplierId];
                $note = ucfirst($supplierKeywords[array_rand($supplierKeywords)]) . ' ' .
                        $supplierKeywords[array_rand($supplierKeywords)] . ' - ' .
                        $notesList[array_rand($notesList)];

                $barangMasuk = BarangMasuk::create([
                    'invoice_no' => 'IN-' . $date->format('Ymd') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT),
                    'date' => $date->toDateString(),
                    'time' => $hour . ':' . $minute,
                    'supplier_id' => $supplierId,
                    'user_id' => $userId,
                    'notes' => $note,
                ]);

                // 2-3 detail items per transaction
                $detailCount = rand(2, 3);
                $pickedIds = [];
                for ($j = 0; $j < $detailCount; $j++) {
                    // Pick a random sparepart, avoid duplicates within same transaction
                    do {
                        $sp = $spareparts->random();
                    } while (in_array($sp->id, $pickedIds) && count($pickedIds) < $spareparts->count());
                    $pickedIds[] = $sp->id;

                    $qty = match (true) {
                        $sp->id <= 6 => rand(10, 30),    // filter, kampas, seal — medium qty
                        in_array($sp->id, [13, 15]) => rand(4, 12),  // ban, velg — low qty
                        in_array($sp->id, [11, 16, 17]) => rand(4, 10), // aki, shockbreaker — low qty
                        default => rand(15, 50),           // bohlam, baut, bushing — high qty
                    };

                    // Harga estimasi per sparepart (berdasarkan data sebelumnya)
                    $priceMap = [
                        1 => 185000,  2 => 165000,  3 => 195000,   // filter
                        4 => 275000,  5 => 245000,  6 => 450000,    // rem
                        7 => 1250000, 8 => 18000,   9 => 35000,     // mesin
                        10 => 325000, 11 => 850000, 12 => 15000,    // kelistrikan
                        13 => 1250000, 14 => 25000, 15 => 750000,   // ban
                        16 => 580000, 17 => 650000, 18 => 155000,   // suspensi
                    ];
                    $price = $priceMap[$sp->id] ?? rand(10000, 200000);

                    DetailBarangMasuk::create([
                        'barang_masuk_id' => $barangMasuk->id,
                        'sparepart_id' => $sp->id,
                        'quantity' => $qty,
                        'price' => $price,
                    ]);
                }
            }
        }

        $this->command?->info("✓ {$counter} transaksi Barang Masuk + detail (6 bulan terakhir)");
    }
}
