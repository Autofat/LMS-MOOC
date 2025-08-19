<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Sub Kategori: {{ $subCategory->name }} - KemenLH/BPLH E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .professional-gradient {
            background: linear-gradient(135deg, rgba(28, 88, 113, 1) 0%, rgba(35, 105, 135, 1) 25%, rgba(42, 122, 157, 1) 50%, rgba(49, 139, 179, 1) 75%, rgba(56, 156, 201, 1) 100%);
        }

        .subtle-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='rgba(28,88,113,0.03)' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen subtle-pattern">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <!-- Toast Messages -->
    @if (session('success'))
        <div id="subCategoryDetailSuccessToast"
            class="fixed top-8 right-4 z-50 bg-green-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl mr-4"></i>
                <p class="text-base font-medium">{{ session('success') }}</p>
                <button onclick="hideSubCategoryDetailToast('subCategoryDetailSuccessToast')"
                    class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div id="subCategoryDetailErrorToast"
            class="fixed top-8 right-4 z-50 bg-red-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-2xl mr-4"></i>
                <p class="text-base font-medium">{{ session('error') }}</p>
                <button onclick="hideSubCategoryDetailToast('subCategoryDetailErrorToast')"
                    class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Hero Section -->
    <div class="professional-gradient text-white py-12 mb-8">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-6 border border-white/30">
                        <i class="fas fa-layer-group text-4xl text-white"></i>
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    {{ $subCategory->name }}
                </h1>
                <p class="text-xl text-blue-100 mb-2">
                    Sub Kategori dari {{ $subCategory->category->name }}
                </p>
                @if ($subCategory->description)
                    <p class="text-lg text-blue-200 mb-8 max-w-3xl mx-auto">
                        {{ $subCategory->description }}
                    </p>
                @endif
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('materials.category.detail', ['category' => $subCategory->category->name]) }}"
                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold py-3 px-6 rounded-full border border-white/30 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Kategori
                    </a>
                    <a href="{{ route('materials.create') }}?sub_category_id={{ $subCategory->id }}"
                        class="text-white font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105"
                        style="background: rgba(28,88,113,0.8);" onmouseover="this.style.background='rgba(28,88,113,1)'"
                        onmouseout="this.style.background='rgba(28,88,113,0.8)'">
                        <i class="fas fa-plus mr-2"></i>Tambah Materi Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pb-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border"
                style="border-color: rgba(28,88,113,0.2);">
                <div class="flex items-center">
                    <div class="p-3 rounded-full" style="background-color: rgba(28,88,113,0.1);">
                        <i class="fas fa-file-alt text-xl" style="color: rgba(28,88,113,1);"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Materi</p>
                        <p class="text-2xl font-bold" style="color: rgba(28,88,113,1);">{{ $totalMaterials }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border"
                style="border-color: rgba(28,88,113,0.2);">
                <div class="flex items-center">
                    <div class="p-3 rounded-full" style="background-color: rgba(28,88,113,0.1);">
                        <i class="fas fa-question-circle text-xl" style="color: rgba(28,88,113,1);"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Soal</p>
                        <p class="text-2xl font-bold" style="color: rgba(28,88,113,1);">{{ $totalQuestions }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials Section -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border"
            style="border-color: rgba(28,88,113,0.2);">
            <div class="p-6 border-b" style="border-color: rgba(28,88,113,0.1);">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold" style="color: rgba(28,88,113,1);">
                        <i class="fas fa-file-alt mr-3"></i>Materi dalam "{{ $subCategory->name }}"
                    </h2>
                    <a href="{{ route('materials.create') }}?sub_category_id={{ $subCategory->id }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 shadow-md">
                        <i class="fas fa-plus mr-2"></i>Tambah Materi
                    </a>
                </div>
            </div>

            <div class="p-6">
                @if ($materials->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($materials as $material)
                            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border"
                                style="border-color: rgba(28,88,113,0.2);">
                                <!-- Card Header with Professional Theme -->
                                <div class="p-4"
                                    style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%);">
                                    <div class="flex items-center justify-between">
                                        <div class="bg-white/20 backdrop-blur-sm rounded-full p-2">
                                            <i class="fas fa-file-text text-white text-lg"></i>
                                        </div>
                                        <span
                                            class="bg-white/20 backdrop-blur-sm text-white text-xs px-3 py-1 rounded-full">
                                            {{ $material->questions->count() }} soal
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="mb-4">
                                        <h3 class="text-xl font-semibold text-gray-800 truncate mb-2">
                                            {{ $material->title }}
                                        </h3>
                                        @if ($material->description)
                                            <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                                                {{ $material->description }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- File Info with Professional Icons -->
                                    <div class="flex items-center text-sm text-gray-500 mb-4 p-3 rounded-lg"
                                        style="background-color: rgba(28,88,113,0.05);">
                                        <i class="fas fa-file-pdf mr-2" style="color: rgba(28,88,113,1);"></i>
                                        <span class="truncate">{{ $material->file_name }}</span>
                                    </div>

                                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-weight mr-1" style="color: rgba(28,88,113,0.7);"></i>
                                            <span>{{ $material->file_size_human ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-question-circle mr-1"
                                                style="color: rgba(28,88,113,0.7);"></i>
                                            <span>{{ $material->questions->count() }} soal</span>
                                        </div>
                                    </div>

                                    <div class="text-xs text-gray-400 mb-4 flex items-center">
                                        <i class="fas fa-calendar mr-1" style="color: rgba(28,88,113,0.6);"></i>
                                        Upload: {{ $material->created_at->format('d M Y H:i') }}
                                    </div>

                                    <!-- Action Buttons with Professional Theme -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('materials.show', $material->id) }}?from=subcategory&subcategory_id={{ $subCategory->id }}"
                                            class="flex-1 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105"
                                            style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%); box-shadow: 0 4px 6px rgba(28,88,113,0.3);"
                                            onmouseover="this.style.background='linear-gradient(135deg, rgba(35,105,135,1) 0%, rgba(42,122,157,1) 100%)'"
                                            onmouseout="this.style.background='linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%)'">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>
                                        
                                        @if ($material->questions->count() > 0)
                                            <a href="{{ route('materials.download.questions.excel', $material->id) }}"
                                                class="flex-1 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105"
                                                style="background: linear-gradient(135deg, rgba(42,122,157,1) 0%, rgba(49,139,179,1) 100%); box-shadow: 0 4px 6px rgba(42,122,157,0.3);"
                                                onmouseover="this.style.background='linear-gradient(135deg, rgba(49,139,179,1) 0%, rgba(56,156,201,1) 100%)'"
                                                onmouseout="this.style.background='linear-gradient(135deg, rgba(42,122,157,1) 0%, rgba(49,139,179,1) 100%)'"
                                                title="Download {{ $material->questions->count() }} soal dalam format Excel">
                                                <i class="fas fa-file-excel mr-1"></i>Download Soal
                                                ({{ $material->questions->count() }})
                                            </a>
                                        @else
                                            <span
                                                class="flex-1 text-gray-500 text-center py-2 px-3 rounded-lg text-sm bg-gray-200"
                                                title="Belum ada soal untuk materi ini">
                                                <i class="fas fa-file-excel mr-1"></i>Belum Ada Soal
                                            </span>
                                        @endif
                                        
                                        <a href="{{ route('materials.edit', $material->id) }}?from=subcategory&subcategory_id={{ $subCategory->id }}"
                                            class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105"
                                            title="Edit materi {{ $material->title }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('materials.destroy', $material->id) }}"
                                            onsubmit="event.preventDefault(); showDeleteConfirmation(this, 'Yakin ingin menghapus materi ini? Semua soal yang terkait juga akan dihapus.'); return false;"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="from" value="subcategory">
                                            <button type="submit"
                                                class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($materials->hasPages())
                        <div class="mt-8 flex justify-center">
                            <div class="bg-white rounded-lg shadow-md p-2">
                                {{ $materials->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="bg-gray-100 rounded-full p-8 inline-block mb-6">
                            <i class="fas fa-file-alt text-5xl text-gray-400"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-600 mb-4">Belum Ada Materi</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">
                            Sub kategori ini belum memiliki materi. Tambahkan materi pertama untuk memulai pembelajaran.
                        </p>
                        <a href="{{ route('materials.create') }}?sub_category_id={{ $subCategory->id }}"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 shadow-md">
                            <i class="fas fa-plus mr-2"></i>Tambah Materi Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bulk Download Section -->
        @if ($materials->count() > 0 && $materials->sum(function($material) { return $material->questions->count(); }) > 0)
            <div class="mt-8 bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border"
                style="border-color: rgba(28,88,113,0.2);">
                <div class="p-6 border-b" style="border-color: rgba(28,88,113,0.1);">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-2" style="color: rgba(28,88,113,1);">
                                <i class="fas fa-download mr-3"></i>Download Soal Pilihan
                            </h2>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1" style="color: rgba(28,88,113,0.7);"></i>
                                Pilih soal-soal tertentu dari berbagai materi untuk didownload dalam satu file Excel.
                            </p>
                        </div>
                        <div class="flex space-x-3">
                            <button id="selectAllQuestions" onclick="toggleSelectAllQuestions()"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-all duration-300">
                                <i class="fas fa-check-square mr-2"></i>Pilih Semua Soal
                            </button>
                            <button id="downloadSelectedBtn" onclick="downloadSelectedQuestions()" disabled
                                class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-all duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                <i class="fas fa-file-excel mr-2"></i>Download Terpilih (<span id="selectedCount">0</span>)
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div id="questionsSelection" class="space-y-6">
                        @foreach ($materials as $material)
                            @if ($material->questions->count() > 0)
                                <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-xl p-5 border border-slate-200">
                                    <!-- Material Header -->
                                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200">
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" 
                                                    class="material-header-checkbox w-5 h-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500"
                                                    data-material-id="{{ $material->id }}"
                                                    onchange="toggleMaterialQuestions({{ $material->id }})">
                                                <span class="ml-3"></span>
                                            </label>
                                            <div class="bg-blue-100 p-2 rounded-lg">
                                                <i class="fas fa-file-text text-blue-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800">{{ $material->title }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    <i class="fas fa-question-circle mr-1"></i>
                                                    {{ $material->questions->count() }} soal tersedia
                                                </p>
                                            </div>
                                        </div>
                                        <button onclick="toggleMaterialQuestions({{ $material->id }})" 
                                            class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                            <span id="toggle-text-{{ $material->id }}">Pilih Semua dari Materi Ini</span>
                                        </button>
                                    </div>

                                    <!-- Questions List -->
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                        @foreach ($material->questions as $question)
                                            <div class="flex items-start space-x-3 p-4 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-all duration-200 min-h-[120px]">
                                                <label class="flex items-start cursor-pointer flex-1">
                                                    <input type="checkbox" 
                                                        class="question-checkbox w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 mt-1 flex-shrink-0"
                                                        data-question-id="{{ $question->id }}"
                                                        data-material-id="{{ $material->id }}"
                                                        onchange="updateSelectionCount()">
                                                    <div class="ml-3 flex-1">
                                                        @if (!empty($question->question))
                                                            <div class="text-sm font-medium text-gray-800 leading-relaxed mb-3">
                                                                {!! nl2br(e(preg_replace('/^\s+/m', '', trim($question->question)))) !!}
                                                            </div>
                                                        @else
                                                            <div class="text-sm font-medium text-gray-600 leading-relaxed mb-3 italic">
                                                                Soal #{{ $question->id }} - {{ $question->created_at->format('d M Y H:i') }}
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="flex items-center gap-2 text-xs flex-wrap">
                                                            @if (!empty($question->tipe_soal))
                                                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded flex-shrink-0">
                                                                    {{ $question->tipe_soal == 'pilihan_ganda' ? 'Multiple Choice' : ucfirst(str_replace('_', ' ', $question->tipe_soal)) }}
                                                                </span>
                                                            @endif
                                                            @if (!empty($question->difficulty))
                                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded flex-shrink-0">
                                                                    {{ ucfirst($question->difficulty) }}
                                                                </span>
                                                            @endif
                                                            @if (!empty($question->answer))
                                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded flex-shrink-0">
                                                                    Jawaban: {{ $question->answer }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        
                                                        {{-- Debug info - remove in production --}}
                                                        @if (config('app.debug'))
                                                            <div class="mt-2 text-xs text-gray-400 border-t pt-2">
                                                                ID: {{ $question->id }} | 
                                                                Material: {{ $material->id }} |
                                                                Created: {{ $question->created_at->format('d/m/Y H:i') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        
                        @if ($materials->sum(function($material) { return $material->questions->count(); }) == 0)
                            <div class="text-center py-8">
                                <div class="bg-gray-100 rounded-full p-6 inline-block mb-4">
                                    <i class="fas fa-question-circle text-3xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Soal</h4>
                                <p class="text-gray-500">Belum ada materi yang memiliki soal untuk didownload.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Bulk download functionality for individual questions
        let allQuestionsSelected = false;

        function toggleSelectAllQuestions() {
            const questionCheckboxes = document.querySelectorAll('.question-checkbox');
            const materialHeaderCheckboxes = document.querySelectorAll('.material-header-checkbox');
            const selectAllBtn = document.getElementById('selectAllQuestions');
            
            allQuestionsSelected = !allQuestionsSelected;
            
            // Toggle all question checkboxes
            questionCheckboxes.forEach(checkbox => {
                checkbox.checked = allQuestionsSelected;
            });
            
            // Toggle all material header checkboxes
            materialHeaderCheckboxes.forEach(checkbox => {
                checkbox.checked = allQuestionsSelected;
            });
            
            // Update button text and style
            if (allQuestionsSelected) {
                selectAllBtn.innerHTML = '<i class="fas fa-square mr-2"></i>Batalkan Pilih Semua';
                selectAllBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                selectAllBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
            } else {
                selectAllBtn.innerHTML = '<i class="fas fa-check-square mr-2"></i>Pilih Semua Soal';
                selectAllBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
                selectAllBtn.classList.add('bg-blue-500', 'hover:bg-blue-600');
            }
            
            // Update toggle texts for materials
            materialHeaderCheckboxes.forEach(checkbox => {
                const materialId = checkbox.dataset.materialId;
                const toggleText = document.getElementById(`toggle-text-${materialId}`);
                if (toggleText) {
                    toggleText.textContent = allQuestionsSelected ? 'Batalkan Pilih Semua' : 'Pilih Semua dari Materi Ini';
                }
            });
            
            updateSelectionCount();
        }

        function toggleMaterialQuestions(materialId) {
            const materialCheckbox = document.querySelector(`.material-header-checkbox[data-material-id="${materialId}"]`);
            const questionCheckboxes = document.querySelectorAll(`.question-checkbox[data-material-id="${materialId}"]`);
            const toggleText = document.getElementById(`toggle-text-${materialId}`);
            
            if (!materialCheckbox || !questionCheckboxes.length) {
                // console.log('toggleMaterialQuestions: elements not found', {materialId, materialCheckbox, questionCheckboxes: questionCheckboxes.length});
                return;
            }
            
            // Check if this was called by checkbox change or button click
            const isCheckboxEvent = event && event.target === materialCheckbox;
            const currentlyChecked = materialCheckbox.checked;
            const allQuestionsChecked = Array.from(questionCheckboxes).every(cb => cb.checked);
            
            let newState;
            
            if (isCheckboxEvent) {
                // If material checkbox was clicked, use its current state
                newState = currentlyChecked;
                // console.log('Material checkbox clicked:', {materialId, newState});
            } else {
                // If button was clicked, toggle based on current state of questions
                newState = !allQuestionsChecked;
                materialCheckbox.checked = newState;
                // console.log('Material button clicked:', {materialId, newState, allQuestionsChecked});
            }
            
            // Update all question checkboxes
            questionCheckboxes.forEach(checkbox => {
                checkbox.checked = newState;
            });
            
            // Update toggle text
            if (toggleText) {
                toggleText.textContent = newState ? 'Batalkan Pilih Semua' : 'Pilih Semua dari Materi Ini';
            }
            
            updateSelectionCount();
        }

        function updateSelectionCount() {
            const selectedQuestions = document.querySelectorAll('.question-checkbox:checked');
            const downloadBtn = document.getElementById('downloadSelectedBtn');
            const countSpan = document.getElementById('selectedCount');
            const selectAllBtn = document.getElementById('selectAllQuestions');
            
            const count = selectedQuestions.length;
            
            // Debug log
            // console.log('updateSelectionCount called:', {
            //     selectedQuestions: selectedQuestions.length,
            //     count: count
            // });
            
            // Update counter display
            if (countSpan) {
                countSpan.textContent = count;
            }
            
            // Update download button
            if (downloadBtn) {
                if (count > 0) {
                    downloadBtn.disabled = false;
                    downloadBtn.innerHTML = `<i class="fas fa-file-excel mr-2"></i>Download ${count} Soal Terpilih`;
                    downloadBtn.classList.remove('disabled:bg-gray-400');
                    downloadBtn.classList.remove('bg-gray-400');
                } else {
                    downloadBtn.disabled = true;
                    downloadBtn.innerHTML = '<i class="fas fa-file-excel mr-2"></i>Download Terpilih (0)';
                    downloadBtn.classList.add('disabled:bg-gray-400');
                }
            }
            
            // Update material header checkboxes based on their questions
            const materialHeaderCheckboxes = document.querySelectorAll('.material-header-checkbox');
            materialHeaderCheckboxes.forEach(checkbox => {
                const materialId = checkbox.dataset.materialId;
                const materialQuestions = document.querySelectorAll(`.question-checkbox[data-material-id="${materialId}"]`);
                const checkedQuestions = document.querySelectorAll(`.question-checkbox[data-material-id="${materialId}"]:checked`);
                const toggleText = document.getElementById(`toggle-text-${materialId}`);
                
                if (checkedQuestions.length === materialQuestions.length && materialQuestions.length > 0) {
                    checkbox.checked = true;
                    checkbox.indeterminate = false;
                    if (toggleText) toggleText.textContent = 'Batalkan Pilih Semua';
                } else if (checkedQuestions.length > 0) {
                    checkbox.checked = false;
                    checkbox.indeterminate = true;
                    if (toggleText) toggleText.textContent = 'Pilih Semua dari Materi Ini';
                } else {
                    checkbox.checked = false;
                    checkbox.indeterminate = false;
                    if (toggleText) toggleText.textContent = 'Pilih Semua dari Materi Ini';
                }
            });
            
            // Update select all button state
            const allQuestions = document.querySelectorAll('.question-checkbox');
            const allChecked = allQuestions.length > 0 && Array.from(allQuestions).every(cb => cb.checked);
            const noneChecked = Array.from(allQuestions).every(cb => !cb.checked);
            
            if (selectAllBtn) {
                if (allChecked && allQuestions.length > 0) {
                    allQuestionsSelected = true;
                    selectAllBtn.innerHTML = '<i class="fas fa-square mr-2"></i>Batalkan Pilih Semua';
                    selectAllBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                    selectAllBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
                } else if (noneChecked) {
                    allQuestionsSelected = false;
                    selectAllBtn.innerHTML = '<i class="fas fa-check-square mr-2"></i>Pilih Semua Soal';
                    selectAllBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
                    selectAllBtn.classList.add('bg-blue-500', 'hover:bg-blue-600');
                }
            }
        }

        function downloadSelectedQuestions() {
            const selectedQuestions = document.querySelectorAll('.question-checkbox:checked');
            
            if (selectedQuestions.length === 0) {
                alert('Pilih setidaknya satu soal untuk didownload.');
                return;
            }
            
            const questionIds = Array.from(selectedQuestions).map(checkbox => checkbox.dataset.questionId);
            const downloadBtn = document.getElementById('downloadSelectedBtn');
            const originalText = downloadBtn.innerHTML;
            
            // Show loading state
            downloadBtn.disabled = true;
            downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses Download...';
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("materials.download.bulk.questions.excel") }}';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add question IDs
            questionIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'question_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            // Add sub category context
            const subCategoryInput = document.createElement('input');
            subCategoryInput.type = 'hidden';
            subCategoryInput.name = 'sub_category_id';
            subCategoryInput.value = '{{ $subCategory->id }}';
            form.appendChild(subCategoryInput);
            
            document.body.appendChild(form);
            form.submit();
            
            // Reset button after short delay
            setTimeout(() => {
                downloadBtn.disabled = false;
                downloadBtn.innerHTML = originalText;
                document.body.removeChild(form);
            }, 2000);
        }

        // Toast functionality for subcategory detail page
        window.hideSubCategoryDetailToast = function(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        // Auto-hide toasts after 5 seconds and initialize checkbox listeners
        document.addEventListener('DOMContentLoaded', function() {
            const successToast = document.getElementById('subCategoryDetailSuccessToast');
            const errorToast = document.getElementById('subCategoryDetailErrorToast');

            if (successToast) {
                setTimeout(() => {
                    hideSubCategoryDetailToast('subCategoryDetailSuccessToast');
                }, 5000);
            }

            if (errorToast) {
                setTimeout(() => {
                    hideSubCategoryDetailToast('subCategoryDetailErrorToast');
                }, 5000);
            }
            
            // Initialize checkbox event listeners to ensure they work properly
            const questionCheckboxes = document.querySelectorAll('.question-checkbox');
            questionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // console.log('Checkbox changed:', this.dataset.questionId, this.checked);
                    updateSelectionCount();
                });
            });
            
            const materialHeaderCheckboxes = document.querySelectorAll('.material-header-checkbox');
            materialHeaderCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const materialId = this.dataset.materialId;
                    // console.log('Material header checkbox changed:', materialId, this.checked);
                    toggleMaterialQuestions(materialId);
                });
            });
            
            // Initial count update
            updateSelectionCount();
        });
    </script>

    <!-- Include Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')

    {{-- Footer --}}
    @include('components.footer')

    <script>
        // Fallback function for delete confirmation if component not loaded
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                if (typeof window.showDeleteConfirmation !== 'function') {
                    console.warn('showDeleteConfirmation not found from component, using fallback');
                    window.showDeleteConfirmation = function(form, message) {
                        if (confirm(message)) {
                            form.onsubmit = null; // Remove the preventDefault
                            form.submit();
                        }
                    };
                }
            }, 100); // Small delay to ensure component has loaded
        });
    </script>


</body>

</html>
