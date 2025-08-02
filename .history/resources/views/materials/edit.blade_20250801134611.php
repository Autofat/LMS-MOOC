<!DOCTYPE html>
<html lang="en">

<head>
    <                @if ($errors->any())
                    <div class="rounded-xl px-6 py-4 mb-6" style="background: linear-gradient(135deg, rgba(239,68,68,0.1) 0%, rgba(220,38,38,0.1) 100%); border: 1px solid rgba(239,68,68,0.3);">
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

                <form action="{{ route('materials.update', $material->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Current File Info -->
                    <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(34,108,137,0.05) 100%); border: 1px solid rgba(28,88,113,0.1);">
                        <h3 class="font-semibold mb-3 flex items-center" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Informasi File Saat Ini
                        </h3>8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Materi - {{ $material->title }} - Education Generator Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .professional-gradient {
            background: linear-gradient(135deg, rgba(28,88,113,0.9) 0%, rgba(34,108,137,0.95) 50%, rgba(52,211,153,0.9) 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(28,88,113,0.1);
        }
    </style>
</head>

<body class="min-h-screen professional-gradient">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="glass-effect rounded-2xl shadow-xl p-8">
                <!-- Header -->
                <div class="flex items-center mb-8 pb-4 border-b" style="border-color: rgba(28,88,113,0.1);">
                    <a href="{{ route('materials.show', $material->id) }}"
                        class="inline-flex items-center px-4 py-2 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 mr-4"
                        style="background: linear-gradient(135deg, rgba(107,114,128,0.8) 0%, rgba(75,85,99,0.9) 100%); color: white;">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold mb-2 flex items-center" style="color: rgba(28,88,113,1);">
                            <i class="fas fa-edit mr-3 text-amber-500"></i>
                            Edit Materi
                        </h1>
                        <p class="text-sm" style="color: rgba(28,88,113,0.7);">{{ Str::limit($material->title, 50) }}</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="rounded-xl px-6 py-4 mb-6" style="background: linear-gradient(135deg, rgba(239,68,68,0.1) 0%, rgba(220,38,38,0.1) 100%); border: 1px solid rgba(239,68,68,0.3);">
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

                <form action="{{ route('materials.update', $material->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Current File Info -->
                    <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(34,108,137,0.05) 100%); border: 1px solid rgba(28,88,113,0.1);">
                        <h3 class="font-semibold mb-4 flex items-center" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            File Saat Ini
                        </h3>
                        <div class="flex items-center space-x-4 p-4 rounded-lg" style="background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-pdf text-3xl text-red-500"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold" style="color: rgba(28,88,113,0.9);">{{ $material->file_name }}</p>
                                <p class="text-sm" style="color: rgba(28,88,113,0.7);">{{ $material->file_size_human }}</p>
                            </div>
                            <a href="{{ route('materials.download', $material->id) }}"
                                class="inline-flex items-center px-4 py-2 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                style="background: linear-gradient(135deg, rgba(16,185,129,0.8) 0%, rgba(5,150,105,0.9) 100%); color: white;">
                                <i class="fas fa-download mr-2"></i>
                                Download
                            </a>
                        </div>
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-graduation-cap text-emerald-500"></i>
                            <span>Judul Materi *</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $material->title) }}"
                            required
                            class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                            style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);"
                            placeholder="Masukkan judul materi...">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-align-left text-blue-500"></i>
                            <span>Deskripsi (Opsional)</span>
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                            style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);"
                            placeholder="Masukkan deskripsi materi...">{{ old('description', $material->description) }}</textarea>
                    </div>

                    <!-- Replace PDF File -->
                    <div>
                        <label for="pdf_file" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-exchange-alt text-amber-500"></i>
                            <span>Ganti dengan File PDF Baru (Opsional)</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-dashed rounded-xl hover:border-opacity-60 transition-all"
                             style="border-color: rgba(28,88,113,0.3); background: linear-gradient(135deg, rgba(245,158,11,0.03) 0%, rgba(251,191,36,0.03) 100%);">
                            <div class="space-y-2 text-center">
                                <div class="mx-auto h-16 w-16 rounded-full flex items-center justify-center mb-4"
                                     style="background: linear-gradient(135deg, rgba(239,68,68,0.1) 0%, rgba(220,38,38,0.1) 100%);">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-red-500"></i>
                                </div>
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm" style="color: rgba(28,88,113,0.7);">
                                    <label for="pdf_file"
                                        class="relative cursor-pointer rounded-lg font-semibold px-4 py-2 transition-all hover:shadow-md"
                                        style="background: linear-gradient(135deg, rgba(28,88,113,0.1) 0%, rgba(34,108,137,0.1) 100%); color: rgba(28,88,113,0.9);">
                                        <span><i class="fas fa-upload mr-2"></i>Pilih file PDF baru</span>
                                        <input id="pdf_file" name="pdf_file" type="file" class="sr-only" accept=".pdf" onchange="validateAndShowFile(this)">
                                    </label>
                                    <p class="pl-2 self-center">atau drag and drop</p>
                                </div>
                                <p class="text-xs" style="color: rgba(28,88,113,0.6);">PDF hingga 10MB (kosongkan jika tidak ingin mengganti)</p>
                                <p id="file-name" class="text-sm font-medium text-emerald-600"></p>
                                <p id="file-error" class="text-sm font-medium text-red-600 hidden"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-8 border-t" style="border-color: rgba(28,88,113,0.1);">
                        <a href="{{ route('materials.show', $material->id) }}"
                            class="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                            style="background: linear-gradient(135deg, rgba(107,114,128,0.8) 0%, rgba(75,85,99,0.9) 100%); color: white;">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" onclick="console.log('Form submitted with file:', document.getElementById('pdf_file').files[0]?.name || 'No file selected')"
                            class="inline-flex items-center px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                            style="background: linear-gradient(135deg, rgba(28,88,113,0.8) 0%, rgba(34,108,137,0.9) 100%); color: white;">
                            <i class="fas fa-save mr-2"></i>Update Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validateAndShowFile(input) {
            const fileNameElement = document.getElementById('file-name');
            const fileErrorElement = document.getElementById('file-error');
            
            // Clear previous messages
            fileNameElement.innerHTML = '';
            fileErrorElement.innerHTML = '';
            fileErrorElement.classList.add('hidden');
            fileNameElement.className = 'text-sm text-green-700 font-medium';
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSizeMB = (file.size / 1024 / 1024);
                
                // Validate file type
                if (file.type !== 'application/pdf') {
                    fileErrorElement.innerHTML = `
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Error: File harus berformat PDF. File yang dipilih: ${file.type || 'Unknown'}
                    `;
                    fileErrorElement.classList.remove('hidden');
                    input.value = ''; // Clear the input
                    return;
                }
                
                // Validate file size (10MB = 10 * 1024 * 1024 bytes)
                if (file.size > 10 * 1024 * 1024) {
                    fileErrorElement.innerHTML = `
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Error: File terlalu besar (${fileSizeMB.toFixed(2)} MB). Maksimal 10MB.
                    `;
                    fileErrorElement.classList.remove('hidden');
                    input.value = ''; // Clear the input
                    return;
                }
                
                // Show success message
                fileNameElement.innerHTML = `
                    <i class="fas fa-check-circle mr-1"></i>
                    File baru dipilih: <strong>${file.name}</strong> (${fileSizeMB.toFixed(2)} MB)
                `;
                fileNameElement.className = 'text-sm text-green-700 font-medium bg-green-50 px-3 py-2 rounded-md mt-2';
            } else {
                fileNameElement.textContent = '';
                fileNameElement.className = 'text-sm text-green-700 font-medium';
            }
        }
        
        // Add form validation before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('pdf_file');
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                if (file.type !== 'application/pdf' || file.size > 10 * 1024 * 1024) {
                    e.preventDefault();
                    alert('Please select a valid PDF file under 10MB before submitting.');
                    return false;
                }
            }
        });
    </script>
</body>

</html>
