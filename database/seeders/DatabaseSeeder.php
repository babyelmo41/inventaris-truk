<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan penting karena foreign key constraint:
     * 1. Users (tidak ada FK)
     * 2. Categories (tidak ada FK)
     * 3. Suppliers (tidak ada FK)
     * 4. Spareparts (FK → categories, suppliers)
     * 5. BarangMasuk (FK → suppliers, users)
     * 6. DetailBarangMasuk (FK → barang_masuk, spareparts)
     * 7. BarangKeluar (FK → users)
     * 8. DetailBarangKeluar (FK → barang_keluar, spareparts)
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            SparepartSeeder::class,
            BarangMasukSeeder::class,
            DetailBarangMasukSeeder::class,
            BarangKeluarSeeder::class,
            DetailBarangKeluarSeeder::class,
        ]);
    }
}
