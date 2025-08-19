<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload Materi Baru - KemenLH/BPLH E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(28, 88, 113, 0.1);
        }

        .subtle-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='rgba(28,88,113,0.03)' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen subtle-pattern">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <!-- Toast Messages -->
    @if (session('success'))
        <div id="createSuccessToast"
            class="fixed top-8 right-4 z-50 bg-green-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl mr-4"></i>
                <p class="text-base font-medium">{{ session('success') }}</p>
                <button onclick="hideCreateToast('createSuccessToast')" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div id="createErrorToast"
            class="fixed top-8 right-4 z-50 bg-red-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-2xl mr-4"></i>
                <div class="ml-4 flex-1">
                    <p class="text-base font-medium">{{ session('error') }}</p>
                </div>
                <button onclick="hideCreateToast('createErrorToast')" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div id="createValidationToast"
            class="fixed top-8 right-4 z-50 bg-red-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-2xl mr-4"></i>
                <div class="ml-4 flex-1">
                    <p class="text-base font-medium">Error Validasi:</p>
                    <ul class="text-sm mt-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="hideCreateToast('createValidationToast')" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="container mx-auto px-4 pb-8 pt-8">
        <div class="max-w-2xl mx-auto">
            <div class="glass-effect rounded-2xl shadow-xl p-8">
                <!-- Header -->
                <div class="mb-8 pb-4 border-b" style="border-color: rgba(28,88,113,0.1);">
                    <h1 class="text-3xl font-bold mb-2 flex items-center" style="color: rgba(28,88,113,1);">
                        <i class="fas fa-cloud-upload-alt mr-3 text-emerald-500"></i>
                        Upload Materi Baru
                    </h1>
                    <p class="text-sm" style="color: rgba(28,88,113,0.7);">Tambahkan materi pembelajaran untuk
                        mengembangkan bank soal otomatis</p>
                </div>

                @if ($errors->any())
                    <div class="rounded-xl px-6 py-4 mb-6"
                        style="background: linear-gradient(135deg, rgba(239,68,68,0.1) 0%, rgba(220,38,38,0.1) 100%); border: 1px solid rgba(239,68,68,0.3);">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="font-semibold text-red-700">Terdapat kesalahan dalam input:</span>
                        </div>
                        <ul class="list-disc list-inside text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-8" id="materialForm">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold mb-3 flex items-center space-x-2"
                            style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-graduation-cap text-emerald-500"></i>
                            <span>Judul Materi *</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                            style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);"
                            placeholder="Masukkan judul materi...">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold mb-3 flex items-center space-x-2"
                            style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-align-left text-blue-500"></i>
                            <span>Deskripsi (Opsional)</span>
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                            style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);"
                            placeholder="Masukkan deskripsi materi...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-semibold mb-3 flex items-center space-x-2"
                            style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-tags text-purple-500"></i>
                            <span>Kategori/Topik *</span>
                        </label>
                        <select id="category" name="category" required
                            class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                            style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);">
                            <option value="">-- Pilih Kategori --</option>
                            @php
                                $existingCategories = isset($categories)
                                    ? $categories
                                    : \App\Models\Category::where('is_active', true)
                                        ->with('subCategories')
                                        ->orderBy('name')
                                        ->get();
                            @endphp
                            @foreach ($existingCategories as $cat)
                                <option value="{{ $cat->name }}"
                                    {{ old('category', request('category', $selectedCategoryName ?? '')) == $cat->name ? 'selected' : '' }}
                                    title="{{ $cat->description }}" data-category-id="{{ $cat->id }}">
                                    {{ $cat->name }}{{ $cat->description ? ' - ' . $cat->description : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs" style="color: rgba(28,88,113,0.6);">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pilih kategori yang sesuai dengan materi Anda
                            </p>
                            <a href="{{ route('materials.index') }}"
                                class="text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                                <i class="fas fa-plus mr-1"></i>Tambah Kategori Baru
                            </a>
                        </div>
                    </div>

                    <!-- Sub Category -->
                    <!-- Sub Category -->
                    <!-- Sub Category -->
                    <div id="subCategoryContainer" style="display: none;">
                        <label for="sub_category_id"
                            class="block text-sm font-semibold mb-3 flex items-center space-x-2"
                            style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-layer-group text-indigo-500"></i>
                            <span>Sub Kategori <span id="subCategoryRequired" style="display: none;">*</span></span>
                        </label>
                        <select id="sub_category_id" name="sub_category_id"
                            class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                            style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);">
                            <option value="">-- Pilih Sub Kategori (Opsional) --</option>
                        </select>
                        <p class="text-xs mt-1" style="color: rgba(107, 114, 128, 1);">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span id="subCategoryHelpText">Sub Kategori membantu mengelompokkan materi serupa untuk
                                download massal soal</span>
                        </p>
                    </div>

                    <!-- PDF File -->
                    <div>
                        <label for="pdf_file" class="block text-sm font-semibold mb-3 flex items-center space-x-2"
                            style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-file-pdf text-red-500"></i>
                            <span>File PDF *</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-dashed rounded-xl hover:border-opacity-60 transition-all"
                            style="border-color: rgba(28,88,113,0.3); background: linear-gradient(135deg, rgba(245,158,11,0.03) 0%, rgba(251,191,36,0.03) 100%);">
                            <div class="space-y-2 text-center">
                                <div class="mx-auto h-16 w-16 rounded-full flex items-center justify-center mb-4"
                                    style="background: linear-gradient(135deg, rgba(239,68,68,0.1) 0%, rgba(220,38,38,0.1) 100%);">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-red-500"></i>
                                </div>
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm" style="color: rgba(28,88,113,0.7);">
                                    <label for="pdf_file"
                                        class="relative cursor-pointer rounded-lg font-semibold px-4 py-2 transition-all hover:shadow-md"
                                        style="background: linear-gradient(135deg, rgba(28,88,113,0.1) 0%, rgba(34,108,137,0.1) 100%); color: rgba(28,88,113,0.9);">
                                        <span><i class="fas fa-upload mr-2"></i>Upload file PDF</span>
                                        <input id="pdf_file" name="pdf_file" type="file" class="sr-only"
                                            accept=".pdf" required onchange="updateFileName(this)">
                                    </label>
                                    <p class="pl-2 self-center">atau drag and drop</p>
                                </div>
                                <p class="text-xs" style="color: rgba(28,88,113,0.6);">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    PDF hingga 10MB • Format yang didukung: .pdf
                                </p>

                                <p id="file-name" class="text-sm font-medium" style="color: rgba(28,88,113,0.8);">
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-8 border-t"
                        style="border-color: rgba(28,88,113,0.1);">
                        @if (request('sub_category_id'))
                            <a href="{{ route('materials.sub-categories.detail', ['subCategory' => request('sub_category_id')]) }}"
                                class="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                style="background: linear-gradient(135deg, rgba(107,114,128,0.8) 0%, rgba(75,85,99,0.9) 100%); color: white;">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Sub Kategori
                            </a>
                        @else
                            <a href="{{ route('materials.index') }}"
                                class="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                style="background: linear-gradient(135deg, rgba(107,114,128,0.8) 0%, rgba(75,85,99,0.9) 100%); color: white;">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex items-center px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                            style="background: linear-gradient(135deg, rgba(28,88,113,0.8) 0%, rgba(34,108,137,0.9) 100%); color: white;">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>Upload Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center">
            <div class="mb-6">
                <div
                    class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-4">
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Mengupload Materi</h3>
                <p class="text-gray-600" id="loadingMessage">Sedang mengupload file dan menggenerate soal...</p>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                    style="width: 0%"></div>
            </div>

            <div class="text-sm text-gray-500" id="progressText">0% Complete</div>

            <!-- Progress Steps -->
            <div class="mt-6 space-y-2 text-left">
                <div class="flex items-center space-x-2" id="step1">
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <span class="text-sm text-gray-600">Mengupload file PDF...</span>
                </div>
                <div class="flex items-center space-x-2" id="step2">
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                    <span class="text-sm text-gray-400">Memvalidasi file...</span>
                </div>
                <div class="flex items-center space-x-2" id="step3">
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                    <span class="text-sm text-gray-400">Memproses materi...</span>
                </div>
                <div class="flex items-center space-x-2" id="step4">
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                    <span class="text-sm text-gray-400">Menyimpan ke database...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Upload Gagal</h3>
                <p class="text-gray-600" id="errorMessage">Terjadi kesalahan saat mengupload materi.</p>
            </div>

            <!-- Error Details -->
            <div id="errorDetails" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 hidden">
                <h4 class="font-semibold text-red-800 mb-2">Detail Error:</h4>
                <p class="text-red-700 text-sm" id="errorDetailsText"></p>
            </div>

            <!-- Common Solutions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-blue-800 mb-2">Solusi yang Disarankan:</h4>
                <ul class="space-y-1" id="solutionsList">
                    <li class="text-blue-700 text-sm">• Pastikan file yang dipilih adalah file PDF</li>
                    <li class="text-blue-700 text-sm">• Periksa ukuran file tidak melebihi 10MB</li>
                    <li class="text-blue-700 text-sm">• Pastikan file tidak rusak atau corrupt</li>
                    <li class="text-blue-700 text-sm">• Coba refresh halaman dan upload ulang</li>
                </ul>
            </div>

            <div class="flex justify-center space-x-3">
                <button onclick="closeErrorModal()"
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Tutup
                </button>
                <button onclick="retryUpload()"
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-redo mr-2"></i>Coba Lagi
                </button>
            </div>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileNameElement = document.getElementById('file-name');
            if (input.files && input.files[0]) {
                fileNameElement.textContent = input.files[0].name;
            } else {
                fileNameElement.textContent = '';
            }
        }

        function updateProgress(step, message, percentage) {
            document.getElementById('loadingMessage').textContent = message;
            document.getElementById('progressBar').style.width = percentage + '%';
            document.getElementById('progressText').textContent = percentage + '% Complete';

            // Update step indicators
            for (let i = 1; i <= 4; i++) {
                const stepEl = document.getElementById(`step${i}`);
                const dot = stepEl.querySelector('div');
                const text = stepEl.querySelector('span');

                if (i <= step) {
                    dot.className = 'w-2 h-2 bg-blue-600 rounded-full';
                    text.className = 'text-sm text-gray-600';
                } else {
                    dot.className = 'w-2 h-2 bg-gray-300 rounded-full';
                    text.className = 'text-sm text-gray-400';
                }
            }
        }

        function showErrorModal(error) {
            // Hide loading modal
            document.getElementById('loadingModal').classList.add('hidden');

            // Get modal elements
            const errorModal = document.getElementById('errorModal');
            const errorMessage = document.getElementById('errorMessage');
            const errorDetails = document.getElementById('errorDetails');
            const errorDetailsText = document.getElementById('errorDetailsText');
            const solutionsList = document.getElementById('solutionsList');

            // Default values
            let mainMessage = 'Terjadi kesalahan saat mengupload materi.';
            let showDetails = false;
            let solutions = [
                'Pastikan file yang dipilih adalah file PDF',
                'Periksa ukuran file tidak melebihi 10MB',
                'Pastikan file tidak rusak atau corrupt',
                'Coba refresh halaman dan upload ulang'
            ];

            // Handle specific error types
            if (error.message) {
                if (error.message.includes('No file was uploaded')) {
                    mainMessage = 'Tidak ada file yang dipilih untuk diupload.';
                    solutions = [
                        'Klik tombol "Upload file PDF" untuk memilih file',
                        'Pastikan file yang dipilih adalah format PDF',
                        'Drag and drop file PDF ke area upload'
                    ];
                } else if (error.message.includes('file size') || error.message.includes('10MB')) {
                    mainMessage = 'Ukuran file terlalu besar.';
                    solutions = [
                        'Maksimal ukuran file adalah 10MB',
                        'Kompres file PDF untuk mengurangi ukuran',
                        'Pilih file PDF dengan ukuran yang lebih kecil'
                    ];
                } else if (error.message.includes('mimes') || error.message.includes('PDF')) {
                    mainMessage = 'Format file tidak didukung.';
                    solutions = [
                        'Hanya file PDF yang diperbolehkan',
                        'Pastikan ekstensi file adalah .pdf',
                        'Konversi file ke format PDF terlebih dahulu'
                    ];
                } else if (error.message.includes('CSRF')) {
                    mainMessage = 'Sesi Anda telah berakhir.';
                    solutions = [
                        'Refresh halaman dan coba lagi',
                        'Login ulang jika diperlukan'
                    ];
                } else {
                    showDetails = true;
                }
            }

            // Set error message
            errorMessage.textContent = mainMessage;

            // Show/hide error details
            if (showDetails && error.message) {
                errorDetailsText.textContent = error.message;
                errorDetails.classList.remove('hidden');
            } else {
                errorDetails.classList.add('hidden');
            }

            // Update solutions list
            solutionsList.innerHTML = '';
            solutions.forEach(solution => {
                const li = document.createElement('li');
                li.textContent = '• ' + solution;
                li.className = 'text-blue-700 text-sm mb-1';
                solutionsList.appendChild(li);
            });

            // Show modal
            errorModal.classList.remove('hidden');
        }

        function closeErrorModal() {
            document.getElementById('errorModal').classList.add('hidden');
        }

        function retryUpload() {
            closeErrorModal();
            // Reset form if needed
            const form = document.getElementById('materialForm');
            // Don't reset the form, just allow user to try again
        }

        document.getElementById('materialForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Check if file is selected
            const fileInput = document.getElementById('pdf_file');
            if (!fileInput.files || !fileInput.files[0]) {
                showErrorModal({
                    message: 'No file was uploaded. Please select a PDF file.'
                });
                return;
            }

            // Show loading modal
            document.getElementById('loadingModal').classList.remove('hidden');
            updateProgress(1, 'Mengupload file PDF...', 25);

            // Upload the material
            fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {
                    const data = await response.json();

                    if (!response.ok) {
                        // Handle validation errors specifically
                        if (response.status === 422 && data.errors) {
                            const firstError = Object.values(data.errors)[0][0];
                            throw new Error(firstError);
                        }
                        throw new Error(data.message || `Upload failed: ${response.status}`);
                    }

                    // Success - show progress
                    updateProgress(2, 'File berhasil diupload!', 50);
                    setTimeout(() => {
                        updateProgress(3, 'Memproses materi...', 75);
                        setTimeout(() => {
                            updateProgress(4, 'Proses selesai! Mengalihkan halaman...',
                            100);
                            setTimeout(() => {
                                const materialShowUrl =
                                    "{{ route('materials.show', ['id' => ':id']) }}"
                                    .replace(':id', data.material.id);
                                window.location.href = materialShowUrl;
                            }, 1500);
                        }, 500);
                    }, 500);
                })
                .catch(error => {
                    showErrorModal(error);
                });
        });

        // Toast functionality for materials create page
        window.hideCreateToast = function(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        // Auto hide toasts after 7 seconds (longer for error messages)
        document.addEventListener('DOMContentLoaded', function() {
            const createSuccessToast = document.getElementById('createSuccessToast');
            const createErrorToast = document.getElementById('createErrorToast');
            const createValidationToast = document.getElementById('createValidationToast');

            if (createSuccessToast) {
                setTimeout(() => {
                    hideCreateToast('createSuccessToast');
                }, 5000);
            }

            if (createErrorToast) {
                setTimeout(() => {
                    hideCreateToast('createErrorToast');
                }, 7000); // Longer for error messages
            }

            if (createValidationToast) {
                setTimeout(() => {
                    hideCreateToast('createValidationToast');
                }, 8000); // Longest for validation errors
            }

            // Fill description from URL parameter if exists
            const urlParams = new URLSearchParams(window.location.search);
            const categoryDescription = urlParams.get('description');
            if (categoryDescription && !document.getElementById('description').value) {
                document.getElementById('description').value = categoryDescription;
            }

            // Category and SubCategory data
            const categoriesData = @json(isset($categories) ? $categories : []);
            const selectedSubCategoryId = @json($selectedSubCategoryId ?? null);
            const selectedCategoryName = @json($selectedCategoryName ?? null);
            const isSubCategoryRequired = urlParams.get('sub_category_id') !== null;

            // Get DOM elements
            const categorySelect = document.getElementById('category');
            const subCategoryContainer = document.getElementById('subCategoryContainer');
            const subCategorySelect = document.getElementById('sub_category_id');
            const subCategoryRequired = document.getElementById('subCategoryRequired');
            const subCategoryHelpText = document.getElementById('subCategoryHelpText');

            // Pre-select category if coming from subcategory page
            if (selectedCategoryName) {
                categorySelect.value = selectedCategoryName;
            }

            // Debug: Log data
            // console.log('Categories Data:', categoriesData);
            // console.log('Selected SubCategory ID:', selectedSubCategoryId);
            // console.log('Selected Category Name:', selectedCategoryName);
            // console.log('Is SubCategory Required:', isSubCategoryRequired);

            function updateSubCategories() {
                const selectedCategoryName = categorySelect.value;

                // Clear existing options first
                const placeholder = isSubCategoryRequired ? '-- Pilih Sub Kategori *--' :
                    '-- Pilih Sub Kategori (Opsional) --';
                subCategorySelect.innerHTML = `<option value="">${placeholder}</option>`;

                // Hide container by default
                subCategoryContainer.style.display = 'none';
                subCategorySelect.required = false;

                // Don't show subcategories for empty selection
                if (!selectedCategoryName) {
                    return;
                }

                // Find the selected category
                const category = categoriesData.find(cat => cat.name === selectedCategoryName);

                if (category && category.sub_categories && category.sub_categories.length > 0) {
                    // Filter only active subcategories for this specific category
                    const activeSubCategories = category.sub_categories.filter(subCat => subCat.is_active);

                    if (activeSubCategories.length > 0) {
                        // Show subcategory container
                        subCategoryContainer.style.display = 'block';

                        // Update required status and styling
                        if (isSubCategoryRequired) {
                            subCategoryRequired.style.display = 'inline';
                            subCategorySelect.required = true;
                            subCategoryHelpText.textContent =
                                'Sub Kategori membantu mengelompokkan materi serupa untuk download massal soal';
                            subCategoryHelpText.parentElement.style.color = 'rgba(28,88,113,0.6)';
                        } else {
                            subCategoryRequired.style.display = 'none';
                            subCategorySelect.required = false;
                            subCategoryHelpText.textContent =
                                'Sub Kategori membantu mengelompokkan materi serupa untuk download massal soal';
                            subCategoryHelpText.parentElement.style.color = 'rgba(107, 114, 128, 1)';
                        }

                        // Add subcategory options ONLY for the selected category
                        activeSubCategories.forEach(subCat => {
                            const option = document.createElement('option');
                            option.value = subCat.id;
                            option.textContent = subCat.name + (subCat.description ? ' - ' + subCat
                                .description : '');

                            // Auto-select if this matches the URL parameter
                            if (selectedSubCategoryId && selectedSubCategoryId == subCat.id) {
                                option.selected = true;
                            }

                            subCategorySelect.appendChild(option);
                        });
                    }
                } else if (isSubCategoryRequired) {
                    // Show container with error message if subcategory is required but none available
                    subCategoryContainer.style.display = 'block';
                    subCategoryRequired.style.display = 'inline';
                    subCategorySelect.required = true;
                    subCategoryHelpText.textContent =
                        'Sub Kategori membantu mengelompokkan materi serupa untuk download massal soal';
                    subCategoryHelpText.parentElement.style.color = 'rgba(28,88,113,0.6)';
                }
            }

            // Add event listener for category change
            categorySelect.addEventListener('change', updateSubCategories);

            // Initialize subcategories on page load
            updateSubCategories();

            // Handle special case when coming with selectedSubCategoryId
            if (selectedSubCategoryId) {
                // console.log('Handling selectedSubCategoryId:', selectedSubCategoryId);

                // Find which category contains this subcategory
                const parentCategory = categoriesData.find(cat =>
                    cat.sub_categories && cat.sub_categories.some(subCat => subCat.id == selectedSubCategoryId)
                );

                if (parentCategory) {
                    // console.log('Found parent category:', parentCategory.name);
                    // Set category first, then update subcategories
                    categorySelect.value = parentCategory.name;
                    updateSubCategories();

                    // Ensure subcategory is selected after DOM update
                    setTimeout(() => {
                        subCategorySelect.value = selectedSubCategoryId;
                        // console.log('Selected subcategory:', selectedSubCategoryId);
                    }, 50);
                }
            }
        });
    </script>

    <!-- Include Help Modal Component -->
    @include('components.help-modal')

    <!-- Include Tutorial Component -->
    @include('components.tutorial')
</body>

</html>
