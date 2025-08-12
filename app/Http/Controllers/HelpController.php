<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Display the help index page
     */
    public function index()
    {
        return view('help.index');
    }

    /**
     * Display context-specific help modal content
     */
    public function contextualHelp($page)
    {
        $helpContent = $this->getContextualHelpContent($page);
        
        return response()->json([
            'title' => $helpContent['title'],
            'content' => $helpContent['content']
        ]);
    }

    /**
     * Get contextual help content based on page
     */
    private function getContextualHelpContent($page)
    {
        $helpData = [
            'materials-index' => [
                'title' => 'Bantuan Dashboard Materi',
                'content' => [
                    'Upload Materi Baru' => 'Klik tombol "Upload Materi Baru" untuk menambahkan dokumen PDF materi pembelajaran Anda.',
                    'Lihat Statistik' => 'Panel statistik menampilkan jumlah total materi, kategori, dan soal yang sudah dibuat.',
                    'Filter Kategori' => 'Gunakan dropdown kategori untuk memfilter materi berdasarkan topik tertentu.',
                    'Kelola Materi' => 'Klik pada kartu materi untuk melihat detail, edit, atau generate soal dari materi tersebut.'
                ]
            ],
            'materials-create' => [
                'title' => 'Bantuan Upload Materi',
                'content' => [
                    'Judul Materi' => 'Berikan judul yang deskriptif dan mudah diingat untuk materi Anda.',
                    'Pilih Kategori' => 'Pilih kategori yang sesuai atau buat kategori baru jika diperlukan.',
                    'Upload File' => 'File harus berformat PDF dengan ukuran maksimal 10MB.',
                    'Tips File' => 'Pastikan PDF berisi teks yang bisa dicopy (bukan hasil scan) untuk hasil generate soal yang optimal.'
                ]
            ],
            'questions-manage' => [
                'title' => 'Bantuan Kelola Soal',
                'content' => [
                    'Filter Soal' => 'Gunakan dropdown untuk memfilter soal berdasarkan materi atau kategori.',
                    'Edit Soal' => 'Klik tombol edit untuk mengubah pertanyaan, pilihan jawaban, atau jawaban yang benar.',
                    'Hapus Soal' => 'Gunakan tombol hapus untuk menghilangkan soal yang tidak diperlukan.',
                    'Tambah Soal' => 'Klik "Tambah Soal Baru" untuk membuat soal secara manual.'
                ]
            ],
            'admin-manage' => [
                'title' => 'Bantuan Kelola User',
                'content' => [
                    'Daftar User' => 'Melihat semua user yang terdaftar dalam sistem.',
                    'Tambah User' => 'Hanya superadmin yang dapat menambahkan user baru.',
                    'Edit User' => 'Mengubah informasi user seperti nama dan status admin.',
                    'Hapus User' => 'Menghapus user dari sistem (tidak dapat dibatalkan).'
                ]
            ]
        ];

        return $helpData[$page] ?? [
            'title' => 'Bantuan',
            'content' => ['Informasi bantuan tidak tersedia untuk halaman ini.']
        ];
    }
}
