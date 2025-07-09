<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Materi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Kelola Materi</h1>
            <div class="space-x-4">
                <a href="{{ route('materials.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Upload Materi Baru
                </a>
                <a href="{{ route('questions.manage') }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Kelola Soal
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if ($materials->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($materials as $material)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 truncate">
                                    {{ $material->title }}
                                </h3>
                                @if ($material->category)
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ $material->category }}
                                    </span>
                                @endif
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
                                <a href="{{ route('materials.edit', $material->id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded text-sm">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('materials.destroy', $material->id) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus materi ini?')" class="inline">
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
</body>

</html>
