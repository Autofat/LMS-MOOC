<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Kategori: {{ $category }} - KemenLH/BPLH E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .professional-gradient {
            background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 25%, rgba(42,122,157,1) 50%, rgba(49,139,179,1) 75%, rgba(56,156,201,1) 100%);
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
    @if(session('success'))
        <div id="categoryDetailSuccessToast" class="fixed top-8 right-4 z-50 bg-green-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl mr-4"></i>
                <p class="text-base font-medium">{{ session('success') }}</p>
                <button onclick="hideCategoryDetailToast('categoryDetailSuccessToast')" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="categoryDetailErrorToast" class="fixed top-8 right-4 z-50 bg-red-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-2xl mr-4"></i>
                <p class="text-base font-medium">{{ session('error') }}</p>
                <button onclick="hideCategoryDetailToast('categoryDetailErrorToast')" class="ml-4 text-white hover:text-gray-200">
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
                        <i class="fas fa-tags text-4xl text-white"></i>
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Detail Kategori: {{ $category }}
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    Semua soal dari materi dalam kategori {{ $category }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('materials.index') }}"
                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold py-3 px-6 rounded-full border border-white/30 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Kelola Materi
                    </a>
                    @if($totalQuestions > 0)
                        <a href="{{ route('materials.download.category.excel', ['category' => $category]) }}"
                            class="text-white font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105"
                            style="background: rgba(28,88,113,0.8);"
                            onmouseover="this.style.background='rgba(28,88,113,1)'"
                            onmouseout="this.style.background='rgba(28,88,113,0.8)'">
                            <i class="fas fa-download mr-2"></i>Download Semua Soal ({{ $totalQuestions }})
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pb-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border" style="border-color: rgba(28,88,113,0.2);">
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
            
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border" style="border-color: rgba(28,88,113,0.2);">
                <div class="flex items-center">
                    <div class="p-3 rounded-full" style="background-color: rgba(28,88,113,0.1);">
                        <i class="fas fa-list-check text-xl" style="color: rgba(28,88,113,1);"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Soal</p>
                        <p class="text-2xl font-bold" style="color: rgba(28,88,113,1);">{{ $totalQuestions }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials List -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border mb-8" style="border-color: rgba(28,88,113,0.2);">
            <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: rgba(28,88,113,1);">
                <i class="fas fa-folder-open mr-3"></i>Materi dalam Kategori
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($materials as $material)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-gray-800 text-base truncate">{{ $material->title }}</h3>
                            <div class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                {{ $material->questions->count() }} soal
                            </div>
                        </div>
                        
                        <div class="text-xs text-gray-500 mb-3">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $material->created_at->format('d M Y') }}
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('materials.show', $material->id) }}"
                               class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-3 rounded-lg text-xs transition-all">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                            @if($material->questions->count() > 0)
                                <a href="{{ route('materials.download.questions.excel', $material->id) }}"
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded-lg text-xs transition-all">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Questions List -->
        @if($questions->count() > 0)
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border" style="border-color: rgba(28,88,113,0.2);">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold flex items-center" style="color: rgba(28,88,113,1);">
                        <i class="fas fa-question-circle mr-3"></i>Daftar Soal
                    </h2>
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $questions->firstItem() }} - {{ $questions->lastItem() }} dari {{ $questions->total() }} soal
                    </div>
                </div>
                
                <div class="space-y-6">
                    @foreach($questions as $index => $question)
                        <div class="border-l-4 border-blue-500 bg-gradient-to-r from-blue-50 to-transparent p-6 rounded-r-xl hover:shadow-md transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center mb-3">
                                        <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full mr-3">
                                            #{{ ($questions->currentPage() - 1) * $questions->perPage() + $index + 1 }}
                                        </span>
                                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-3 py-1 rounded-lg font-semibold text-sm shadow-md">
                                            <i class="fas fa-file-alt mr-1"></i>
                                            {{ $question->material->title }}
                                        </div>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                        {!! nl2br(e($question->question ?? 'Tidak ada teks soal')) !!}
                                    </h3>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('questions.edit', $question->id) }}" 
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-xs font-medium transition-all duration-200 transform hover:scale-105"
                                       title="Edit Soal">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <button onclick="confirmDeleteQuestion({{ $question->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-medium transition-all duration-200 transform hover:scale-105"
                                            title="Hapus Soal">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                            
                            @php
                                $options = $question->options ?? [];
                            @endphp
                            
                            @if(!empty($options))
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                    @foreach(['A', 'B', 'C', 'D'] as $optionKey)
                                        @if(isset($options[$optionKey]) && !empty($options[$optionKey]))
                                            <div class="flex items-start space-x-3 p-3 rounded-lg {{ strtoupper($question->answer ?? '') === $optionKey ? 'bg-green-100 border-2 border-green-500' : 'bg-gray-50' }}">
                                                <span class="font-bold text-sm {{ strtoupper($question->answer ?? '') === $optionKey ? 'text-green-700' : 'text-gray-600' }}">
                                                    {{ $optionKey }}.
                                                </span>
                                                <span class="text-sm {{ strtoupper($question->answer ?? '') === $optionKey ? 'text-green-800 font-semibold' : 'text-gray-700' }}">
                                                    {{ $options[$optionKey] }}
                                                </span>
                                                @if(strtoupper($question->answer ?? '') === $optionKey)
                                                    <i class="fas fa-check-circle text-green-500 ml-auto"></i>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 pt-4 border-t border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <i class="fas fa-bullseye mr-1"></i>
                                        Jawaban: <strong class="ml-1 text-green-600">{{ strtoupper($question->answer ?? 'N/A') }}</strong>
                                    </span>
                                    @if($question->difficulty)
                                        <span class="flex items-center">
                                            <i class="fas fa-chart-bar mr-1"></i>
                                            {{ ucfirst($question->difficulty) }}
                                        </span>
                                    @endif
                                </div>
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $question->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($questions->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $questions->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-12 text-center border" style="border-color: rgba(28,88,113,0.2);">
                <div class="max-w-md mx-auto">
                    <div class="bg-gray-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-question-circle text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Belum Ada Soal</h3>
                    <p class="text-gray-600 mb-6">
                        Belum ada soal yang dibuat untuk kategori "{{ $category }}". 
                        Silakan generate soal dari materi yang ada.
                    </p>
                    <a href="{{ route('materials.index') }}" 
                       class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 transform hover:scale-105"
                       style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%);">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Kelola Materi
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Soal</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus soal ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="deleteConfirmBtn" 
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Hapus
                    </button>
                    <button id="deleteCancelBtn" 
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toast functionality for category detail page
        window.hideCategoryDetailToast = function(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }
        
        // Delete question confirmation with modal
        let questionIdToDelete = null;
        
        window.confirmDeleteQuestion = function(questionId) {
            questionIdToDelete = questionId;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        
        // Modal event handlers
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
            const deleteCancelBtn = document.getElementById('deleteCancelBtn');
            
            // Confirm delete
            deleteConfirmBtn.addEventListener('click', function() {
                if (questionIdToDelete) {
                    // Create a form and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/questions/${questionIdToDelete}`;
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    form.appendChild(csrfToken);
                    
                    // Add method override for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
            
            // Cancel delete
            deleteCancelBtn.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                questionIdToDelete = null;
            });
            
            // Close modal when clicking outside
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    deleteModal.classList.add('hidden');
                    questionIdToDelete = null;
                }
            });
            
            // Auto hide toasts after 5 seconds
            const successToast = document.getElementById('categoryDetailSuccessToast');
            const errorToast = document.getElementById('categoryDetailErrorToast');
            
            if (successToast) {
                setTimeout(() => {
                    hideCategoryDetailToast('categoryDetailSuccessToast');
                }, 5000);
            }
            
            if (errorToast) {
                setTimeout(() => {
                    hideCategoryDetailToast('categoryDetailErrorToast');
                }, 5000);
            }
        });
    </script>
</body>

</html>
