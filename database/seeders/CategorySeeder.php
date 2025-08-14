<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pelatihan Teknis (LAT)',
                'description' => null,
                'is_active' => true
            ],
            [
                'name' => 'Pelatihan Bagi Pelatih (TOT)',
                'description' => null,
                'is_active' => true
            ],
            [
                'name' => 'Pelatihan Fungsional (FUNG)',
                'description' => null,
                'is_active' => true
            ],
            [
                'name' => 'Pelatihan Manajemen (MAN)',
                'description' => null,
                'is_active' => true
            ],
            [
                'name' => 'Pelatihan Kepemimpinan (PIM)',
                'description' => null,
                'is_active' => true
            ],
            [
                'name' => 'Pelatihan Latsar / Orientasi (LATSAR/ORIEN)',
                'description' => null,
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
