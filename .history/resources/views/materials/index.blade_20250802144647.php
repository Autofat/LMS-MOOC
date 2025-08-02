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
            background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 25%, rgba(42,122,157,1) 50%, rgba(49,139,179,1) 75%, rgba(56,156,201,1) 100%);
        }
        .subtle-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='rgba(28,88,113,0.03)' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
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
                        <i class="fas fa-graduation-cap text-4xl text-white"></i>
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Platform Pembuatan Soal Berbasis AI
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    Menghadirkan sistem pembuatan soal otomatis berdasarkan materi pembelajaran untuk Platform E-Learning KemenLH/BPLH
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('materials.create') }}"
                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold py-3 px-6 rounded-full border border-white/30 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>Upload Materi Baru
                    </a>
                    <a href="{{ route('questions.manage') }}"
                        class="text-white font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105"
                        style="background: rgba(28,88,113,0.8);"
                        onmouseover="this.style.background='rgba(28,88,113,1)'"
                        onmouseout="this.style.background='rgba(28,88,113,0.8)'">
                        <i class="fas fa-question-circle mr-2"></i>Kelola Soal
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
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border" style="border-color: rgba(28,88,113,0.2);">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full" style="background-color: rgba(28,88,113,0.1);">
                            <i class="fas fa-file-alt text-xl" style="color: rgba(28,88,113,1);"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Materi</p>
                            <p class="text-2xl font-bold" style="color: rgba(28,88,113,1);">{{ $materials->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border" style="border-color: rgba(28,88,113,0.2);">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full" style="background-color: rgba(28,88,113,0.1);">
                            <i class="fas fa-list-check text-xl" style="color: rgba(28,88,113,1);"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Soal</p>
                            <p class="text-2xl font-bold" style="color: rgba(28,88,113,1);">{{ $materials->sum('questions_count') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border" style="border-color: rgba(28,88,113,0.2);">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full" style="background-color: rgba(28,88,113,0.1);">
                            <i class="fas fa-chart-line text-xl" style="color: rgba(28,88,113,1);"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Rata-rata Soal</p>
                            <p class="text-2xl font-bold" style="color: rgba(28,88,113,1);">
                                {{ $materials->count() > 0 ? number_format($materials->sum('questions_count') / $materials->count(), 1) : '0' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Materials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($materials as $material)
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border" style="border-color: rgba(28,88,113,0.2);">
                        <!-- Card Header with Professional Theme -->
                        <div class="p-4" style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%);">
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
                            <div class="flex items-center text-sm text-gray-500 mb-4 p-3 rounded-lg" style="background-color: rgba(28,88,113,0.05);">
                                <i class="fas fa-file-pdf mr-2" style="color: rgba(28,88,113,1);"></i>
                                <span class="truncate">{{ $material->file_name }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-weight mr-1" style="color: rgba(28,88,113,0.7);"></i>
                                    <span>{{ $material->file_size_human }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-question-circle mr-1" style="color: rgba(28,88,113,0.7);"></i>
                                    <span>{{ $material->questions_count }} soal</span>
                                </div>
                            </div>

                            <div class="text-xs text-gray-400 mb-4 flex items-center">
                                <i class="fas fa-calendar mr-1" style="color: rgba(28,88,113,0.6);"></i>
                                Upload: {{ $material->created_at->format('d M Y H:i') }}
                            </div>

                            <!-- Action Buttons with Professional Theme -->
                            <div class="flex space-x-2">
                                <a href="{{ route('materials.show', $material->id) }}"
                                    class="flex-1 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105"
                                    style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%); box-shadow: 0 4px 6px rgba(28,88,113,0.3);"
                                    onmouseover="this.style.background='linear-gradient(135deg, rgba(35,105,135,1) 0%, rgba(42,122,157,1) 100%)'"
                                    onmouseout="this.style.background='linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%)'">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                <a href="{{ route('materials.download.questions.excel', $material->id) }}"
                                    class="flex-1 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105"
                                    style="background: linear-gradient(135deg, rgba(42,122,157,1) 0%, rgba(49,139,179,1) 100%); box-shadow: 0 4px 6px rgba(42,122,157,0.3);"
                                    onmouseover="this.style.background='linear-gradient(135deg, rgba(49,139,179,1) 0%, rgba(56,156,201,1) 100%)'"
                                    onmouseout="this.style.background='linear-gradient(135deg, rgba(42,122,157,1) 0%, rgba(49,139,179,1) 100%)'">
                                    <i class="fas fa-file-excel mr-1"></i>Download Soal
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
            <!-- Empty State with Professional Theme -->
            <div class="bg-white/95 backdrop-blur-sm shadow-xl rounded-3xl p-12 text-center border" style="border-color: rgba(28,88,113,0.2);">
                <div class="mb-6" style="color: rgba(28,88,113,1);">
                    <div class="rounded-full p-8 inline-block" style="background-color: rgba(28,88,113,0.1);">
                        <i class="fas fa-graduation-cap text-5xl" style="color: rgba(28,88,113,1);"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Mulai Dengan Generator</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Belum ada materi untuk diproses. Upload materi pembelajaran dan biarkan sistem membuat soal secara otomatis dengan teknologi modern terbaru.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('materials.create') }}"
                        class="text-white font-bold py-3 px-8 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg"
                        style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(42,122,157,1) 100%);">
                        <i class="fas fa-plus mr-2"></i>Upload Materi Pertama
                    </a>
                    <a href="{{ route('questions.manage') }}"
                        class="bg-white border-2 font-bold py-3 px-8 rounded-full transition-all duration-300 hover:bg-gray-50"
                        style="border-color: rgba(28,88,113,1); color: rgba(28,88,113,1);">
                        <i class="fas fa-question-circle mr-2"></i>Kelola Soal
                    </a>
                </div>
                
                <!-- Decorative Elements -->
                <div class="mt-8 flex justify-center space-x-4" style="color: rgba(28,88,113,0.4);">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                    <i class="fas fa-cogs text-3xl"></i>
                    <i class="fas fa-clipboard-check text-2xl"></i>
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
        @endif
    </div>

    <!-- Include Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')
</body>

</html>
