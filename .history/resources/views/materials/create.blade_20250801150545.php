<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Materi Baru - KemenLH/BPLH E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(28,88,113,0.1);
        }
        .subtle-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='rgba(28,88,113,0.03)' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen subtle-pattern">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <!-- Back Button - Outside Container -->
    <div class="container mx-auto px-4 py-4">
        <a href="{{ route('materials.index') }}" 
           class="inline-flex items-center px-4 py-2 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
           style="background: linear-gradient(135deg, rgba(107,114,128,0.8) 0%, rgba(75,85,99,0.9) 100%); color: white;">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <div class="container mx-auto px-4 pb-8">
        <div class="max-w-2xl mx-auto">
            <div class="glass-effect rounded-2xl shadow-xl p-8">
                <!-- Header -->
                <div class="mb-8 pb-4 border-b" style="border-color: rgba(28,88,113,0.1);">
                    <h1 class="text-3xl font-bold mb-2 flex items-center" style="color: rgba(28,88,113,1);">
                        <i class="fas fa-cloud-upload-alt mr-3 text-emerald-500"></i>
                        Upload Materi Baru
                    </h1>
                    <p class="text-sm" style="color: rgba(28,88,113,0.7);">Tambahkan materi pembelajaran untuk mengembangkan bank soal otomatis</p>
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

                <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-8">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
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
                        <label for="description" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-align-left text-blue-500"></i>
                            <span>Deskripsi (Opsional)</span>
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                            style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);"
                            placeholder="Masukkan deskripsi materi...">{{ old('description') }}</textarea>
                    </div>

                    <!-- PDF File -->
                    <div>
                        <label for="pdf_file" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
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
                                <p class="text-xs" style="color: rgba(28,88,113,0.6);">PDF hingga 10MB</p>
                                <p id="file-name" class="text-sm font-medium" style="color: rgba(28,88,113,0.8);"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-8 border-t" style="border-color: rgba(28,88,113,0.1);">
                        <a href="{{ route('materials.index') }}"
                            class="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                            style="background: linear-gradient(135deg, rgba(107,114,128,0.8) 0%, rgba(75,85,99,0.9) 100%); color: white;">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
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

    <script>
        function updateFileName(input) {
            const fileNameElement = document.getElementById('file-name');
            if (input.files && input.files[0]) {
                fileNameElement.textContent = input.files[0].name;
            } else {
                fileNameElement.textContent = '';
            }
        }
    </script>
</body>

</html>
