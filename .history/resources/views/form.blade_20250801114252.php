<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Buat Soal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="{{ route('questions.manage') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Kembali ke Kelola Soal
            </a>
        </div>
    </div>
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Form Buat Soal</h1>
                <p class="text-gray-600">Buat soal pilihan ganda dengan mudah</p>
            </div>

            <!-- Auto-Fill Message -->
            @if (isset($autoFillData))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                    <strong>Form telah diisi otomatis!</strong> Data dari bot n8n telah dimuat. Anda dapat mengedit dan
                    submit secara manual.
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
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
                    <label for="material_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Materi (Opsional)
                    </label>
                    <select id="material_id" name="material_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                    <p class="text-sm text-gray-500 mt-1">Pilih materi untuk mengelompokkan soal berdasarkan materi yang
                        sudah diupload.</p>
                </div>

                <!-- Question -->
                <div>
                    <label for="question" class="block text-sm font-medium text-gray-700 mb-2">
                        Pertanyaan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="question" name="question" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan pertanyaan soal..." required>{{ old('question', $autoFillData['question'] ?? '') }}</textarea>
                </div>

                <!-- Options -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Option A -->
                    <div>
                        <label for="option_a" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilihan A <span class="text-red-500">*</span>
                        </label>
                        <textarea id="option_a" name="option_a" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan pilihan A..." required>{{ old('option_a', $autoFillData['option_a'] ?? '') }}</textarea>
                    </div>

                    <!-- Option B -->
                    <div>
                        <label for="option_b" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilihan B <span class="text-red-500">*</span>
                        </label>
                        <textarea id="option_b" name="option_b" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan pilihan B..." required>{{ old('option_b', $autoFillData['option_b'] ?? '') }}</textarea>
                    </div>

                    <!-- Option C -->
                    <div>
                        <label for="option_c" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilihan C <span class="text-red-500">*</span>
                        </label>
                        <textarea id="option_c" name="option_c" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan pilihan C..." required>{{ old('option_c', $autoFillData['option_c'] ?? '') }}</textarea>
                    </div>

                    <!-- Option D -->
                    <div>
                        <label for="option_d" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilihan D <span class="text-red-500">*</span>
                        </label>
                        <textarea id="option_d" name="option_d" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan pilihan D..." required>{{ old('option_d', $autoFillData['option_d'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Answer -->
                <div>
                    <label for="answer" class="block text-sm font-medium text-gray-700 mb-2">
                        Jawaban Benar <span class="text-red-500">*</span>
                    </label>
                    <select id="answer" name="answer"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                    <label for="explanation" class="block text-sm font-medium text-gray-700 mb-2">
                        Penjelasan (Opsional)
                    </label>
                    <textarea id="explanation" name="explanation" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan penjelasan jawaban...">{{ old('explanation', $autoFillData['explanation'] ?? '') }}</textarea>
                </div>

                <!-- Difficulty -->
                <div>
                    <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                        Tingkat Kesulitan (Opsional)
                    </label>
                    <select id="difficulty" name="difficulty"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="resetForm()"
                        class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                        Reset
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        Simpan Soal
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
