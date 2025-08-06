<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuestionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $materialId;
    
    public function __construct($materialId)
    {
        $this->materialId = $materialId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Question::where('material_id', $this->materialId)->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Teks Soal',
            'Tipe Soal',
            'Jawaban',
            'Opsi A',
            'Opsi B',
            'Opsi C',
            'Opsi D',
            'Opsi E',
            'Tingkat Kesulitan'
        ];
    }

    /**
     * @param mixed $question
     * @return array
     */
    public function map($question): array
    {
        // Get options safely
        $options = $question->options ?? [];
        
        // Determine question type (default to multiple choice if not specified)
        $questionType = $question->type ?? 'Pilihan Ganda';
        
        // Format answer based on question type
        $answer = '';
        if ($questionType === 'Pilihan Ganda' || $questionType === 'multiple_choice') {
            $answer = strtoupper($question->answer ?? '');
        } else {
            $answer = $question->answer ?? '';
        }

        return [
            $question->question ?? '',
            $questionType,
            $answer,
            $options['A'] ?? '',
            $options['B'] ?? '',
            $options['C'] ?? '',
            $options['D'] ?? '',
            $options['E'] ?? '',
            $question->difficulty ?? ''
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header with bold text and background color
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFF2F2F2']
                ]
            ],
            // Add borders to all cells
            'A1:I' . ($sheet->getHighestRow()) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 50, // Teks Soal
            'B' => 15, // Tipe Soal
            'C' => 10, // Jawaban
            'D' => 30, // Opsi A
            'E' => 30, // Opsi B
            'F' => 30, // Opsi C
            'G' => 30, // Opsi D
            'H' => 30, // Opsi E
            'I' => 20, // Tingkat Kesulitan
        ];
    }
}
