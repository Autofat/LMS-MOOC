<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Material;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat material sample jika belum ada
        $material = Material::firstOrCreate([
            'title' => 'Matematika Dasar'
        ], [
            'description' => 'Materi pembelajaran matematika dasar',
            'file_path' => 'materials/sample.pdf',
            'file_name' => 'sample.pdf',
            'file_size' => 1024,
            'category' => 'Matematika',
            'is_active' => true
        ]);

        $material2 = Material::firstOrCreate([
            'title' => 'Bahasa Indonesia'
        ], [
            'description' => 'Materi pembelajaran bahasa Indonesia',
            'file_path' => 'materials/bahasa.pdf',
            'file_name' => 'bahasa.pdf',
            'file_size' => 2048,
            'category' => 'Bahasa',
            'is_active' => true
        ]);

        // Sample questions
        $questions = [
            [
                'material_id' => $material->id,
                'question' => 'Berapa hasil dari 2 + 2?',
                'options' => [
                    'A' => '3',
                    'B' => '4',
                    'C' => '5',
                    'D' => '6'
                ],
                'answer' => 'B',
                'difficulty' => 'mudah'
            ],
            [
                'material_id' => $material->id,
                'question' => 'Berapa hasil dari 10 × 5?',
                'options' => [
                    'A' => '50',
                    'B' => '40',
                    'C' => '60',
                    'D' => '55'
                ],
                'answer' => 'A',
                'difficulty' => 'mudah'
            ],
            [
                'material_id' => $material->id,
                'question' => 'Berapa hasil dari √16?',
                'options' => [
                    'A' => '2',
                    'B' => '3',
                    'C' => '4',
                    'D' => '5'
                ],
                'answer' => 'C',
                'difficulty' => 'menengah'
            ],
            [
                'material_id' => $material2->id,
                'question' => 'Apa arti kata "bibliografi"?',
                'options' => [
                    'A' => 'Daftar pustaka',
                    'B' => 'Daftar isi',
                    'C' => 'Daftar gambar',
                    'D' => 'Daftar tabel'
                ],
                'answer' => 'A',
                'difficulty' => 'menengah'
            ],
            [
                'material_id' => $material2->id,
                'question' => 'Manakah yang merupakan contoh majas metafora?',
                'options' => [
                    'A' => 'Dia berlari seperti kijang',
                    'B' => 'Dia adalah singa di medan perang',
                    'C' => 'Suaranya merdu sekali',
                    'D' => 'Hujan turun dengan deras'
                ],
                'answer' => 'B',
                'difficulty' => 'sulit'
            ]
        ];

        foreach ($questions as $questionData) {
            Question::create($questionData);
        }
    }
}
