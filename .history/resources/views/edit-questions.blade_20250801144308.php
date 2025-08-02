<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Soal Pilihan Ganda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-edit text-blue-600 mr-2"></i>
                        Edit Soal Pilihan Ganda
                    </h1>
                    @if ($savedQuestionsCount > 0)
                        <p class="text-gray-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ $savedQuestionsCount }} soal berhasil disimpan dari n8n
                        </p>
                    @endif
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('questions.create') }}"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-1"></i> Tambah Baru
                    </a>
                    <a href="{{ route('questions.manage') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-list mr-1"></i> Lihat Semua
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Edit -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    @if ($autoFillData)
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                            <i class="fas fa-robot mr-2"></i>
                            Form telah diisi otomatis dari data n8n. Silakan edit sesuai kebutuhan.
                        </div>
                    @endif

                    <form method="POST"
                        action="{{ $autoFillData ? route('questions.update', $autoFillData['id']) : route('questions.store') }}"
                        class="space-y-6">
                        @csrf
                        @if ($autoFillData)
                            @method('PUT')
                        @endif

                        <div>
                            <label for="question" class="block text-sm font-medium mb-2" style="color: rgba(28,88,113,0.9);">
                                <i class="fas fa-question-circle mr-1" style="color: rgba(28,88,113,1);"></i>
                                Pertanyaan
                            </label>
                            <textarea name="question" id="question" rows="3"
                                class="w-full px-3 py-2 rounded-md focus:outline-none focus:ring-2 transition-all border-2"
                                style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);"
                                placeholder="Masukkan pertanyaan di sini..." required>{{ old('question', $autoFillData['question'] ?? '') }}</textarea>
                            @error('question')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="option_a" class="block text-sm font-medium mb-2" style="color: rgba(28,88,113,0.9);">
                                    <span class="text-white px-2 py-1 rounded text-xs mr-1" style="background: rgba(28,88,113,1);">A</span>
                                    Pilihan A
                                </label>
                                <textarea name="option_a" id="option_a" rows="2"
                                    class="w-full px-3 py-2 rounded-md focus:outline-none focus:ring-2 transition-all border-2"
                                    style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);"
                                    placeholder="Pilihan A" required>{{ old('option_a', $autoFillData['option_a'] ?? '') }}</textarea>
                                @error('option_a')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="option_b" class="block text-sm font-medium mb-2" style="color: rgba(28,88,113,0.9);">
                                    <span class="text-white px-2 py-1 rounded text-xs mr-1" style="background: rgba(28,88,113,1);">B</span>
                                    Pilihan B
                                </label>
                                <textarea name="option_b" id="option_b" rows="2"
                                    class="w-full px-3 py-2 rounded-md focus:outline-none focus:ring-2 transition-all border-2"
                                    style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);"
                                    placeholder="Pilihan B" required>{{ old('option_b', $autoFillData['option_b'] ?? '') }}</textarea>
                                @error('option_b')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="option_c" class="block text-sm font-medium mb-2" style="color: rgba(28,88,113,0.9);">
                                    <span class="text-white px-2 py-1 rounded text-xs mr-1" style="background: rgba(28,88,113,1);">C</span>
                                    Pilihan C
                                </label>
                                <textarea name="option_c" id="option_c" rows="2"
                                    class="w-full px-3 py-2 rounded-md focus:outline-none focus:ring-2 transition-all border-2"
                                    style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);"
                                    placeholder="Pilihan C" required>{{ old('option_c', $autoFillData['option_c'] ?? '') }}</textarea>
                                @error('option_c')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="option_d" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded text-xs mr-1">D</span>
                                    Pilihan D
                                </label>
                                <textarea name="option_d" id="option_d" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Pilihan D" required>{{ old('option_d', $autoFillData['option_d'] ?? '') }}</textarea>
                                @error('option_d')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="answer" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                Jawaban Benar
                            </label>
                            <select name="answer" id="answer"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                                <option value="">Pilih jawaban yang benar</option>
                                <option value="A"
                                    {{ old('answer', $autoFillData['answer'] ?? '') == 'A' ? 'selected' : '' }}>A
                                </option>
                                <option value="B"
                                    {{ old('answer', $autoFillData['answer'] ?? '') == 'B' ? 'selected' : '' }}>B
                                </option>
                                <option value="C"
                                    {{ old('answer', $autoFillData['answer'] ?? '') == 'C' ? 'selected' : '' }}>C
                                </option>
                                <option value="D"
                                    {{ old('answer', $autoFillData['answer'] ?? '') == 'D' ? 'selected' : '' }}>D
                                </option>
                            </select>
                            @error('answer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="explanation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                                Penjelasan (Opsional)
                            </label>
                            <textarea name="explanation" id="explanation" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Penjelasan mengapa jawaban tersebut benar...">{{ old('explanation', $autoFillData['explanation'] ?? '') }}</textarea>
                            @error('explanation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button type="button" onclick="clearForm()"
                                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                                <i class="fas fa-eraser mr-1"></i>
                                Clear Form
                            </button>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                                <i class="fas fa-save mr-1"></i>
                                {{ $autoFillData ? 'Update Soal' : 'Simpan Soal' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar - Daftar Soal -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-list text-blue-500 mr-2"></i>
                        Soal Tersimpan
                    </h3>

                    @if (count($allSavedQuestions) > 0)
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach ($allSavedQuestions as $index => $question)
                                <div
                                    class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition duration-200">
                                    <h4 class="font-medium text-sm text-gray-800 mb-2">
                                        #{{ $question['id'] }} - {{ Str::limit($question['question'], 60) }}
                                    </h4>
                                    <div class="flex justify-between items-center text-xs text-gray-500">
                                        <span>Jawaban: <strong>{{ $question['answer'] }}</strong></span>
                                        <a href="{{ route('questions.load', $question['id']) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition duration-200">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                    </div>
                                    @if (isset($question['created_at']))
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $question['created_at'] }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-8">
                            <i class="fas fa-inbox text-4xl mb-3"></i>
                            <p>Belum ada soal tersimpan</p>
                            <p class="text-sm">Kirim data dari n8n untuk melihat soal di sini</p>
                        </div>
                    @endif
                </div>

                <!-- API Information -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-md p-6 mt-6 text-white">
                    <h3 class="text-lg font-semibold mb-3">
                        <i class="fas fa-robot mr-2"></i>
                        Endpoint n8n
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="bg-white/20 rounded p-2">
                            <strong>Auto-Save:</strong><br>
                            <code class="text-xs">POST /api/questions/auto-save</code>
                        </div>
                        <div class="bg-white/20 rounded p-2">
                            <strong>Fill Form:</strong><br>
                            <code class="text-xs">POST /api/questions/n8n-fill</code>
                        </div>
                        <div class="bg-white/20 rounded p-2">
                            <strong>Debug:</strong><br>
                            <code class="text-xs">POST /api/questions/debug</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function clearForm() {
            if (confirm('Yakin ingin menghapus semua data di form?')) {
                document.getElementById('question').value = '';
                document.getElementById('option_a').value = '';
                document.getElementById('option_b').value = '';
                document.getElementById('option_c').value = '';
                document.getElementById('option_d').value = '';
                document.getElementById('answer').value = '';
                document.getElementById('explanation').value = '';
            }
        }

        // Auto-resize textareas
        document.addEventListener('DOMContentLoaded', function() {
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });

                // Initial resize
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            });
        });
    </script>
</body>

</html>
