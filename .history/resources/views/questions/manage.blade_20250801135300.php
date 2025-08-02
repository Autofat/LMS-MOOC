<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Soal - Education Generator Platform</title>
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
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center">
                <a href="{{ route('materials.index') }}"
                    class="text-blue-500 hover:text-blue-700 mr-4" title="Kembali ke Kelola Materi">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Kelola Soal</h1>
            </div>
            <div class="space-x-4">
                <a href="{{ route('questions.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i>Tambah Soal Baru
                </a>
            </div>
        </div>

        @if ($questions->count() > 0)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b" style="background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(34,108,137,0.05) 100%); border-color: rgba(28,88,113,0.1);">
                    <h2 class="text-lg font-semibold flex items-center" style="color: rgba(28,88,113,0.9);">
                        <i class="fas fa-list-ol mr-2 text-blue-500"></i>
                        Total: {{ $questions->total() }} soal
                    </h2>
                </div>

                <div class="divide-y" style="border-color: rgba(28,88,113,0.1);">
                    @foreach ($questions as $index => $question)
                        <div class="p-6 transition-all hover:shadow-md" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,250,252,0.9) 100%);">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="mb-4">
                                        <h3 class="text-lg font-semibold mb-3 flex items-center" style="color: rgba(28,88,113,0.9);">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold mr-3"
                                                  style="background: linear-gradient(135deg, rgba(28,88,113,0.1) 0%, rgba(34,108,137,0.1) 100%); color: rgba(28,88,113,0.9);">
                                                {{ $index + 1 + ($questions->currentPage() - 1) * $questions->perPage() }}
                                            </span>
                                            {{ $question->question }}
                                        </h3>

                                        @if ($question->material)
                                            <div class="p-4 rounded-xl mb-3" style="background: linear-gradient(135deg, rgba(168,85,247,0.1) 0%, rgba(147,51,234,0.1) 100%); border-left: 4px solid rgba(168,85,247,0.5);">
                                                <p class="text-sm" style="color: rgba(147,51,234,0.9);">
                                                    <span class="font-semibold flex items-center mb-2">
                                                        <i class="fas fa-book-open mr-2"></i>
                                                        Materi:
                                                    </span>
                                                    <a href="{{ route('materials.show', $question->material->id) }}"
                                                        class="hover:underline font-medium" style="color: rgba(147,51,234,0.8);">
                                                        {{ $question->material->title }}
                                                    </a>
                                                    @if ($question->material->category)
                                                        <span class="inline-block text-xs px-3 py-1 rounded-full ml-2 font-medium"
                                                              style="background: rgba(168,85,247,0.2); color: rgba(147,51,234,0.9);">
                                                            {{ $question->material->category }}
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                        @if ($question->difficulty)
                                            <div class="p-4 rounded-xl" style="background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(34,108,137,0.05) 100%); border-left: 4px solid rgba(28,88,113,0.3);">
                                                <p class="text-sm" style="color: rgba(28,88,113,0.8);">
                                                    <span class="font-semibold flex items-center mb-2">
                                                        <i class="fas fa-layer-group mr-2"></i>
                                                        Tingkat Kesulitan:
                                                    </span>
                                                    @php
                                                        $difficulty = strtolower($question->difficulty);
                                                        $bgClass = '';
                                                        $textClass = '';

                                                        if ($difficulty === 'easy') {
                                                            $bgClass = 'bg-green-100';
                                                            $textClass = 'text-green-700';
                                                        } elseif ($difficulty === 'medium') {
                                                            $bgClass = 'bg-yellow-100';
                                                            $textClass = 'text-yellow-700';
                                                        } elseif ($difficulty === 'hard') {
                                                            $bgClass = 'bg-red-100';
                                                            $textClass = 'text-red-700';
                                                        } else {
                                                            $bgClass = 'bg-gray-200';
                                                            $textClass = 'text-gray-800';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="inline-block {{ $bgClass }} {{ $textClass }} text-xs px-2 py-1 rounded-full ml-1">
                                                        {{ $question->difficulty }}
                                                    </span>
                                                </p>
                                            </div>
                                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 my-4">
                            @foreach ($question->options as $key => $option)
                                <div class="flex items-center p-3 rounded-lg text-sm transition-all
                                    {{ $question->answer === $key ? 'border-2' : 'border' }}"
                                    style="{{ $question->answer === $key ? 'background: linear-gradient(135deg, rgba(16,185,129,0.1) 0%, rgba(5,150,105,0.1) 100%); border-color: rgba(16,185,129,0.5); color: rgba(5,150,105,0.9);' : 'background: linear-gradient(135deg, rgba(243,244,246,0.8) 0%, rgba(249,250,251,0.8) 100%); border-color: rgba(28,88,113,0.1); color: rgba(28,88,113,0.7);' }}">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold mr-3"
                                          style="{{ $question->answer === $key ? 'background: rgba(16,185,129,0.2); color: rgba(5,150,105,1);' : 'background: rgba(28,88,113,0.1); color: rgba(28,88,113,0.7);' }}">
                                        {{ $key }}
                                    </span>
                                    <span class="{{ $question->answer === $key ? 'font-semibold' : '' }}">
                                        {{ Str::limit($option, 60) }}
                                    </span>
                                    @if ($question->answer === $key)
                                        <i class="fas fa-check-circle ml-auto text-emerald-500"></i>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if ($question->explanation)
                            <div class="p-4 rounded-xl" style="background: linear-gradient(135deg, rgba(59,130,246,0.1) 0%, rgba(37,99,235,0.1) 100%); border-left: 4px solid rgba(59,130,246,0.5);">
                                <p class="text-sm" style="color: rgba(37,99,235,0.8);">
                                    <span class="font-semibold flex items-center mb-2">
                                        <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                                        Penjelasan:
                                    </span>
                                    {{ $question->explanation }}
                                </p>
                            </div>
                        @endif
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        Dibuat: {{ $question->created_at->format('d M Y H:i') }}
                                        @if ($question->updated_at != $question->created_at)
                                            | Diupdate: {{ $question->updated_at->format('d M Y H:i') }}
                                        @endif
                                    </div>
                                </div>

                                <div class="ml-6 flex flex-col space-y-2">
                                    <a href="{{ route('questions.edit', $question->id) }}"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm text-center">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('questions.destroy', $question->id) }}"
                                        onsubmit="event.preventDefault(); showDeleteConfirmation(this, 'Yakin ingin menghapus soal ini? Aksi ini tidak dapat dibatalkan.'); return false;" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($questions->hasPages())
                    <div class="bg-gray-50 px-6 py-3 border-t">
                        {{ $questions->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg p-8 text-center">
                <div class="text-gray-500 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Belum ada soal</h3>
                <p class="text-gray-600 mb-4">Mulai dengan menambahkan soal baru atau gunakan API untuk import dari n8n.
                </p>

                <div class="space-y-2">
                    <a href="{{ route('questions.create') }}"
                        class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Tambah Soal Manual
                    </a>

                    <div class="text-sm text-gray-600">
                        <p>Atau gunakan endpoint untuk n8n:</p>
                        <code class="bg-gray-100 px-2 py-1 rounded text-xs">
                            POST http://172.29.192.1:8000/api/questions/auto-save
                        </code>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Include Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')
</body>

</html>
