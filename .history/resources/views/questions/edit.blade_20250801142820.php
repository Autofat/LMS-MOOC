<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Soal - KemenLH/BPLH E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .professional-gradient {
            background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 50%, rgba(42,122,157,1) 100%);
        }
    </style>
</head>

<body class="min-h-screen" style="background: linear-gradient(135deg, #f8fafc 0%, rgba(28,88,113,0.05) 50%, rgba(42,122,157,0.08) 100%);">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="flex items-center mb-8">
            <a href="{{ route('questions.manage') }}" 
               class="mr-4 p-3 rounded-full transition-all duration-300 hover:scale-105"
               style="color: rgba(28,88,113,1); background-color: rgba(28,88,113,0.1); border: 1px solid rgba(28,88,113,0.2);">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold" style="color: rgba(28,88,113,1);">Edit Soal</h1>
        </div>

        <div class="bg-white/95 backdrop-blur-sm shadow-xl rounded-2xl p-8 border" style="border-color: rgba(28,88,113,0.1);">
            <form method="POST" action="{{ route('questions.update', $question->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Material Selection -->
                <div>
                    <label for="material_id" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                        <i class="fas fa-folder-open" style="color: rgba(28,88,113,1);"></i>
                        <span>Pilih Materi (Opsional)</span>
                    </label>
                    <select id="material_id" name="material_id"
                        class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2" 
                        style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.03) 0%, rgba(42,122,157,0.05) 100%);">
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

                <!-- Question Field -->
                <div>
                    <label for="question" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                        <i class="fas fa-question-circle" style="color: rgba(28,88,113,1);"></i>
                        <span>Pertanyaan <span class="text-red-500">*</span></span>
                    </label>
                    <textarea id="question" name="question" rows="4" required
                        class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                        style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.03) 0%, rgba(42,122,157,0.05) 100%);"
                        placeholder="Masukkan pertanyaan soal...">{{ old('question', $question->question) }}</textarea>
                    @error('question')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Options Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="option_a" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <span class="text-white px-2 py-1 rounded-lg text-xs font-bold" style="background-color: rgba(28,88,113,1);">A</span>
                            <span>Pilihan A <span class="text-red-500">*</span></span>
                        </label>
                        <textarea id="option_a" name="option_a" rows="3" required
                            class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                            style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.03) 0%, rgba(42,122,157,0.05) 100%);"
                            placeholder="Masukkan pilihan A...">{{ old('option_a', $question->options['A']) }}</textarea>
                        @error('option_a')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="option_b" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <span class="text-white px-2 py-1 rounded-lg text-xs font-bold" style="background-color: rgba(28,88,113,1);">B</span>
                            <span>Pilihan B <span class="text-red-500">*</span></span>
                        </label>
                        <textarea id="option_b" name="option_b" rows="3" required
                            class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                            style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.03) 0%, rgba(42,122,157,0.05) 100%);"
                            placeholder="Masukkan pilihan B...">{{ old('option_b', $question->options['B']) }}</textarea>
                        @error('option_b')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="option_c" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <span class="text-white px-2 py-1 rounded-lg text-xs font-bold" style="background-color: rgba(28,88,113,1);">C</span>
                            <span>Pilihan C <span class="text-red-500">*</span></span>
                        </label>
                        <textarea id="option_c" name="option_c" rows="3" required
                            class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                            style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.03) 0%, rgba(42,122,157,0.05) 100%);"
                            placeholder="Masukkan pilihan C...">{{ old('option_c', $question->options['C']) }}</textarea>
                        @error('option_c')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="option_d" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <span class="text-white px-2 py-1 rounded-lg text-xs font-bold" style="background-color: rgba(28,88,113,1);">D</span>
                            <span>Pilihan D <span class="text-red-500">*</span></span>
                        </label>
                        <textarea id="option_d" name="option_d" rows="3" required
                            class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                            style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.03) 0%, rgba(42,122,157,0.05) 100%);"
                            placeholder="Masukkan pilihan D...">{{ old('option_d', $question->options['D']) }}</textarea>
                        @error('option_d')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Answer Field -->
                <div>
                    <label for="answer" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span>Jawaban Benar <span class="text-red-500">*</span></span>
                    </label>
                    <select id="answer" name="answer" required
                        class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                        style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(34,197,94,0.05) 0%, rgba(16,185,129,0.05) 100%);">
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
                    <label for="explanation" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                        <i class="fas fa-lightbulb text-yellow-500"></i>
                        <span>Penjelasan (Opsional)</span>
                    </label>
                    <textarea id="explanation" name="explanation" rows="4"
                        class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                        style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);"
                        placeholder="Masukkan penjelasan jawaban...">{{ old('explanation', $question->explanation) }}</textarea>
                    @error('explanation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Difficulty Field -->
                                <!-- Difficulty Field -->
                <div>
                    <label for="difficulty" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                        <i class="fas fa-layer-group text-purple-500"></i>
                        <span>Tingkat Kesulitan</span>
                    </label>
                    <select id="difficulty" name="difficulty" 
                        class="w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all border-2"
                        style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);">
                        <option value="">Pilih tingkat kesulitan</option>
                        <option value="Mudah" {{ old('difficulty', $question->difficulty) == 'Mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="Menengah" {{ old('difficulty', $question->difficulty) == 'Menengah' ? 'selected' : '' }}>Menengah</option>
                        <option value="Sulit" {{ old('difficulty', $question->difficulty) == 'Sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                    @error('difficulty')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-between items-center pt-8 border-t" style="border-color: rgba(28,88,113,0.1);">
                    <div class="text-sm" style="color: rgba(28,88,113,0.6);">
                        <p><i class="fas fa-id-card mr-1"></i>ID: {{ $question->id }}</p>
                        <p><i class="fas fa-calendar-plus mr-1"></i>Dibuat: {{ $question->created_at->format('d M Y H:i') }}</p>
                        @if ($question->updated_at != $question->created_at)
                            <p><i class="fas fa-edit mr-1"></i>Terakhir diupdate: {{ $question->updated_at->format('d M Y H:i') }}</p>
                        @endif
                        @if ($question->difficulty)
                            @php
                                $difficulty = $question->difficulty;
                                
                                if ($difficulty === 'Mudah') {
                                    $bgStyle = 'background: rgba(34,197,94,0.12); color: rgba(21,128,61,0.9);';
                                    $icon = 'fas fa-circle';
                                } elseif ($difficulty === 'Menengah') {
                                    $bgStyle = 'background: rgba(251,191,36,0.12); color: rgba(180,83,9,0.9);';
                                    $icon = 'fas fa-adjust';
                                } elseif ($difficulty === 'Sulit') {
                                    $bgStyle = 'background: rgba(248,113,113,0.12); color: rgba(185,28,28,0.9);';
                                    $icon = 'fas fa-fire';
                                } else {
                                    $bgStyle = 'background: rgba(156,163,175,0.12); color: rgba(75,85,99,0.9);';
                                    $icon = 'fas fa-question';
                                }
                            @endphp
                            <p><i class="fas fa-layer-group mr-1"></i>Kesulitan: <span
                                    class="inline-flex items-center text-xs px-3 py-1 rounded-full font-medium"
                                    style="{{ $bgStyle }}">
                                    <i class="{{ $icon }} mr-1"></i>
                                    {{ $question->difficulty }}
                                </span>
                            </p>
                        @endif
                    </div>

                    <div class="space-x-4">
                        <a href="{{ $question->material_id ? route('materials.show', $question->material_id) : route('materials.index') }}"
                            class="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                            style="background: linear-gradient(135deg, rgba(107,114,128,0.8) 0%, rgba(75,85,99,0.9) 100%); color: white;">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                            style="background: linear-gradient(135deg, rgba(28,88,113,0.8) 0%, rgba(34,108,137,0.9) 100%); color: white;">
                            <i class="fas fa-save mr-2"></i>
                            Perbarui Soal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
