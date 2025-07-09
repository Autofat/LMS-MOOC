<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $material->title }} - Detail Materi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('materials.index') }}" class="text-blue-500 hover:text-blue-700 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">{{ $material->title }}</h1>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Material Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Info Materi</h2>

                    @if ($material->category)
                        <div class="mb-3">
                            <span class="text-sm font-medium text-gray-600">Kategori:</span>
                            <span class="inline-block bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded-full ml-2">
                                {{ $material->category }}
                            </span>
                        </div>
                    @endif

                    @if ($material->description)
                        <div class="mb-4">
                            <span class="text-sm font-medium text-gray-600">Deskripsi:</span>
                            <p class="text-gray-700 mt-1">{{ $material->description }}</p>
                        </div>
                    @endif

                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>File:</span>
                            <span>{{ $material->file_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ukuran:</span>
                            <span>{{ $material->file_size_human }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Soal:</span>
                            <span class="font-semibold text-blue-600">{{ $material->questions->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Upload:</span>
                            <span>{{ $material->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-2">
                        <a href="{{ route('materials.download', $material->id) }}"
                            class="w-full bg-green-500 hover:bg-green-600 text-white text-center py-2 px-4 rounded flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Download PDF
                        </a>

                        <a href="{{ route('materials.edit', $material->id) }}"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 px-4 rounded">
                            Edit Materi
                        </a>

                        <a href="{{ route('questions.create') }}?material_id={{ $material->id }}"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded">
                            Tambah Soal untuk Materi Ini
                        </a>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Daftar Soal ({{ $material->questions->count() }})
                        </h2>
                        <a href="{{ route('questions.create') }}?material_id={{ $material->id }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                            + Tambah Soal
                        </a>
                    </div>

                    @if ($material->questions->count() > 0)
                        <div class="space-y-4">
                            @foreach ($material->questions as $index => $question)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-800 mb-2">
                                                {{ $index + 1 }}. {{ $question->question }}
                                            </h3>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3">
                                                @foreach ($question->options as $key => $option)
                                                    <div
                                                        class="flex items-center p-2 rounded text-sm
                                                        {{ $question->answer === $key ? 'bg-green-100 border border-green-300' : 'bg-gray-50' }}">
                                                        <span class="font-bold mr-2">{{ $key }}.</span>
                                                        <span
                                                            class="{{ $question->answer === $key ? 'font-semibold text-green-800' : '' }}">
                                                            {{ Str::limit($option, 80) }}
                                                        </span>
                                                        @if ($question->answer === $key)
                                                            <span class="ml-auto text-green-600">✓</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                @if ($question->difficulty)
                                                    @php
                                                        $difficulty = strtolower($question->difficulty);
                                                        $bgClass = '';
                                                        $textClass = '';

                                                        if ($difficulty === 'mudah') {
                                                            $bgClass = 'bg-green-200';
                                                            $textClass = 'text-green-800';
                                                        } elseif ($difficulty === 'menengah') {
                                                            $bgClass = 'bg-yellow-200';
                                                            $textClass = 'text-yellow-800';
                                                        } elseif ($difficulty === 'sulit') {
                                                            $bgClass = 'bg-red-200';
                                                            $textClass = 'text-red-800';
                                                        } else {
                                                            $bgClass = 'bg-gray-200';
                                                            $textClass = 'text-gray-800';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="inline-block {{ $bgClass }} {{ $textClass }} text-xs px-2 py-1 rounded-full">
                                                        {{ $question->difficulty }}
                                                    </span>
                                                @endif
                                                <span>{{ $question->created_at->format('d M Y') }}</span>
                                            </div>
                                        </div>

                                        <div class="ml-4 flex flex-col space-y-1">
                                            <a href="{{ route('questions.edit', $question->id) }}"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs text-center">
                                                Edit
                                            </a>
                                            <form method="POST"
                                                action="{{ route('questions.destroy', $question->id) }}"
                                                onsubmit="return confirm('Yakin ingin menghapus soal ini?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Belum ada soal</h3>
                            <p class="text-gray-600 mb-4">Mulai dengan menambahkan soal untuk materi ini.</p>

                            <a href="{{ route('questions.create') }}?material_id={{ $material->id }}"
                                class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Soal Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>
