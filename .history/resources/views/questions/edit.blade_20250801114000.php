<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Soal - KemenLH/BPLH E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Edit Soal</h1>
            <a href="{{ route('questions.manage') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Kembali ke Kelola Soal
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-8">
            <form method="POST" action="{{ route('questions.update', $question->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Question Field -->
                <div>
                    <label for="question" class="block text-sm font-medium text-gray-700 mb-2">
                        Pertanyaan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="question" name="question" rows="3" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan pertanyaan soal...">{{ old('question', $question->question) }}</textarea>
                    @error('question')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Material Selection -->
                <div>
                    <label for="material_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Materi (Opsional)
                    </label>
                    <select id="material_id" name="material_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tanpa materi spesifik</option>
                        @foreach ($materials as $material)
                            <option value="{{ $material->id }}"
                                {{ old('material_id', $question->material_id) == $material->id ? 'selected' : '' }}>
                                {{ $material->title }}
                                @if ($material->category)
                                    ({{ $material->category }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Pilih materi untuk mengelompokkan soal berdasarkan materi yang
                        sudah diupload.</p>
                    @error('material_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Options Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="option_a" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilihan A <span class="text-red-500">*</span>
                        </label>
                        <textarea id="option_a" name="option_a" rows="2" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan pilihan A...">{{ old('option_a', $question->options['A']) }}</textarea>
                        @error('option_a')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="option_b" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilihan B <span class="text-red-500">*</span>
                        </label>
                        <textarea id="option_b" name="option_b" rows="2" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan pilihan B...">{{ old('option_b', $question->options['B']) }}</textarea>
                        @error('option_b')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="option_c" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilihan C <span class="text-red-500">*</span>
                        </label>
                        <textarea id="option_c" name="option_c" rows="2" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan pilihan C...">{{ old('option_c', $question->options['C']) }}</textarea>
                        @error('option_c')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="option_d" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilihan D <span class="text-red-500">*</span>
                        </label>
                        <textarea id="option_d" name="option_d" rows="2" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan pilihan D...">{{ old('option_d', $question->options['D']) }}</textarea>
                        @error('option_d')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Answer Field -->
                <div>
                    <label for="answer" class="block text-sm font-medium text-gray-700 mb-2">
                        Jawaban Benar <span class="text-red-500">*</span>
                    </label>
                    <select id="answer" name="answer" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih jawaban benar...</option>
                        <option value="A" {{ old('answer', $question->answer) == 'A' ? 'selected' : '' }}>A
                        </option>
                        <option value="B" {{ old('answer', $question->answer) == 'B' ? 'selected' : '' }}>B
                        </option>
                        <option value="C" {{ old('answer', $question->answer) == 'C' ? 'selected' : '' }}>C
                        </option>
                        <option value="D" {{ old('answer', $question->answer) == 'D' ? 'selected' : '' }}>D
                        </option>
                    </select>
                    @error('answer')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Explanation Field -->
                <div>
                    <label for="explanation" class="block text-sm font-medium text-gray-700 mb-2">
                        Penjelasan (Opsional)
                    </label>
                    <textarea id="explanation" name="explanation" rows="3"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan penjelasan jawaban...">{{ old('explanation', $question->explanation) }}</textarea>
                    @error('explanation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Difficulty Field -->
                <div>
                    <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                        Tingkat Kesulitan (Opsional)
                    </label>
                    <select id="difficulty" name="difficulty"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih tingkat kesulitan...</option>
                        <option value="Mudah"
                            {{ old('difficulty', $question->difficulty) == 'Mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="Menengah"
                            {{ old('difficulty', $question->difficulty) == 'Menengah' ? 'selected' : '' }}>Menengah
                        </option>
                        <option value="Sulit"
                            {{ old('difficulty', $question->difficulty) == 'Sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                    @error('difficulty')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-between items-center pt-6 border-t">
                    <div class="text-sm text-gray-600">
                        <p>ID: {{ $question->id }}</p>
                        <p>Dibuat: {{ $question->created_at->format('d M Y H:i') }}</p>
                        @if ($question->updated_at != $question->created_at)
                            <p>Terakhir diupdate: {{ $question->updated_at->format('d M Y H:i') }}</p>
                        @endif
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
                            <p>Current: <span
                                    class="inline-block {{ $bgClass }} {{ $textClass }} text-xs px-2 py-1 rounded-full">{{ $question->difficulty }}</span>
                            </p>
                        @endif
                    </div>

                    <div class="space-x-4">
                        <a href="{{ route('questions.manage') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            Update Soal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
