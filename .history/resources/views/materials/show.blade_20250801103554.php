<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $material->title }} - Detail Materi - KemenLH/BPLH E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('materials.index') }}" class="text-blue-500 hover:text-blue-700 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">{{ $material->title }}</h1>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Material Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Info Materi</h2>

                    @if ($material->description)
                        <div class="mb-4">
                            <span class="text-sm font-medium text-gray-600">Deskripsi:</span>
                            <p class="text-gray-700 mt-1">{{ $material->description }}</p>
                        </div>
                    @endif

                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>File:</span>
                            <span>{{ $material->file_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ukuran:</span>
                            <span>{{ $material->file_size_human }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Soal:</span>
                            <span class="font-semibold text-blue-600">{{ $material->questions->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Upload:</span>
                            <span>{{ $material->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex flex-row gap-2">
                        <a href="{{ route('materials.download', $material->id) }}"
                            class="w-full bg-green-500 hover:bg-green-600 text-white text-center py-2 px-4 rounded flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Download PDF
                        </a>
                        <a href="{{ route('materials.edit', $material->id) }}"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 px-4 rounded">
                            Edit Materi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Daftar Soal ({{ $material->questions->count() }})
                        </h2>
                        <div class="flex flex-row gap-2">
                            <a href="{{ route('questions.create') }}?material_id={{ $material->id }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                + Tambah Soal
                            </a>
                            <button onclick="generateQuestions({{ $material->id }})" id="generateBtn"
                                class="bg-fuchsia-500 hover:bg-fuchsia-600 text-white px-4 py-2 rounded text-sm flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span id="generateBtnText">Generate Soal AI</span>
                            </button>
                        </div>
                    </div>

                    @if ($material->questions->count() > 0)
                        <div class="space-y-4">
                            @foreach ($material->questions as $index => $question)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-800 mb-2">
                                                {{ $index + 1 }}. {{ $question->question }}
                                            </h3>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3">
                                                @foreach ($question->options as $key => $option)
                                                    <div
                                                        class="flex items-center p-2 rounded text-sm
                                                        {{ $question->answer === $key ? 'bg-green-100 border border-green-300' : 'bg-gray-50' }}">
                                                        <span class="font-bold mr-2">{{ $key }}.</span>
                                                        <span
                                                            class="{{ $question->answer === $key ? 'font-semibold text-green-800' : '' }}">
                                                            {{ Str::limit($option, 80) }}
                                                        </span>
                                                        @if ($question->answer === $key)
                                                            <span class="ml-auto text-green-600">✓</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
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
                                                    <span
                                                        class="inline-block {{ $bgClass }} {{ $textClass }} text-xs px-2 py-1 rounded-full">
                                                        {{ $question->difficulty }}
                                                    </span>
                                                @endif
                                                <span>{{ $question->created_at->format('d M Y') }}</span>
                                            </div>
                                        </div>

                                        <div class="ml-4 flex flex-col space-y-1">
                                            <a href="{{ route('questions.edit', $question->id) }}"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                    viewBox="0 0 24 24">
                                                    <path fill="#ffffff"
                                                        d="M3 21v-4.25L16.2 3.575q.3-.275.663-.425t.762-.15t.775.15t.65.45L20.425 5q.3.275.438.65T21 6.4q0 .4-.137.763t-.438.662L7.25 21zM17.6 7.8L19 6.4L17.6 5l-1.4 1.4z" />
                                                </svg>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('questions.destroy', $question->id) }}"
                                                onsubmit="return confirm('Yakin ingin menghapus soal ini?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="32"
                                                        height="32" viewBox="0 0 24 24">
                                                        <path fill="#ffffff"
                                                            d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Belum ada soal</h3>
                            <p class="text-gray-600 mb-4">Mulai dengan menambahkan soal untuk materi ini.</p>

                            <a href="{{ route('questions.create') }}?material_id={{ $material->id }}"
                                class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Soal Pertama
                            </a>
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
                'Memproses dengan AI...',
                'Membuat soal pilihan ganda...',
                'Menunggu respon dari AI...',
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
            progressText.textContent = 'n8n sedang memproses PDF dan mengirim ke AI...';

            let initialQuestionCount = {{ $material->questions->count() }};
            let pollAttempts = 0;
            let startTime = Date.now();

            console.log('Starting infinite polling with initial question count:', initialQuestionCount);

            // Wait 5 minutes before starting to poll to give n8n more time to process
            setTimeout(() => {
                progressText.textContent = 'AI sedang menganalisis materi dan membuat soal...';

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
</body>

</html>
