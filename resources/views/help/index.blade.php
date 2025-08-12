<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bantuan - KemenLH/BPLH E-Learning Platform</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .professional-gradient {
            background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 50%, rgba(42,122,157,1) 100%);
        }
        .subtle-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='rgba(28,88,113,0.03)' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .help-card {
            transition: all 0.3s ease;
        }
        .help-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(28,88,113,0.15);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen subtle-pattern">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            @if(Auth::user()->is_admin)
                <a href="{{ route('materials.index') }}" 
                   class="inline-flex items-center space-x-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 shadow-md">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            @else
                <a href="{{ route('user.dashboard') }}" 
                   class="inline-flex items-center space-x-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 shadow-md">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            @endif
        </div>

        <!-- Header -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl border p-8 mb-8" style="border-color: rgba(28,88,113,0.1);">
            <div class="text-center mb-8">
                <div class="professional-gradient rounded-full p-6 inline-block mb-4">
                    <i class="fas fa-question-circle text-4xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold mb-2" style="color: rgba(28,88,113,1);">Bantuan Penggunaan</h1>
                <p class="text-gray-600">Panduan lengkap menggunakan Platform E-Learning KemenLH/BPLH</p>
            </div>
        </div>

        <!-- Quick Start Guide -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl border p-8 mb-8" style="border-color: rgba(28,88,113,0.1);">
            <h2 class="text-2xl font-bold mb-6" style="color: rgba(28,88,113,1);">
                <i class="fas fa-compass mr-2"></i>Panduan Cepat
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="help-card bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-6 border" style="border-color: rgba(28,88,113,0.1);">
                    <div class="text-center">
                        <div class="bg-orange-100 rounded-full p-4 inline-block mb-4">
                            <i class="fas fa-folder-plus text-2xl text-orange-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">1. Tambah Kategori</h3>
                        <p class="text-gray-600 text-sm">Buat kategori untuk mengelompokkan materi pembelajaran</p>
                    </div>
                </div>
                <div class="help-card bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border" style="border-color: rgba(28,88,113,0.1);">
                    <div class="text-center">
                        <div class="bg-blue-100 rounded-full p-4 inline-block mb-4">
                            <i class="fas fa-upload text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">2. Upload Materi</h3>
                        <p class="text-gray-600 text-sm">Upload file PDF atau dokumen materi pembelajaran Anda ke dalam sistem</p>
                    </div>
                </div>
                <div class="help-card bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border" style="border-color: rgba(28,88,113,0.1);">
                    <div class="text-center">
                        <div class="bg-green-100 rounded-full p-4 inline-block mb-4">
                            <i class="fas fa-magic text-2xl text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">3. Generate Soal</h3>
                        <p class="text-gray-600 text-sm">Pilih opsi "Generate Soal" dan Sistem AI akan secara otomatis membuat soal berdasarkan materi yang diupload</p>
                    </div>
                </div>
                <div class="help-card bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-6 border" style="border-color: rgba(28,88,113,0.1);">
                    <div class="text-center">
                        <div class="bg-purple-100 rounded-full p-4 inline-block mb-4">
                            <i class="fas fa-edit text-2xl text-purple-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">4. Kelola Soal</h3>
                        <p class="text-gray-600 text-sm">Edit, hapus, atau tambah soal baru sesuai dengan kebutuhan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Instructions -->
        <div class="space-y-8">
            <!-- Tambah Kategori -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border p-6" style="border-color: rgba(28,88,113,0.1);">
                <h3 class="text-xl font-bold mb-4 flex items-center" style="color: rgba(28,88,113,1);">
                    <i class="fas fa-folder-plus mr-2"></i>Cara Tambahkan Kategori
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">1</span>
                        <p class="text-sm text-gray-600">Klik tombol "Tambah Kategori Baru" di halaman kategori</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">2</span>
                        <p class="text-sm text-gray-600">Isi nama kategori dan deskripsi kategori</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">3</span>
                        <p class="text-sm text-gray-600">Klik "Buat Kategori" untuk menyimpan kategori baru</p>
                    </div>
                </div>
            </div>

            <!-- Upload Materi -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border p-6" style="border-color: rgba(28,88,113,0.1);">
                <h3 class="text-xl font-bold mb-4 flex items-center" style="color: rgba(28,88,113,1);">
                    <i class="fas fa-cloud-upload-alt mr-2"></i>Cara Upload Materi
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">1</span>
                        <p class="text-sm text-gray-600">Klik tombol "Upload Materi" di halaman dashboard</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">2</span>
                        <p class="text-sm text-gray-600">Isi judul materi dan pilih kategori yang sesuai</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">3</span>
                        <p class="text-sm text-gray-600">Pilih file PDF (maksimal 10MB) yang akan diupload</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">4</span>
                        <p class="text-sm text-gray-600">Klik "Upload Materi" untuk menyimpan</p>
                    </div>
                </div>
            </div>

            <!-- Generate Soal -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border p-6" style="border-color: rgba(28,88,113,0.1);">
                <h3 class="text-xl font-bold mb-4 flex items-center" style="color: rgba(28,88,113,1);">
                    <i class="fas fa-cogs mr-2"></i>Cara Generate Soal
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">1</span>
                        <p class="text-sm text-gray-600">Buka detail materi yang sudah diupload</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">2</span>
                        <p class="text-sm text-gray-600">Klik tombol "Generate Soal Otomatis"</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">3</span>
                        <p class="text-sm text-gray-600">Tunggu proses generate selesai (biasanya 1-3 menit)</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">4</span>
                        <p class="text-sm text-gray-600">Soal akan otomatis tersimpan dan bisa dilihat di menu "Kelola Soal"</p>
                    </div>
                </div>
            </div>

            <!-- Kelola Soal -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border p-6" style="border-color: rgba(28,88,113,0.1);">
                <h3 class="text-xl font-bold mb-4 flex items-center" style="color: rgba(28,88,113,1);">
                    <i class="fas fa-tasks mr-2"></i>Cara Kelola Soal
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">1</span>
                        <p class="text-sm text-gray-600">Akses menu "Soal" di navbar untuk melihat semua soal</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">2</span>
                        <p class="text-sm text-gray-600">Gunakan tombol "Edit" untuk mengubah soal yang sudah ada</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">3</span>
                        <p class="text-sm text-gray-600">Klik "Tambah Soal Baru" untuk membuat soal manual</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">4</span>
                        <p class="text-sm text-gray-600">Gunakan tombol hapus untuk menghilangkan soal yang tidak diperlukan</p>
                    </div>
                </div>
            </div>

            <!-- Tips & Tricks -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border p-6" style="border-color: rgba(28,88,113,0.1);">
                <h3 class="text-xl font-bold mb-4 flex items-center" style="color: rgba(28,88,113,1);">
                    <i class="fas fa-lightbulb mr-2"></i>Tips & Trik
                </h3>
                <div class="space-y-3">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                        <p class="text-sm text-gray-700"><strong>Format File:</strong> Gunakan PDF dengan teks yang jelas, hindari gambar scan untuk hasil terbaik</p>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                        <p class="text-sm text-gray-700"><strong>Kategorisasi:</strong> Kelompokkan materi berdasarkan topik untuk memudahkan pencarian</p>
                    </div>
                    <div class="bg-green-50 border-l-4 border-green-400 p-3 rounded">
                        <p class="text-sm text-gray-700"><strong>Review Soal:</strong> Selalu review soal yang di-generate untuk memastikan kualitas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl border p-8 mt-8" style="border-color: rgba(28,88,113,0.1);">
            <h2 class="text-2xl font-bold mb-6" style="color: rgba(28,88,113,1);">
                <i class="fas fa-question-circle mr-2"></i>Pertanyaan yang Sering Diajukan (FAQ)
            </h2>
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-4">
                    <button class="faq-toggle w-full text-left flex justify-between items-center py-2" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-800">Bagaimana cara mengunduh soal yang telah dibuat?</span>
                        <i class="fas fa-chevron-down transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden mt-2 text-gray-600">
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            <li><strong>Download Soal dari Materi:</strong> Di halaman detail materi, klik "Download Soal" untuk mengunduh soal-soal yang terkait dengan materi tersebut dalam format Excel.</li>
                            <li><strong>Download Soal dari Kategori:</strong> Di halaman kategori, klik "Download Soal" untuk mengunduh semua soal dari kategori tersebut dalam format Excel.</li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-b border-gray-200 pb-4">
                    <button class="faq-toggle w-full text-left flex justify-between items-center py-2" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-800">Bagaimana cara menambah kategori baru?</span>
                        <i class="fas fa-chevron-down transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden mt-2 text-gray-600">
                        <p>Klik menu "Kategori" di navbar, kemudian pilih "Tambah Kategori Baru". Isi nama kategori dan deskripsi, lalu klik "Simpan Kategori". Kategori baru akan langsung tersedia untuk digunakan saat upload materi.</p>
                    </div>
                </div>
                
                <div class="border-b border-gray-200 pb-4">
                    <button class="faq-toggle w-full text-left flex justify-between items-center py-2" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-800">Apakah ada batasan ukuran file yang bisa diupload?</span>
                        <i class="fas fa-chevron-down transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden mt-2 text-gray-600">
                        <p>Ya, ukuran maksimal file yang bisa diupload adalah 10MB. Pastikan file PDF Anda tidak melebihi batas ini.</p>
                    </div>
                </div>
                
                <div class="border-b border-gray-200 pb-4">
                    <button class="faq-toggle w-full text-left flex justify-between items-center py-2" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-800">Berapa lama proses generate soal?</span>
                        <i class="fas fa-chevron-down transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden mt-2 text-gray-600">
                        <p>Proses generate soal biasanya memakan waktu 1-3 menit tergantung panjang materi. Sistem akan memberikan notifikasi ketika proses selesai.</p>
                    </div>
                </div>
                
                <div class="border-b border-gray-200 pb-4">
                    <button class="faq-toggle w-full text-left flex justify-between items-center py-2" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-800">Bisakah saya mengedit soal yang sudah di-generate?</span>
                        <i class="fas fa-chevron-down transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden mt-2 text-gray-600">
                        <p>Tentu saja! Anda bisa mengedit semua aspek dari soal yang sudah di-generate, termasuk pertanyaan, pilihan jawaban, dan jawaban yang benar.</p>
                    </div>
                </div>
                
                <div class="border-b border-gray-200 pb-4">
                    <button class="faq-toggle w-full text-left flex justify-between items-center py-2" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-800">Bagaimana cara menghapus materi yang sudah diupload?</span>
                        <i class="fas fa-chevron-down transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden mt-2 text-gray-600">
                        <p>Buka detail materi, lalu klik tombol "Hapus Materi". Perhatian: menghapus materi akan menghapus semua soal yang terkait dengan materi tersebut.</p>
                    </div>
                </div>
                
                <div class="border-b border-gray-200 pb-4">
                    <button class="faq-toggle w-full text-left flex justify-between items-center py-2" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-800">Bisakah saya membuat soal secara manual?</span>
                        <i class="fas fa-chevron-down transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden mt-2 text-gray-600">
                        <p>Ya, tentu saja! Anda bisa membuat soal secara manual dengan pilih "Tambah Soal Baru". Isi pertanyaan, pilihan jawaban, dan tentukan jawaban yang benar. Soal manual ini akan tersimpan bersama dengan soal-soal yang di-generate otomatis.</p>
                    </div>
                </div>
                
                <div class="border-b border-gray-200 pb-4">
                    <button class="faq-toggle w-full text-left flex justify-between items-center py-2" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-800">Apakah ada format khusus untuk file PDF?</span>
                        <i class="fas fa-chevron-down transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden mt-2 text-gray-600">
                        <p>Tidak ada format khusus, tapi untuk hasil terbaik gunakan PDF dengan teks yang bisa dicopy (bukan hasil scan gambar). Pastikan teks mudah dibaca dan terstruktur dengan baik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFAQ(button) {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    </script>
</body>
</html>
