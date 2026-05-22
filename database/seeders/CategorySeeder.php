<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Filter', 'description' => 'Komponen penyaring oli, udara, dan bahan bakar.'],
            ['name' => 'Rem', 'description' => 'Suku cadang sistem pengereman truk.'],
            ['name' => 'Mesin', 'description' => 'Komponen utama mesin dan pendukung performa.'],
            ['name' => 'Kelistrikan', 'description' => 'Komponen listrik, lampu, aki, dan sensor.'],
            ['name' => 'Ban', 'description' => 'Ban, velg, baut roda, dan aksesori roda.'],
            ['name' => 'Suspensi', 'description' => 'Komponen sistem suspensi dan peredam kejut.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
