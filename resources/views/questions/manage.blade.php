<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Soal - KemenLH/BPLH E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(28, 88, 113, 0.1);
        }

        .subtle-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='rgba(28,88,113,0.03)' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* Toast Animation Styles */
        .toast-enter {
            transform: translateX(100%);
            opacity: 0;
        }
        
        .toast-show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .toast-hide {
            transform: translateX(100%);
            opacity: 0;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen subtle-pattern">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <!-- Toast Messages -->
    @if(session('success'))
        @include('components.toast', ['id' => 'questionsSuccessToast', 'type' => 'success', 'message' => session('success')])
    @endif

    @if(session('error'))
        @include('components.toast', ['id' => 'questionsErrorToast', 'type' => 'error', 'message' => session('error')])
    @endif

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center">
                <a href="{{ route('materials.index') }}"
                    class="mr-4 p-2 rounded-lg transition-all duration-200 hover:scale-110"
                    style="color: rgba(28,88,113,1); background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); box-shadow: 0 2px 8px rgba(28,88,113,0.15);"
                    onmouseover="this.style.background='rgba(255,255,255,1)'; this.style.boxShadow='0 4px 12px rgba(28,88,113,0.2)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.8)'; this.style.boxShadow='0 2px 8px rgba(28,88,113,0.15)'"
                    title="Kembali ke Kelola Materi">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold flex items-center" style="color: rgba(28,88,113,1);">
                    <i class="fas fa-cogs mr-3" style="color: rgba(28,88,113,0.8);"></i>
                    Kelola Soal
                </h1>
            </div>
            <div class="space-x-4">
                <a href="{{ route('questions.create') }}"
                    class="inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105"
                    style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%); color: white; box-shadow: 0 4px 15px rgba(28,88,113,0.3);"
                    onmouseover="this.style.background='linear-gradient(135deg, rgba(35,105,135,1) 0%, rgba(42,122,157,1) 100%)'; this.style.boxShadow='0 6px 20px rgba(28,88,113,0.4)'"
                    onmouseout="this.style.background='linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%)'; this.style.boxShadow='0 4px 15px rgba(28,88,113,0.3)'">
                    <i class="fas fa-plus mr-2"></i>Tambah Soal Baru
                </a>
            </div>
        </div>

        @if ($questions->count() > 0)
            <div class="rounded-2xl overflow-hidden backdrop-blur-md"
                style="background: rgba(255,255,255,0.95); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                <div class="px-6 py-5"
                    style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,250,252,0.9) 100%); border-bottom: 1px solid rgba(28,88,113,0.08);">
                    <h2 class="text-xl font-bold flex items-center" style="color: rgba(28,88,113,0.9);">
                        <i class="fas fa-list-check mr-3" style="color: rgba(28,88,113,0.7);"></i>
                        Daftar Soal
                    </h2>
                    <p class="text-sm mt-1" style="color: rgba(28,88,113,0.6);">
                        Total: {{ $questions->total() }} soal | Kelola dan edit soal pembelajaran
                    </p>
                </div>

                <div class="divide-y" style="border-color: rgba(28,88,113,0.08);">
                    @foreach ($questions as $index => $question)
                        <div class="p-6 transition-all hover:shadow-lg"
                            style="background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(250,252,255,0.98) 100%); border-left: 4px solid rgba(28,88,113,0.1);"
                            onmouseover="this.style.borderLeftColor='rgba(28,88,113,0.3)'"
                            onmouseout="this.style.borderLeftColor='rgba(28,88,113,0.1)'">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="mb-4">
                                        <h3 class="text-lg font-semibold mb-3 flex items-center"
                                            style="color: rgba(28,88,113,0.85);">
                                            <span
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold mr-3"
                                                style="background: linear-gradient(135deg, rgba(28,88,113,0.08) 0%, rgba(34,108,137,0.08) 100%); color: rgba(28,88,113,0.75); border: 1px solid rgba(28,88,113,0.15);">
                                                {{ $index + 1 + ($questions->currentPage() - 1) * $questions->perPage() }}
                                            </span>
                                            {{ $question->question }}
                                        </h3>

                                        @if ($question->material)
                                            <div class="p-4 rounded-xl mb-3"
                                                style="background: linear-gradient(135deg, rgba(99,102,241,0.06) 0%, rgba(79,70,229,0.06) 100%); border-left: 3px solid rgba(99,102,241,0.3);">
                                                <p class="text-sm" style="color: rgba(79,70,229,0.8);">
                                                    <span class="font-semibold flex items-center mb-2">
                                                        <i class="fas fa-book-open mr-2"
                                                            style="color: rgba(99,102,241,0.7);"></i>
                                                        Materi:
                                                    </span>
                                                    <a href="{{ route('materials.show', $question->material->id) }}"
                                                        class="hover:underline font-medium"
                                                        style="color: rgba(147,51,234,0.8);">
                                                        {{ $question->material->title }}
                                                    </a>
                                                    @if ($question->material->category)
                                                        <span
                                                            class="inline-block text-xs px-3 py-1 rounded-full ml-2 font-medium"
                                                            style="background: rgba(168,85,247,0.2); color: rgba(147,51,234,0.9);">
                                                            {{ $question->material->category }}
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                        @if ($question->difficulty)
                                            <div class="p-4 rounded-xl"
                                                style="background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(34,108,137,0.05) 100%); border-left: 4px solid rgba(28,88,113,0.3);">
                                                <p class="text-sm" style="color: rgba(28,88,113,0.8);">
                                                    <span class="font-semibold flex items-center mb-2">
                                                        <i class="fas fa-layer-group mr-2"></i>
                                                        Tingkat Kesulitan:
                                                    </span>
                                                    @php
                                                        $difficulty = $question->difficulty;

                                                        if ($difficulty === 'Mudah' || $difficulty === 'easy') {
                                                            $bgStyle =
                                                                'background: rgba(34,197,94,0.12); color: rgba(21,128,61,0.9);';
                                                            $icon = 'fas fa-circle';
                                                        } elseif (
                                                            $difficulty === 'Menengah' ||
                                                            $difficulty === 'medium'
                                                        ) {
                                                            $bgStyle =
                                                                'background: rgba(251,191,36,0.12); color: rgba(180,83,9,0.9);';
                                                            $icon = 'fas fa-adjust';
                                                        } elseif ($difficulty === 'Sulit' || $difficulty === 'hard') {
                                                            $bgStyle =
                                                                'background: rgba(248,113,113,0.12); color: rgba(185,28,28,0.9);';
                                                            $icon = 'fas fa-fire';
                                                        } else {
                                                            $bgStyle =
                                                                'background: rgba(156,163,175,0.12); color: rgba(75,85,99,0.9);';
                                                            $icon = 'fas fa-question';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center text-xs px-3 py-1 rounded-full font-medium ml-1"
                                                        style="{{ $bgStyle }}">
                                                        <i class="{{ $icon }} mr-1"></i>
                                                        {{ $question->difficulty }}
                                                    </span>
                                                </p>
                                            </div>
                                        @endif
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 my-4">
                                            @foreach ($question->options as $key => $option)
                                                <div class="flex items-center p-3 rounded-lg text-sm transition-all
                                    {{ $question->answer === $key ? 'border-2' : 'border' }}"
                                                    style="{{ $question->answer === $key ? 'background: linear-gradient(135deg, rgba(34,197,94,0.06) 0%, rgba(21,128,61,0.06) 100%); border-color: rgba(34,197,94,0.3); color: rgba(21,128,61,0.85);' : 'background: linear-gradient(135deg, rgba(248,250,252,0.9) 0%, rgba(241,245,249,0.9) 100%); border-color: rgba(28,88,113,0.08); color: rgba(28,88,113,0.7);' }}">
                                                    <span
                                                        class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold mr-3"
                                                        style="{{ $question->answer === $key ? 'background: rgba(34,197,94,0.15); color: rgba(21,128,61,0.9);' : 'background: rgba(28,88,113,0.08); color: rgba(28,88,113,0.7);' }}">
                                                        {{ $key }}
                                                    </span>
                                                    <span
                                                        class="{{ $question->answer === $key ? 'font-semibold' : '' }}">
                                                        {{ Str::limit($option, 60) }}
                                                    </span>
                                                    @if ($question->answer === $key)
                                                        <i class="fas fa-check-circle ml-auto"
                                                            style="color: rgba(34,197,94,0.7);"></i>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        @if ($question->explanation)
                                            <div class="p-4 rounded-xl"
                                                style="background: linear-gradient(135deg, rgba(59,130,246,0.06) 0%, rgba(37,99,235,0.06) 100%); border-left: 3px solid rgba(59,130,246,0.35);">
                                                <p class="text-sm" style="color: rgba(37,99,235,0.75);">
                                                    <span class="font-semibold flex items-center mb-2">
                                                        <i class="fas fa-lightbulb mr-2"
                                                            style="color: rgba(251,191,36,0.8);"></i>
                                                        Penjelasan:
                                                    </span>
                                                    {{ $question->explanation }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-sm flex items-center space-x-4"
                                        style="color: rgba(28,88,113,0.5);">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar-plus mr-1"
                                                style="color: rgba(28,88,113,0.4);"></i>
                                            Dibuat: {{ $question->created_at->format('d M Y H:i') }}
                                        </span>
                                        @if ($question->updated_at != $question->created_at)
                                            <span class="flex items-center">
                                                <i class="fas fa-edit mr-1" style="color: rgba(28,88,113,0.4);"></i>
                                                Diupdate: {{ $question->updated_at->format('d M Y H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="ml-6 flex flex-col space-y-3">
                                    <a href="{{ route('questions.edit', $question->id) }}"
                                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105"
                                        style="background: linear-gradient(135deg, rgba(251,191,36,0.9) 0%, rgba(245,158,11,0.9) 100%); color: white; box-shadow: 0 3px 8px rgba(245,158,11,0.25);"
                                        onmouseover="this.style.background='linear-gradient(135deg, rgba(251,191,36,1) 0%, rgba(245,158,11,1) 100%)'; this.style.boxShadow='0 4px 12px rgba(245,158,11,0.35)'"
                                        onmouseout="this.style.background='linear-gradient(135deg, rgba(251,191,36,0.9) 0%, rgba(245,158,11,0.9) 100%)'; this.style.boxShadow='0 3px 8px rgba(245,158,11,0.25)'">
                                        <i class="fas fa-edit mr-2"></i>
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('questions.destroy', $question->id) }}"
                                        onsubmit="event.preventDefault(); showDeleteConfirmation(this, 'Yakin ingin menghapus soal ini? Aksi ini tidak dapat dibatalkan.'); return false;"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-full px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105"
                                            style="background: linear-gradient(135deg, rgba(239,68,68,0.9) 0%, rgba(220,38,38,0.9) 100%); color: white; box-shadow: 0 3px 8px rgba(220,38,38,0.25);"
                                            onmouseover="this.style.background='linear-gradient(135deg, rgba(239,68,68,1) 0%, rgba(220,38,38,1) 100%)'; this.style.boxShadow='0 4px 12px rgba(220,38,38,0.35)'"
                                            onmouseout="this.style.background='linear-gradient(135deg, rgba(239,68,68,0.9) 0%, rgba(220,38,38,0.9) 100%)'; this.style.boxShadow='0 3px 8px rgba(220,38,38,0.25)'">
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
                    <div class="p-6 border-t"
                        style="background: linear-gradient(135deg, rgba(248,250,252,0.8) 0%, rgba(241,245,249,0.8) 100%); border-color: rgba(28,88,113,0.06);">
                        {{ $questions->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="rounded-2xl p-10 text-center backdrop-blur-md"
                style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(250,252,255,0.95) 100%); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 8px 32px rgba(0,0,0,0.08);">
                <div class="mb-6" style="color: rgba(28,88,113,0.4);">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full"
                        style="background: rgba(28,88,113,0.05);">
                        <i class="fas fa-question-circle text-4xl"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-3" style="color: rgba(28,88,113,0.8);">Belum ada soal</h3>
                <p class="mb-8 text-lg" style="color: rgba(28,88,113,0.55);">
                    Mulai dengan menambahkan soal baru atau gunakan soal generate AI.
                </p>

                <div class="space-y-4">
                    <a href="{{ route('questions.create') }}"
                        class="inline-flex items-center px-8 py-3 rounded-xl text-white font-semibold text-lg transition-all duration-200 transform hover:scale-105"
                        style="background: linear-gradient(135deg, rgba(28,88,113,0.9) 0%, rgba(34,108,137,0.9) 100%); box-shadow: 0 4px 15px rgba(28,88,113,0.25);"
                        onmouseover="this.style.background='linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(34,108,137,1) 100%)'; this.style.boxShadow='0 6px 20px rgba(28,88,113,0.35)'"
                        onmouseout="this.style.background='linear-gradient(135deg, rgba(28,88,113,0.9) 0%, rgba(34,108,137,0.9) 100%)'; this.style.boxShadow='0 4px 15px rgba(28,88,113,0.25)'">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Soal Manual
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Include Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')

    <!-- Include Help Modal Component -->
    @include('components.help-modal')

    <!-- Include Tutorial Component -->
    @include('components.tutorial')
</body>

</html>
