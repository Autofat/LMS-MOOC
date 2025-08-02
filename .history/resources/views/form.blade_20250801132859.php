<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Buat Soal - Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .professional-gradient {
            background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 50%, rgba(42,122,157,1) 100%);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="{{ route('questions.manage') }}"
                class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Kelola Soal</span>
            </a>
        </div>
    </div>
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-8 border border-blue-100">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="professional-gradient p-6 rounded-2xl mb-6">
                    <h1 class="text-3xl font-bold text-white mb-2 flex items-center justify-center space-x-3">
                        <i class="fas fa-robot text-4xl"></i>
                        <span>Form Buat Soal</span>
                    </h1>
                    <p class="text-blue-100">Buat soal pilihan ganda dengan bantuan teknologi otomatis</p>
                </div>
            </div>

            <!-- Auto-Fill Message -->
            @if (isset($autoFillData))
                <div class="bg-gradient-to-r from-blue-100 to-indigo-100 border border-blue-300 text-blue-800 px-6 py-4 rounded-xl mb-6 flex items-center space-x-3">
                    <i class="fas fa-magic text-blue-600 text-xl"></i>
                    <div>
                        <strong>Form telah diisi otomatis oleh sistem!</strong> 
                        <p class="text-sm mt-1">Data dari sistem telah dimuat. Anda dapat mengedit dan submit secara manual.</p>
                    </div>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-gradient-to-r from-red-100 to-red-50 border border-red-300 text-red-700 px-6 py-4 rounded-xl mb-6">
                    <div class="flex items-center space-x-3 mb-2">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                        <strong>Terdapat kesalahan:</strong>
                    </div>
                    <ul class="list-disc list-inside ml-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('questions.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Material Selection -->
                <div>
                    <label for="material_id" class="block text-sm font-semibold text-blue-900 mb-3 flex items-center space-x-2">
                        <i class="fas fa-folder-open text-blue-600"></i>
                        <span>Pilih Materi (Opsional)</span>
                    </label>
                    <select id="material_id" name="material_id"
                        class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gradient-to-r from-blue-50 to-indigo-50">
                        <option value="">Tanpa materi spesifik</option>
                        @foreach (\App\Models\Material::where('is_active', true)->orderBy('title')->get() as $material)
                            <option value="{{ $material->id }}"
                                {{ old('material_id', $autoFillData['material_id'] ?? '') == $material->id ? 'selected' : '' }}>
                                {{ $material->title }}
                                @if ($material->category)
                                    ({{ $material->category }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-blue-600 mt-2 flex items-center space-x-1">
                        <i class="fas fa-info-circle"></i>
                        <span>Pilih materi untuk mengelompokkan soal berdasarkan materi yang sudah diupload.</span>
                    </p>
                </div>

                <!-- Question -->
                <div>
                    <label for="question" class="block text-sm font-semibold text-blue-900 mb-3 flex items-center space-x-2">
                        <i class="fas fa-question-circle text-blue-600"></i>
                        <span>Pertanyaan <span class="text-red-500">*</span></span>
                    </label>
                    <textarea id="question" name="question" rows="4"
                        class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gradient-to-r from-blue-50 to-indigo-50"
                        placeholder="Masukkan pertanyaan soal..." required>{{ old('question', $autoFillData['question'] ?? '') }}</textarea>
                </div>

                <!-- Options -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Option A -->
                    <div>
                        <label for="option_a" class="block text-sm font-semibold text-blue-900 mb-3 flex items-center space-x-2">
                            <span class="bg-blue-600 text-white px-2 py-1 rounded-lg text-xs font-bold">A</span>
                            <span>Pilihan A <span class="text-red-500">*</span></span>
                        </label>
                        <textarea id="option_a" name="option_a" rows="3"
                            class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gradient-to-r from-blue-50 to-indigo-50"
                            placeholder="Masukkan pilihan A..." required>{{ old('option_a', $autoFillData['option_a'] ?? '') }}</textarea>
                    </div>

                    <!-- Option B -->
                    <div>
                        <label for="option_b" class="block text-sm font-semibold text-blue-900 mb-3 flex items-center space-x-2">
                            <span class="bg-blue-600 text-white px-2 py-1 rounded-lg text-xs font-bold">B</span>
                            <span>Pilihan B <span class="text-red-500">*</span></span>
                        </label>
                        <textarea id="option_b" name="option_b" rows="3"
                            class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gradient-to-r from-blue-50 to-indigo-50"
                            placeholder="Masukkan pilihan B..." required>{{ old('option_b', $autoFillData['option_b'] ?? '') }}</textarea>
                    </div>

                    <!-- Option C -->
                    <div>
                        <label for="option_c" class="block text-sm font-semibold text-blue-900 mb-3 flex items-center space-x-2">
                            <span class="bg-blue-600 text-white px-2 py-1 rounded-lg text-xs font-bold">C</span>
                            <span>Pilihan C <span class="text-red-500">*</span></span>
                        </label>
                        <textarea id="option_c" name="option_c" rows="3"
                            class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gradient-to-r from-blue-50 to-indigo-50"
                            placeholder="Masukkan pilihan C..." required>{{ old('option_c', $autoFillData['option_c'] ?? '') }}</textarea>
                    </div>

                    <!-- Option D -->
                    <div>
                        <label for="option_d" class="block text-sm font-semibold text-blue-900 mb-3 flex items-center space-x-2">
                            <span class="bg-blue-600 text-white px-2 py-1 rounded-lg text-xs font-bold">D</span>
                            <span>Pilihan D <span class="text-red-500">*</span></span>
                        </label>
                        <textarea id="option_d" name="option_d" rows="3"
                            class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gradient-to-r from-blue-50 to-indigo-50"
                            placeholder="Masukkan pilihan D..." required>{{ old('option_d', $autoFillData['option_d'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Answer -->
                <div>
                    <label for="answer" class="block text-sm font-semibold text-blue-900 mb-3 flex items-center space-x-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span>Jawaban Benar <span class="text-red-500">*</span></span>
                    </label>
                    <select id="answer" name="answer"
                        class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gradient-to-r from-green-50 to-emerald-50"
                        required>
                        <option value="">Pilih jawaban benar...</option>
                        <option value="A"
                            {{ old('answer', $autoFillData['answer'] ?? '') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B"
                            {{ old('answer', $autoFillData['answer'] ?? '') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C"
                            {{ old('answer', $autoFillData['answer'] ?? '') == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D"
                            {{ old('answer', $autoFillData['answer'] ?? '') == 'D' ? 'selected' : '' }}>D</option>
                    </select>
                </div>

                <!-- Explanation -->
                <div>
                    <label for="explanation" class="block text-sm font-semibold text-blue-900 mb-3 flex items-center space-x-2">
                        <i class="fas fa-lightbulb text-yellow-500"></i>
                        <span>Penjelasan (Opsional)</span>
                    </label>
                    <textarea id="explanation" name="explanation" rows="4"
                        class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gradient-to-r from-yellow-50 to-amber-50"
                        placeholder="Masukkan penjelasan jawaban...">{{ old('explanation', $autoFillData['explanation'] ?? '') }}</textarea>
                </div>

                <!-- Difficulty -->
                <div>
                    <label for="difficulty" class="block text-sm font-semibold text-blue-900 mb-3 flex items-center space-x-2">
                        <i class="fas fa-chart-line text-orange-500"></i>
                        <span>Tingkat Kesulitan (Opsional)</span>
                    </label>
                    <select id="difficulty" name="difficulty"
                        class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gradient-to-r from-orange-50 to-red-50">
                        <option value="">Pilih tingkat kesulitan...</option>
                        <option value="Mudah"
                            {{ old('difficulty', $autoFillData['difficulty'] ?? '') == 'Mudah' ? 'selected' : '' }}>
                            Mudah</option>
                        <option value="Menengah"
                            {{ old('difficulty', $autoFillData['difficulty'] ?? '') == 'Menengah' ? 'selected' : '' }}>
                            Menengah</option>
                        <option value="Sulit"
                            {{ old('difficulty', $autoFillData['difficulty'] ?? '') == 'Sulit' ? 'selected' : '' }}>
                            Sulit</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 pt-6">
                    <button type="button" onclick="resetForm()"
                        class="px-8 py-3 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-300 font-semibold flex items-center space-x-2">
                        <i class="fas fa-undo"></i>
                        <span>Reset</span>
                    </button>
                    <button type="submit"
                        class="px-8 py-3 professional-gradient text-white rounded-xl hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-semibold flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Soal</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Function to reset form
        function resetForm() {
            document.querySelector('form').reset();
            updatePreview();
        }

        // Function to update JSON preview
        function updatePreview() {
            const question = document.getElementById('question').value || 'Contoh pertanyaan...';
            const optionA = document.getElementById('option_a').value || 'Pilihan A';
            const optionB = document.getElementById('option_b').value || 'Pilihan B';
            const optionC = document.getElementById('option_c').value || 'Pilihan C';
            const optionD = document.getElementById('option_d').value || 'Pilihan D';
            const answer = document.getElementById('answer').value || 'C';
            const explanation = document.getElementById('explanation').value || 'Penjelasan jawaban...';
            const difficulty = document.getElementById('difficulty').value || 'Menengah';

            const jsonData = {
                soal: [{
                    question: question,
                    options: {
                        A: optionA,
                        B: optionB,
                        C: optionC,
                        D: optionD
                    },
                    answer: answer,
                    explanation: explanation,
                    difficulty: difficulty
                }]
            };

            document.getElementById('jsonPreview').textContent = JSON.stringify(jsonData, null, 2);
        }

        // Add event listeners to update preview
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = ['question', 'option_a', 'option_b', 'option_c', 'option_d', 'answer', 'explanation',
                'difficulty'
            ];
            inputs.forEach(id => {
                const element = document.getElementById(id);
                element.addEventListener('input', updatePreview);
                element.addEventListener('change', updatePreview);
            });

            updatePreview(); // Initial preview
        });
    </script>
</body>

</html>
