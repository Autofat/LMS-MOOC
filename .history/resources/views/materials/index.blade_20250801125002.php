<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Materi - KemenLH/BPLH E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .nature-gradient {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 25%, #15803d  50%, #166534 75%, #14532d 100%);
        }
        .leaf-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 min-h-screen leaf-pattern">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <!-- Hero Section -->
    <div class="nature-gradient text-white py-16 mb-8">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-6">
                        <i class="fas fa-leaf text-4xl text-green-100"></i>
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    🌿 Pusat Pembelajaran Lingkungan Hidup
                </h1>
                <p class="text-xl text-green-100 mb-8 max-w-3xl mx-auto">
                    Platform e-learning untuk edukasi dan pelatihan dalam bidang lingkungan hidup dan konservasi alam
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('materials.create') }}"
                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold py-3 px-6 rounded-full border border-white/30 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>Upload Materi Baru
                    </a>
                    <a href="{{ route('questions.manage') }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105">
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
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-green-100">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-file-alt text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Materi</p>
                            <p class="text-2xl font-bold text-green-700">{{ $materials->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-emerald-100">
                    <div class="flex items-center">
                        <div class="bg-emerald-100 p-3 rounded-full">
                            <i class="fas fa-question-circle text-emerald-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Soal</p>
                            <p class="text-2xl font-bold text-emerald-700">{{ $materials->sum('questions_count') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-teal-100">
                    <div class="flex items-center">
                        <div class="bg-teal-100 p-3 rounded-full">
                            <i class="fas fa-leaf text-teal-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Kategori Aktif</p>
                            <p class="text-2xl font-bold text-teal-700">{{ $materials->whereNotNull('category')->groupBy('category')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Materials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($materials as $material)
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-green-100">
                        <!-- Card Header with Nature Theme -->
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-4">
                            <div class="flex items-center justify-between">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-2">
                                    <i class="fas fa-seedling text-white text-lg"></i>
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

                            <!-- File Info with Nature Icons -->
                            <div class="flex items-center text-sm text-gray-500 mb-4 bg-green-50 p-3 rounded-lg">
                                <i class="fas fa-file-pdf text-green-600 mr-2"></i>
                                <span class="truncate">{{ $material->file_name }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-weight text-green-500 mr-1"></i>
                                    <span>{{ $material->file_size_human }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-list-ul text-emerald-500 mr-1"></i>
                                    <span>{{ $material->questions_count }} soal</span>
                                </div>
                            </div>

                            <div class="text-xs text-gray-400 mb-4 flex items-center">
                                <i class="fas fa-calendar text-green-400 mr-1"></i>
                                Upload: {{ $material->created_at->format('d M Y H:i') }}
                            </div>

                            <!-- Action Buttons with Nature Theme -->
                            <div class="flex space-x-2">
                                <a href="{{ route('materials.show', $material->id) }}"
                                    class="flex-1 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                <a href="{{ route('materials.download', $material->id) }}"
                                    class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                                <form method="POST" action="{{ route('materials.destroy', $material->id) }}"
                                    onsubmit="event.preventDefault(); showDeleteConfirmation(this, 'Yakin ingin menghapus materi ini? Semua soal yang terkait juga akan dihapus.'); return false;" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
    </div>

    <!-- Pagination -->
    @if ($materials->hasPages())
        <div class="mt-8">
            {{ $materials->links() }}
        </div>
    @endif
@else
    <div class="bg-white shadow-md rounded-lg p-8 text-center">
        <div class="text-gray-500 mb-4">
            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-800 mb-2">Belum ada materi</h3>
        <p class="text-gray-600 mb-4">Mulai dengan mengupload materi PDF pertama Anda.</p>

        <a href="{{ route('materials.create') }}"
            class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Upload Materi Baru
        </a>
    </div>
    @endif
    </div>

    <!-- Include Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')
</body>

</html>
