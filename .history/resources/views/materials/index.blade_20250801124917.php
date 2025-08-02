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

        @if ($materials->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($materials as $material)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 truncate">
                                    {{ $material->title }}
                                </h3>
                            </div>

                            @if ($material->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ $material->description }}
                                </p>
                            @endif

                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                {{ $material->file_name }}
                            </div>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span>{{ $material->file_size_human }}</span>
                                <span>{{ $material->questions_count }} soal</span>
                            </div>

                            <div class="text-xs text-gray-400 mb-4">
                                Upload: {{ $material->created_at->format('d M Y H:i') }}
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('materials.show', $material->id) }}"
                                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-3 rounded text-sm">
                                    Detail
                                </a>
                                <a href="{{ route('materials.download', $material->id) }}"
                                    class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 px-3 rounded text-sm">
                                    Download
                                </a>
                                <form method="POST" action="{{ route('materials.destroy', $material->id) }}"
                                    onsubmit="event.preventDefault(); showDeleteConfirmation(this, 'Yakin ingin menghapus materi ini? Semua soal yang terkait juga akan dihapus.'); return false;" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded text-sm">
                                        Hapus
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
