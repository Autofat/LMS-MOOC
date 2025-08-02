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
                    class="mr-4 p-2 rounded-lg transition-all duration-200 hover:scale-110"
                    style="color: rgba(255,255,255,0.9); background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);"
                    onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.1)'"
                    title="Kembali ke Kelola Materi">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-white flex items-center">
                    <i class="fas fa-cogs mr-3 text-white/80"></i>
                    Kelola Soal
                </h1>
            </div>
            <div class="space-x-4">
                <a href="{{ route('questions.create') }}"
                    class="inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105"
                    style="background: rgba(255,255,255,0.9); color: rgba(28,88,113,1); box-shadow: 0 4px 15px rgba(0,0,0,0.1);"
                    onmouseover="this.style.background='rgba(255,255,255,1)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.15)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.9)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.1)'">
                    <i class="fas fa-plus mr-2"></i>Tambah Soal Baru
                </a>
            </div>
        </div>

        @if ($questions->count() > 0)
            <div class="rounded-2xl overflow-hidden backdrop-blur-md" style="background: rgba(255,255,255,0.95); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                <div class="px-6 py-5" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,250,252,0.9) 100%); border-bottom: 1px solid rgba(28,88,113,0.08);">
                    <h2 class="text-xl font-bold flex items-center" style="color: rgba(28,88,113,0.9);">
                        <i class="fas fa-list-check mr-3" style="color: rgba(28,88,113,0.7);"></i>
                        Daftar Soal
                    </h2>
                    <p class="text-sm mt-1" style="color: rgba(28,88,113,0.6);">
                        Total: {{ $questions->total() }} soal | Kelola dan edit soal pembelajaran
                    </p>
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

                                    <div class="text-sm flex items-center space-x-4" style="color: rgba(28,88,113,0.6);">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar-plus mr-1"></i>
                                            Dibuat: {{ $question->created_at->format('d M Y H:i') }}
                                        </span>
                                        @if ($question->updated_at != $question->created_at)
                                            <span class="flex items-center">
                                                <i class="fas fa-edit mr-1"></i>
                                                Diupdate: {{ $question->updated_at->format('d M Y H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="ml-6 flex flex-col space-y-3">
                                    <a href="{{ route('questions.edit', $question->id) }}"
                                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105"
                                        style="background: linear-gradient(135deg, rgba(251,191,36,1) 0%, rgba(245,158,11,1) 100%); color: white; box-shadow: 0 4px 6px rgba(245,158,11,0.3);"
                                        onmouseover="this.style.boxShadow='0 6px 12px rgba(245,158,11,0.4)'"
                                        onmouseout="this.style.boxShadow='0 4px 6px rgba(245,158,11,0.3)'">
                                        <i class="fas fa-edit mr-2"></i>
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('questions.destroy', $question->id) }}"
                                        onsubmit="event.preventDefault(); showDeleteConfirmation(this, 'Yakin ingin menghapus soal ini? Aksi ini tidak dapat dibatalkan.'); return false;" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-full px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105"
                                            style="background: linear-gradient(135deg, rgba(239,68,68,1) 0%, rgba(220,38,38,1) 100%); color: white; box-shadow: 0 4px 6px rgba(220,38,38,0.3);"
                                            onmouseover="this.style.boxShadow='0 6px 12px rgba(220,38,38,0.4)'"
                                            onmouseout="this.style.boxShadow='0 4px 6px rgba(220,38,38,0.3)'">
                                            <i class="fas fa-trash mr-2"></i>
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
                    <div class="p-6 border-t" style="background: linear-gradient(135deg, rgba(28,88,113,0.03) 0%, rgba(34,108,137,0.03) 100%); border-color: rgba(28,88,113,0.1);">
                        {{ $questions->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="rounded-xl p-8 text-center backdrop-blur-md" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,250,252,0.9) 100%); border: 1px solid rgba(28,88,113,0.1); box-shadow: 0 8px 32px rgba(28,88,113,0.1);">
                <div class="mb-6" style="color: rgba(28,88,113,0.5);">
                    <i class="fas fa-question-circle text-6xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3" style="color: rgba(28,88,113,0.9);">Belum ada soal</h3>
                <p class="mb-6" style="color: rgba(28,88,113,0.6);">
                    Mulai dengan menambahkan soal baru atau gunakan API untuk import dari n8n.
                </p>

                <div class="space-y-4">
                    <a href="{{ route('questions.create') }}"
                        class="inline-flex items-center px-6 py-3 rounded-lg text-white font-semibold transition-all duration-200 transform hover:scale-105"
                        style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(34,108,137,1) 100%); box-shadow: 0 4px 15px rgba(28,88,113,0.3);"
                        onmouseover="this.style.boxShadow='0 6px 20px rgba(28,88,113,0.4)'"
                        onmouseout="this.style.boxShadow='0 4px 15px rgba(28,88,113,0.3)'">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Soal Manual
                    </a>

                    <div class="p-4 rounded-lg" style="background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(34,108,137,0.05) 100%); border: 1px solid rgba(28,88,113,0.1);">
                        <p class="text-sm font-medium mb-2" style="color: rgba(28,88,113,0.8);">
                            <i class="fas fa-code mr-2"></i>
                            Atau gunakan endpoint untuk n8n:
                        </p>
                        <code class="inline-block px-3 py-2 rounded text-xs font-mono" style="background: rgba(28,88,113,0.1); color: rgba(28,88,113,0.8);">
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
