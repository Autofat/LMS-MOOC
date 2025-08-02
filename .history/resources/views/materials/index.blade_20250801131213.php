<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Materi - KemenLH/BPLH E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .professional-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 25%, #2563eb 50%, #3b82f6 75%, #60a5fa 100%);
        }
        .subtle-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%231e40af' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen subtle-pattern">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <!-- Hero Section -->
    <div class="professional-gradient text-white py-16 mb-8">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-6 border border-white/30">
                        <i class="fas fa-brain text-4xl text-blue-100"></i>
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Platform Pembuatan Soal AI Generator
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    Sistem Artificial Intelligence untuk membuat soal otomatis dari materi pembelajaran dengan teknologi modern dan efisien
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('materials.create') }}"
                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold py-3 px-6 rounded-full border border-white/30 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>Upload Materi Baru
                    </a>
                    <a href="{{ route('questions.manage') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-robot mr-2"></i>Kelola Soal AI
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pb-8">
        @if ($materials->count() > 0)
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-blue-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Materi</p>
                            <p class="text-2xl font-bold text-blue-700">{{ $materials->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-indigo-100">
                    <div class="flex items-center">
                        <div class="bg-indigo-100 p-3 rounded-full">
                            <i class="fas fa-robot text-indigo-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Soal AI Generated</p>
                            <p class="text-2xl font-bold text-indigo-700">{{ $materials->sum('questions_count') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-slate-100">
                    <div class="flex items-center">
                        <div class="bg-slate-100 p-3 rounded-full">
                            <i class="fas fa-tags text-slate-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Kategori Aktif</p>
                            <p class="text-2xl font-bold text-slate-700">{{ $materials->whereNotNull('category')->groupBy('category')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Materials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($materials as $material)
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                        <!-- Card Header with Professional Theme -->
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4">
                            <div class="flex items-center justify-between">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-2">
                                    <i class="fas fa-file-text text-white text-lg"></i>
                                </div>
                                @if($material->category)
                                    <span class="bg-white/20 backdrop-blur-sm text-white text-xs px-3 py-1 rounded-full">
                                        {{ $material->category }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 truncate mb-2">
                                    {{ $material->title }}
                                </h3>
                                @if ($material->description)
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                                        {{ $material->description }}
                                    </p>
                                @endif
                            </div>

                            <!-- File Info with Professional Icons -->
                            <div class="flex items-center text-sm text-gray-500 mb-4 bg-blue-50 p-3 rounded-lg">
                                <i class="fas fa-file-pdf text-blue-600 mr-2"></i>
                                <span class="truncate">{{ $material->file_name }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-weight text-blue-500 mr-1"></i>
                                    <span>{{ $material->file_size_human }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-robot text-indigo-500 mr-1"></i>
                                    <span>{{ $material->questions_count }} soal AI</span>
                                </div>
                            </div>

                            <div class="text-xs text-gray-400 mb-4 flex items-center">
                                <i class="fas fa-calendar text-blue-400 mr-1"></i>
                                Upload: {{ $material->created_at->format('d M Y H:i') }}
                            </div>

                            <!-- Action Buttons with Professional Theme -->
                            <div class="flex space-x-2">
                                <a href="{{ route('materials.show', $material->id) }}"
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                <a href="{{ route('materials.download', $material->id) }}"
                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                                <form method="POST" action="{{ route('materials.destroy', $material->id) }}"
                                    onsubmit="event.preventDefault(); showDeleteConfirmation(this, 'Yakin ingin menghapus materi ini? Semua soal yang terkait juga akan dihapus.'); return false;" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        
        <!-- Pagination -->
        @if ($materials->hasPages())
            <div class="mt-8">
                {{ $materials->links() }}
            </div>
        @endif
        @else
            <!-- Empty State with Theme Colors -->
            <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-3xl p-12 text-center border border-teal-100">
                <div class="text-teal-500 mb-6">
                    <div class="bg-teal-100 rounded-full p-8 inline-block">
                        <i class="fas fa-book-open text-5xl text-teal-600"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Mulai Perjalanan Pembelajaran</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Belum ada materi pembelajaran lingkungan hidup. Mari mulai dengan mengupload materi edukasi pertama untuk menumbuhkan kesadaran lingkungan.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('materials.create') }}"
                        class="bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-bold py-3 px-8 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Upload Materi Pertama
                    </a>
                    <a href="{{ route('questions.manage') }}"
                        class="bg-white border-2 border-teal-500 text-teal-600 hover:bg-teal-50 font-bold py-3 px-8 rounded-full transition-all duration-300">
                        <i class="fas fa-question-circle mr-2"></i>Kelola Soal
                    </a>
                </div>
                
                <!-- Decorative Elements -->
                <div class="mt-8 flex justify-center space-x-4 text-teal-300">
                    <i class="fas fa-book text-2xl"></i>
                    <i class="fas fa-graduation-cap text-3xl"></i>
                    <i class="fas fa-globe-americas text-2xl"></i>
                    <i class="fas fa-award text-2xl"></i>
                </div>
            </div>
        @endif
    </div>

    <!-- Include Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')
</body>

</html>
