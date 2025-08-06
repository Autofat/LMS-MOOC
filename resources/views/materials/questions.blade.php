<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soal - {{ $material->title }} - PPSDM MOOC Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center mb-6">
            <a href="{{ route('materials.show', $material->id) }}" class="text-blue-500 hover:text-blue-700 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Soal untuk: {{ $material->title }}</h1>
                @if ($material->category)
                    <span class="inline-block bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded-full mt-1">
                        {{ $material->category }}
                    </span>
                @endif
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <div class="text-gray-600">
                Total: {{ $questions->total() }} soal
            </div>
            <a href="{{ route('questions.create') }}?material_id={{ $material->id }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                + Tambah Soal Baru
            </a>
        </div>

        @if ($questions->count() > 0)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @foreach ($questions as $index => $question)
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="mb-4">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                            {{ $index + 1 + ($questions->currentPage() - 1) * $questions->perPage() }}.
                                            {{ $question->question }}
                                        </h3>

                                        <div class="grid grid-cols-2 gap-2 my-3">
                                            @php
                                                $options = [
                                                    'A' => $question->option_a,
                                                    'B' => $question->option_b,
                                                    'C' => $question->option_c,
                                                    'D' => $question->option_d,
                                                    'E' => $question->option_e
                                                ];
                                                // Filter out empty options
                                                $options = array_filter($options, function($value) {
                                                    return !empty($value);
                                                });
                                            @endphp
                                            @foreach ($options as $key => $option)
                                                <div
                                                    class="flex items-center p-2 rounded
                                                    {{ $question->answer === $key ? 'bg-green-100 border border-green-300' : 'bg-gray-50' }}">
                                                    <span class="font-bold mr-2">{{ $key }}.</span>
                                                    <span
                                                        class="{{ $question->answer === $key ? 'font-semibold text-green-800' : '' }}">
                                                        {{ $option }}
                                                    </span>
                                                    @if ($question->answer === $key)
                                                        <span class="ml-auto text-green-600">✓</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        @if ($question->explanation)
                                            <div class="bg-blue-50 p-3 rounded border-l-4 border-blue-400">
                                                <p class="text-sm text-blue-800">
                                                    <span class="font-semibold">Penjelasan:</span>
                                                    {{ $question->explanation }}
                                                </p>
                                            </div>
                                        @endif

                                        @if ($question->difficulty)
                                            <div class="bg-yellow-50 p-3 rounded border-l-4 border-yellow-400 mt-3">
                                                <p class="text-sm text-yellow-800">
                                                    <span class="font-semibold">Tingkat Kesulitan:</span>
                                                    @php
                                                        $difficulty = strtolower($question->difficulty);

                                                        // Handle both Indonesian and English difficulty levels
                                                        if (in_array($difficulty, ['mudah', 'easy'])) {
                                                            $bgStyle =
                                                                'background: rgba(34,197,94,0.12); color: rgba(21,128,61,0.9);';
                                                            $icon = 'fas fa-circle';
                                                            $displayText = $difficulty === 'easy' ? 'Easy' : 'Mudah';
                                                        } elseif (in_array($difficulty, ['menengah', 'medium'])) {
                                                            $bgStyle =
                                                                'background: rgba(251,191,36,0.12); color: rgba(180,83,9,0.9);';
                                                            $icon = 'fas fa-adjust';
                                                            $displayText =
                                                                $difficulty === 'medium' ? 'Medium' : 'Menengah';
                                                        } elseif (in_array($difficulty, ['sulit', 'hard'])) {
                                                            $bgStyle =
                                                                'background: rgba(248,113,113,0.12); color: rgba(185,28,28,0.9);';
                                                            $icon = 'fas fa-fire';
                                                            $displayText = $difficulty === 'hard' ? 'Hard' : 'Sulit';
                                                        } else {
                                                            $bgStyle =
                                                                'background: rgba(156,163,175,0.12); color: rgba(75,85,99,0.9);';
                                                            $icon = 'fas fa-question';
                                                            $displayText = ucfirst($question->difficulty);
                                                        }
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center text-xs px-3 py-1 rounded-full font-medium ml-1"
                                                        style="{{ $bgStyle }}">
                                                        <i class="{{ $icon }} mr-1"></i>
                                                        {{ $displayText }}
                                                    </span>
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
                                        onsubmit="event.preventDefault(); showDeleteConfirmation(this, 'Yakin ingin menghapus soal ini? Aksi ini tidak dapat dibatalkan.'); return false;"
                                        class="inline">
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
                <h3 class="text-lg font-medium text-gray-800 mb-2">Belum ada soal untuk materi ini</h3>
                <p class="text-gray-600 mb-4">Mulai dengan menambahkan soal pertama untuk materi
                    "{{ $material->title }}".</p>

                <a href="{{ route('questions.create') }}?material_id={{ $material->id }}"
                    class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Tambah Soal Pertama
                </a>
            </div>
        @endif
    </div>

    <!-- Include Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')
</body>

</html>
