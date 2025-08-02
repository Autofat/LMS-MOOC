<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $material->title }} - Detail Materi - Education Generator Platform</title>
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
        <!-- Header -->
        <div class="flex items-center mb-8 glass-effect rounded-2xl p-6 shadow-xl">
            <a href="{{ route('materials.index') }}" 
               class="inline-flex items-center px-4 py-2 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 mr-6"
               style="background: linear-gradient(135deg, rgba(107,114,128,0.8) 0%, rgba(75,85,99,0.9) 100%); color: white;">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
            <div>
                <h1 class="text-3xl font-bold flex items-center mb-2" style="color: rgba(28,88,113,1);">
                    <i class="fas fa-file-text mr-3 text-emerald-500"></i>
                    {{ $material->title }}
                </h1>
                <p class="text-sm" style="color: rgba(28,88,113,0.7);">Detail materi pembelajaran dan manajemen soal</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Material Info -->
            <div class="lg:col-span-1">
                <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center" style="color: rgba(28,88,113,0.9);">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Info Materi
                    </h2>

                    @if ($material->description)
                        <div class="mb-4 p-4 rounded-xl" style="background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(34,108,137,0.05) 100%);">
                            <span class="text-sm font-semibold flex items-center mb-2" style="color: rgba(28,88,113,0.8);">
                                <i class="fas fa-align-left mr-2 text-blue-500"></i>
                                Deskripsi:
                            </span>
                            <p style="color: rgba(28,88,113,0.7);">{{ $material->description }}</p>
                        </div>
                    @endif

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center p-3 rounded-lg" style="background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);">
                            <span class="flex items-center" style="color: rgba(28,88,113,0.7);">
                                <i class="fas fa-file-pdf mr-2 text-red-500"></i>
                                File:
                            </span>
                            <span class="font-medium" style="color: rgba(28,88,113,0.9);">{{ $material->file_name }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-lg" style="background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);">
                            <span class="flex items-center" style="color: rgba(28,88,113,0.7);">
                                <i class="fas fa-weight mr-2 text-purple-500"></i>
                                Ukuran:
                            </span>
                            <span class="font-medium" style="color: rgba(28,88,113,0.9);">{{ $material->file_size_human }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-lg" style="background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);">
                            <span class="flex items-center" style="color: rgba(28,88,113,0.7);">
                                <i class="fas fa-question-circle mr-2 text-emerald-500"></i>
                                Total Soal:
                            </span>
                            <span class="font-semibold px-3 py-1 rounded-full text-sm" style="background: linear-gradient(135deg, rgba(28,88,113,0.1) 0%, rgba(34,108,137,0.1) 100%); color: rgba(28,88,113,0.9);">{{ $material->questions->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-lg" style="background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);">
                            <span class="flex items-center" style="color: rgba(28,88,113,0.7);">
                                <i class="fas fa-calendar-plus mr-2 text-blue-500"></i>
                                Upload:
                            </span>
                            <span class="font-medium" style="color: rgba(28,88,113,0.9);">{{ $material->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex flex-col gap-3">
                        <a href="{{ route('materials.download', $material->id) }}"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                            style="background: linear-gradient(135deg, rgba(16,185,129,0.8) 0%, rgba(5,150,105,0.9) 100%); color: white;">
                            <i class="fas fa-download mr-2"></i>
                            Download PDF
                        </a>
                        <a href="{{ route('materials.edit', $material->id) }}"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                            style="background: linear-gradient(135deg, rgba(245,158,11,0.8) 0%, rgba(217,119,6,0.9) 100%); color: white;">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Materi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            <div class="lg:col-span-2">
                <div class="glass-effect rounded-2xl shadow-xl p-6">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b" style="border-color: rgba(28,88,113,0.1);">
                        <h2 class="text-xl font-semibold flex items-center" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-list-ol text-emerald-500 mr-2"></i>
                            Daftar Soal ({{ $material->questions->count() }})
                        </h2>
                        <div class="flex flex-row gap-3">
                            <a href="{{ route('questions.create') }}?material_id={{ $material->id }}"
                                class="inline-flex items-center px-4 py-2 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                style="background: linear-gradient(135deg, rgba(28,88,113,0.8) 0%, rgba(34,108,137,0.9) 100%); color: white;">
                                <i class="fas fa-plus mr-2"></i>Tambah Soal
                            </a>
                            <button onclick="generateQuestions({{ $material->id }})" id="generateBtn"
                                class="inline-flex items-center px-4 py-2 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                style="background: linear-gradient(135deg, rgba(168,85,247,0.8) 0%, rgba(147,51,234,0.9) 100%); color: white;">
                                <i class="fas fa-magic mr-2"></i>
                                <span id="generateBtnText">Generate Soal</span>
                            </button>
                        </div>
                    </div>

                    @if ($material->questions->count() > 0)
                        <div class="space-y-4">
                            @foreach ($material->questions as $index => $question)
                                <div class="border rounded-xl p-6 transition-all hover:shadow-md" style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,250,252,0.9) 100%);">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="font-semibold mb-3 flex items-center" style="color: rgba(28,88,113,0.9);">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold mr-3"
                                                      style="background: linear-gradient(135deg, rgba(28,88,113,0.1) 0%, rgba(34,108,137,0.1) 100%); color: rgba(28,88,113,0.9);">
                                                    {{ $index + 1 }}
                                                </span>
                                                {{ $question->question }}
                                            </h3>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                                @foreach ($question->options as $key => $option)
                                                    <div class="flex items-center p-3 rounded-lg text-sm transition-all
                                                        {{ $question->answer === $key ? 'border-2' : 'border' }}"
                                                        style="{{ $question->answer === $key ? 'background: linear-gradient(135deg, rgba(16,185,129,0.1) 0%, rgba(5,150,105,0.1) 100%); border-color: rgba(16,185,129,0.5); color: rgba(5,150,105,0.9);' : 'background: linear-gradient(135deg, rgba(243,244,246,0.8) 0%, rgba(249,250,251,0.8) 100%); border-color: rgba(28,88,113,0.1); color: rgba(28,88,113,0.7);' }}">
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold mr-3"
                                                              style="{{ $question->answer === $key ? 'background: rgba(16,185,129,0.2); color: rgba(5,150,105,1);' : 'background: rgba(28,88,113,0.1); color: rgba(28,88,113,0.7);' }}">
                                                            {{ $key }}
                                                        </span>
                                                        <span class="{{ $question->answer === $key ? 'font-semibold' : '' }}">
                                                            {{ Str::limit($option, 80) }}
                                                        </span>
                                                        @if ($question->answer === $key)
                                                            <i class="fas fa-check-circle ml-auto text-emerald-500"></i>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="flex items-center space-x-4 text-sm">
                                                @if ($question->difficulty)
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
                                                            $bgClass = 'bg-gray-100';
                                                            $textClass = 'text-gray-700';
                                                        }
                                                    @endphp
                                                    <span class="inline-flex items-center {{ $bgClass }} {{ $textClass }} text-xs px-3 py-1 rounded-full font-medium">
                                                        <i class="fas fa-layer-group mr-1"></i>
                                                        {{ ucfirst($question->difficulty) }}
                                                    </span>
                                                @endif
                                                <span class="flex items-center" style="color: rgba(28,88,113,0.6);">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ $question->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="ml-4 flex flex-col space-y-2">
                                            <a href="{{ route('questions.edit', $question->id) }}"
                                                class="inline-flex items-center justify-center w-10 h-10 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                                style="background: linear-gradient(135deg, rgba(245,158,11,0.8) 0%, rgba(217,119,6,0.9) 100%); color: white;">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('questions.destroy', $question->id) }}"
                                                onsubmit="event.preventDefault(); showDeleteConfirmation(this, 'Yakin ingin menghapus soal ini? Aksi ini tidak dapat dibatalkan.'); return false;"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                                    style="background: linear-gradient(135deg, rgba(239,68,68,0.8) 0%, rgba(220,38,38,0.9) 100%); color: white;">
                                                    <i class="fas fa-trash text-sm"></i>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mb-6">
                                <div class="mx-auto h-16 w-16 rounded-full flex items-center justify-center"
                                     style="background: linear-gradient(135deg, rgba(28,88,113,0.1) 0%, rgba(34,108,137,0.1) 100%);">
                                    <i class="fas fa-question-circle text-2xl" style="color: rgba(28,88,113,0.7);"></i>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold mb-3" style="color: rgba(28,88,113,0.9);">Belum Ada Soal</h3>
                            <p class="mb-6 max-w-sm mx-auto" style="color: rgba(28,88,113,0.7);">
                                Mulai dengan menambahkan soal untuk materi ini atau gunakan fitur otomatis untuk generate soal.
                            </p>

                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="{{ route('questions.create') }}?material_id={{ $material->id }}"
                                    class="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                    style="background: linear-gradient(135deg, rgba(28,88,113,0.8) 0%, rgba(34,108,137,0.9) 100%); color: white;">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Soal Manual
                                </a>
                                <button onclick="generateQuestions({{ $material->id }})"
                                    class="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                    style="background: linear-gradient(135deg, rgba(168,85,247,0.8) 0%, rgba(147,51,234,0.9) 100%); color: white;">
                                    <i class="fas fa-magic mr-2"></i>
                                    Generate Otomatis
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Modal -->
    <div id="progressModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900">Sedang Membuat Soal...</h3>
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-2.5">
                        <div id="progressBar" class="bg-fuchsia-600 h-2.5 rounded-full transition-all duration-300"
                            style="width: 0%"></div>
                    </div>
                    <p id="progressText" class="text-sm text-gray-500 mt-2">Menganalisis materi PDF...</p>
                </div>
                <div class="mt-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-fuchsia-500 mx-auto"></div>
                </div>
                <div class="mt-4">
                    <button onclick="cancelGeneration()" id="cancelBtn"
                        class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentMaterialId = null;
        let progressInterval = null;
        let isGenerating = false;
        let pollInterval = null;

        // Optimize polling based on page visibility
        let isPageVisible = true;

        document.addEventListener('visibilitychange', function() {
            isPageVisible = !document.hidden;
            if (isPageVisible && pollInterval) {
                console.log('Page became visible, resuming normal polling');
            } else if (!isPageVisible && pollInterval) {
                console.log('Page became hidden, polling continues but with less logging');
            }
        });

        function generateQuestions(materialId) {
            if (isGenerating) return;

            currentMaterialId = materialId;
            isGenerating = true;

            // Show progress modal immediately
            document.getElementById('progressModal').classList.remove('hidden');

            // Start progress animation
            startProgressAnimation();

            // Trigger n8n workflow with default values
            triggerN8nGeneration(materialId, 10, 'menengah'); // Default: 10 soal, tingkat menengah
        }

        function closeModal() {
            document.getElementById('progressModal').classList.add('hidden');
            isGenerating = false;
            if (progressInterval) {
                clearInterval(progressInterval);
                progressInterval = null;
            }
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
        }

        function cancelGeneration() {
            if (progressInterval) {
                clearInterval(progressInterval);
                progressInterval = null;
            }
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
            isGenerating = false;
            closeModal();
        }

        function startProgressAnimation() {
            let progress = 0;
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            const steps = [
                'Menganalisis materi PDF...',
                'Mengekstrak konten teks...',
                'Memproses dengan sistem...',
                'Membuat soal pilihan ganda...',
                'Menunggu respon dari sistem...',
                'Menyimpan soal ke database...'
            ];

            let stepIndex = 0;

            progressText.textContent = steps[stepIndex];

            progressInterval = setInterval(() => {
                // Slow progress until we get actual response
                progress += Math.random() * 5 + 2; // Slower progress 2-7%

                if (progress > 85) progress = 85; // Don't complete until we get response

                progressBar.style.width = progress + '%';

                if (stepIndex < steps.length - 1 && progress > (stepIndex + 1) * 14) {
                    stepIndex++;
                    progressText.textContent = steps[stepIndex];
                }
            }, 2000); // Slower interval
        }

        function completeProgress(successMessage) {
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            if (progressInterval) {
                clearInterval(progressInterval);
                progressInterval = null;
            }

            // Complete progress
            progressBar.style.width = '100%';
            progressText.textContent = successMessage || 'Selesai! Soal berhasil dibuat!';

            setTimeout(() => {
                progressText.textContent = 'Memuat ulang halaman...';
                setTimeout(() => {
                    isGenerating = false;
                    window.location.reload();
                }, 1000);
            }, 2000);
        }

        function showError(errorMessage) {
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            if (progressInterval) {
                clearInterval(progressInterval);
                progressInterval = null;
            }

            progressBar.style.width = '100%';
            progressBar.classList.remove('bg-fuchsia-600');
            progressBar.classList.add('bg-red-500');
            progressText.textContent = 'Error: ' + errorMessage;

            setTimeout(() => {
                isGenerating = false;
                closeModal();
            }, 5000);
        }

        async function triggerN8nGeneration(materialId, questionCount, difficulty) {
            try {
                console.log('=== Triggering n8n Generation ===');
                console.log('Material ID:', materialId);
                console.log('Question Count:', questionCount);
                console.log('Difficulty:', difficulty);
                console.log('CSRF Token:', '{{ csrf_token() }}');

                const requestData = {
                    question_count: questionCount,
                    difficulty: difficulty,
                    auto_generate: true
                };
                console.log('Request Data:', requestData);

                // First, trigger the n8n workflow using async method
                const response = await fetch(`/materials/${materialId}/generate-questions-async`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });

                console.log('Response status:', response.status);
                console.log('Response headers:', Object.fromEntries(response.headers));

                // Check if response is JSON or HTML
                const contentType = response.headers.get('content-type');
                console.log('Content-Type:', contentType);

                if (contentType && contentType.includes('application/json')) {
                    const result = await response.json();
                    console.log('JSON Response:', result);

                    if (!response.ok) {
                        throw new Error(result.message ||
                            `HTTP ${response.status}: ${result.error || 'Unknown error'}`);
                    }

                    console.log('n8n triggered successfully:', result);

                    // Start polling the auto-save endpoint
                    startPollingAutoSave();
                } else {
                    // Response is likely HTML (error page)
                    const htmlResponse = await response.text();
                    console.error('HTML Response received instead of JSON:');
                    console.error('First 500 chars:', htmlResponse.substring(0, 500));

                    if (response.status === 419) {
                        throw new Error('CSRF token mismatch. Please refresh the page and try again.');
                    } else if (response.status === 500) {
                        throw new Error('Server error occurred. Check the Laravel logs for details.');
                    } else {
                        throw new Error(
                            `Server returned HTML instead of JSON (Status: ${response.status}). Check server logs.`);
                    }
                }

            } catch (error) {
                console.error('Error triggering n8n:', error);
                showError(error.message);
            }
        }

        function startPollingAutoSave() {
            const progressText = document.getElementById('progressText');
            progressText.textContent = 'n8n sedang memproses PDF dan mengirim ke sistem...';

            let initialQuestionCount = {{ $material->questions->count() }};
            let pollAttempts = 0;
            let startTime = Date.now();

            console.log('Starting infinite polling with initial question count:', initialQuestionCount);

            // Wait 5 minutes before starting to poll to give n8n more time to process
            setTimeout(() => {
                progressText.textContent = 'Sistem sedang menganalisis materi dan membuat soal...';

                pollInterval = setInterval(async () => {
                    pollAttempts++;
                    const elapsedMinutes = Math.floor((Date.now() - startTime) / 60000);

                    console.log(`Polling attempt ${pollAttempts} (${elapsedMinutes} minutes elapsed)`);

                    try {
                        // Check if new questions were added to this material
                        const checkResponse = await fetch(
                            `/api/materials/${currentMaterialId}/questions-count`, {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });

                        if (checkResponse.ok) {
                            try {
                                const data = await checkResponse.json();
                                console.log(`API Response:`, data);
                                console.log(
                                    `Current questions: ${data.count}, Initial: ${initialQuestionCount}`
                                );

                                // If questions count increased, assume AI generation completed
                                if (data.count > initialQuestionCount) {
                                    clearInterval(pollInterval);
                                    pollInterval = null;
                                    const newQuestionsCount = data.count - initialQuestionCount;
                                    console.log(`Success! ${newQuestionsCount} new questions created`);
                                    completeProgress(
                                        `Berhasil! ${newQuestionsCount} soal baru telah dibuat.`);
                                    return;
                                }
                            } catch (jsonError) {
                                console.error('JSON parsing error:', jsonError);
                                const responseText = await checkResponse.text();
                                console.error('Raw response:', responseText);
                            }
                        } else {
                            console.warn('Failed to fetch questions count:', checkResponse.status,
                                checkResponse.statusText);
                            try {
                                const responseText = await checkResponse.text();
                                console.warn('Response body:', responseText);
                            } catch (e) {
                                console.warn('Could not read response body');
                            }
                        }

                        // Update progress text with elapsed time and helpful messages (only when page is visible)
                        if (pollAttempts % 20 === 0) { // Update every minute (20 * 3s = 60s)
                            if (elapsedMinutes < 2) {
                                progressText.textContent =
                                    `AI sedang memproses... (${elapsedMinutes} menit)`;
                            } else if (elapsedMinutes < 5) {
                                progressText.textContent =
                                    `AI menganalisis konten PDF... (${elapsedMinutes} menit)`;
                            } else if (elapsedMinutes < 10) {
                                progressText.textContent =
                                    `AI memproses dokumen yang kompleks... (${elapsedMinutes} menit)`;
                            } else if (elapsedMinutes < 15) {
                                progressText.textContent =
                                    `Pemrosesan AI memakan waktu lebih lama... (${elapsedMinutes} menit)`;
                            } else {
                                progressText.textContent =
                                    `⏳ Dokumen besar atau kompleks sedang diproses... (${elapsedMinutes} menit)`;
                            }
                        }

                        // Show encouraging messages and tips at longer intervals (only when page is visible)
                        if (elapsedMinutes >= 5 && pollAttempts % 60 ===
                            0) { // Every 3 minutes after 5 min mark
                            console.log(`Long processing time: ${elapsedMinutes} minutes`);

                            if (elapsedMinutes >= 15) {
                                progressText.textContent =
                                    `⚠️ Proses berjalan lebih lama dari biasanya (${elapsedMinutes} menit). AI mungkin memproses dokumen yang sangat besar atau kompleks.`;
                            } else if (elapsedMinutes >= 10) {
                                progressText.textContent =
                                    `💭 AI sedang menganalisis konten secara mendalam... (${elapsedMinutes} menit)`;
                            } else {
                                progressText.textContent =
                                    `🔄 Tetap menunggu, AI sedang bekerja... (${elapsedMinutes} menit)`;
                            }
                        }

                    } catch (error) {
                        console.warn('Polling check error (continuing):', error.message);
                        // Continue polling, don't stop on network errors

                        // Show connection error message but keep trying
                        if (pollAttempts % 10 === 0) {
                            progressText.textContent =
                                `Mencoba koneksi ulang... (${elapsedMinutes} menit)`;
                        }
                    }

                    // No timeout limit - polling continues until success or manual cancel
                    // This ensures the loading bar keeps running until n8n actually responds

                }, 3000); // Poll every 3 seconds

            }, 300000); // Wait 5 minutes (300 seconds) before starting to poll
        }

        // Test function to manually check questions count
        async function testQuestionsCount() {
            const materialId = {{ $material->id }};
            console.log('Testing questions count for material:', materialId);

            try {
                // Test both the questions count API and the n8n debug endpoint
                console.log('=== Testing Questions Count API ===');
                const response = await fetch(`/api/materials/${materialId}/questions-count`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);

                if (response.ok) {
                    const data = await response.json();
                    console.log('Questions count response:', data);

                    // Also test the n8n connection
                    console.log('=== Testing n8n Connection ===');
                    const n8nResponse = await fetch('/test-n8n-connection', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (n8nResponse.ok) {
                        const n8nData = await n8nResponse.json();
                        console.log('n8n connection test:', n8nData);

                        alert(
                            `✅ Tests Complete!\n\nQuestions Count: ${data.count}\nn8n Status: ${n8nData.response.status}\nn8n Response: ${n8nData.response.successful ? 'Success' : 'Failed'}`
                        );
                    } else {
                        alert(`✅ Questions API works! Count: ${data.count}\n❌ n8n test failed: ${n8nResponse.status}`);
                    }
                } else {
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    alert(`❌ Questions API Error: ${response.status}\n${errorText}`);
                }
            } catch (error) {
                console.error('Network error:', error);
                alert(`❌ Network error: ${error.message}`);
            }
        }

        // Close modal when clicking outside (only if not generating)
        window.onclick = function(event) {
            const progressModal = document.getElementById('progressModal');

            if (event.target === progressModal && !isGenerating) {
                closeModal();
            }
        }
    </script>

    <!-- Include Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')
</body>

</html>
