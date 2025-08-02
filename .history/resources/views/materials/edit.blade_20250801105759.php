<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Materi - {{ $material->title }} - KemenLH/BPLH E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-8">
                <!-- Header sederhana -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <a href="{{ route('materials.index') }}"
                            class="text-blue-500 hover:text-blue-700 mr-4" title="Kembali ke Daftar Materi">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Edit Materi</h1>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($material->title, 50) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('materials.show', $material->id) }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-eye mr-2"></i>Detail
                    </a>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('materials.update', $material->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Current File Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">File Saat Ini</h3>
                        <div class="flex items-center space-x-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-800">{{ $material->file_name }}</p>
                                <p class="text-sm text-gray-600">{{ $material->file_size_human }}</p>
                            </div>
                            <a href="{{ route('materials.download', $material->id) }}"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                Download
                            </a>
                        </div>
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Materi *
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $material->title) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan judul materi...">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi (Opsional)
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan deskripsi materi...">{{ old('description', $material->description) }}</textarea>
                    </div>

                    <!-- Current PDF File Info -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">File PDF Saat Ini:</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-900">{{ $material->file_name }}</span>
                                <span class="ml-2 text-xs text-gray-500">({{ $material->file_size_human }})</span>
                            </div>
                            <a href="{{ route('materials.download', $material->id) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-download mr-1"></i>Download
                            </a>
                        </div>
                    </div>

                    <!-- Replace PDF File -->
                    <div>
                        <label for="pdf_file" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-exchange-alt mr-2"></i>Ganti dengan File PDF Baru (Opsional)
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="pdf_file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Pilih file PDF baru</span>
                                        <input id="pdf_file" name="pdf_file" type="file" class="sr-only" accept=".pdf" onchange="validateAndShowFile(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF hingga 10MB (kosongkan jika tidak ingin mengganti)</p>
                                <p id="file-name" class="text-sm text-green-700 font-medium"></p>
                                <p id="file-error" class="text-sm text-red-600 font-medium hidden"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t">
                        <a href="{{ route('materials.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded transition-colors">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" onclick="console.log('Form submitted with file:', document.getElementById('pdf_file').files[0]?.name || 'No file selected')"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded transition-colors">
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
