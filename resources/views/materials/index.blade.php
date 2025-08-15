<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Materi - KemenLH/BPLH E-Learning Platform</title>
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
        @include('components.toast', [
            'id' => 'materialsSuccessToast',
            'type' => 'success',
            'message' => session('success'),
        ])
    @endif

    @if (session('error'))
        @include('components.toast', [
            'id' => 'materialsErrorToast',
            'type' => 'error',
            'message' => session('error'),
        ])
    @endif

    <!-- Hero Section -->
    <div class="professional-gradient text-white py-16 mb-8">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-6 border border-white/30">
                        <i class="fas fa-graduation-cap text-4xl text-white"></i>
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Platform Pembuatan Soal Berbasis AI
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    Menghadirkan sistem pembuatan soal otomatis berdasarkan materi pembelajaran untuk Platform
                    E-Learning KemenLH/BPLH
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('materials.create') }}"
                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold py-3 px-6 rounded-full border border-white/30 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>Upload Materi Baru
                    </a>
                    <a href="{{ route('questions.manage') }}"
                        class="text-white font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105"
                        style="background: rgba(28,88,113,0.8);" onmouseover="this.style.background='rgba(28,88,113,1)'"
                        onmouseout="this.style.background='rgba(28,88,113,0.8)'">
                        <i class="fas fa-question-circle mr-2"></i>Kelola Soal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pb-8">
        @if ($categories->count() > 0)
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border"
                    style="border-color: rgba(28,88,113,0.2);">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full" style="background-color: rgba(28,88,113,0.1);">
                            <i class="fas fa-tags text-xl" style="color: rgba(28,88,113,1);"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Kategori</p>
                            <p class="text-2xl font-bold" style="color: rgba(28,88,113,1);">{{ $categories->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border"
                    style="border-color: rgba(28,88,113,0.2);">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full" style="background-color: rgba(28,88,113,0.1);">
                            <i class="fas fa-layer-group text-xl" style="color: rgba(28,88,113,1);"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Sub Kategori</p>
                            <p class="text-2xl font-bold" style="color: rgba(28,88,113,1);">
                                {{ $totalSubCategories ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="mb-8">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border"
                    style="border-color: rgba(28,88,113,0.2);">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold mb-2" style="color: rgba(28,88,113,1);">
                                <i class="fas fa-tags mr-2"></i>Kategori
                            </h2>
                            <p class="text-sm text-gray-600 max-w-2xl">
                                <i class="fas fa-info-circle mr-1" style="color: rgba(28,88,113,0.7);"></i>
                                Materi/bahan ajar dengan kategori yang sama dapat didownload soalnya secara bersamaan.
                            </p>
                        </div>
                        <div>
                            <button onclick="showAddCategoryModal()"
                                class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-plus mr-2"></i>Tambah Kategori
                            </button>
                        </div>
                    </div>

                    @if ($categories->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($categories as $category)
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200 hover:shadow-lg transition-all duration-300">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="font-semibold text-gray-800 text-lg">{{ $category->name }}</h3>
                                        <div class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                            {{ $category->materials_count }} materi
                                        </div>
                                    </div>

                                    @if ($category->description)
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $category->description }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                                        <span>
                                            <i class="fas fa-question-circle mr-1"></i>
                                            {{ $category->questions_count }} soal
                                        </span>
                                    </div>

                                    <div class="flex space-x-2">
                                        <a href="{{ route('materials.category.detail', ['category' => $category->name]) }}"
                                            class="flex-1 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105"
                                            style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%); box-shadow: 0 4px 6px rgba(28,88,113,0.3);"
                                            onmouseover="this.style.background='linear-gradient(135deg, rgba(35,105,135,1) 0%, rgba(42,122,157,1) 100%)'"
                                            onmouseout="this.style.background='linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%)'"
                                            title="Lihat detail soal dari kategori {{ $category->name }}">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>

                                        @if ($category->questions_count > 0)
                                            <a href="{{ route('materials.download.category.excel', ['category' => $category->name]) }}"
                                                class="flex-1 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105"
                                                style="background: linear-gradient(135deg, rgba(42,122,157,1) 0%, rgba(49,139,179,1) 100%); box-shadow: 0 4px 6px rgba(42,122,157,0.3);"
                                                onmouseover="this.style.background='linear-gradient(135deg, rgba(49,139,179,1) 0%, rgba(56,156,201,1) 100%)'"
                                                onmouseout="this.style.background='linear-gradient(135deg, rgba(42,122,157,1) 0%, rgba(49,139,179,1) 100%)'"
                                                title="Download semua {{ $category->questions_count }} soal dari kategori {{ $category->name }}">
                                                <i class="fas fa-file-excel mr-1"></i>Download Soal
                                                ({{ $category->questions_count }})
                                            </a>
                                        @else
                                            <span
                                                class="flex-1 text-gray-500 text-center py-2 px-3 rounded-lg text-sm bg-gray-200"
                                                title="Belum ada soal untuk kategori ini">
                                                <i class="fas fa-file-excel mr-1"></i>Belum Ada Soal
                                            </span>
                                        @endif

                                        <button
                                            class="edit-category-btn bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105"
                                            data-category-id="{{ $category->id }}"
                                            data-category-name="{{ $category->name }}"
                                            data-category-description="{{ $category->description ?? '' }}"
                                            title="Edit kategori {{ $category->name }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form method="POST"
                                            action="{{ route('categories.destroy', ['id' => $category->id]) }}"
                                            onsubmit="event.preventDefault(); showDeleteConfirmation(this, 'Yakin ingin menghapus kategori {{ $category->name }}? Semua materi dan soal dalam kategori ini akan dihapus.'); return false;"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-tags text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Kategori</h3>
                            <p class="text-gray-500 mb-4">Buat kategori pertama untuk mengelompokkan materi
                                pembelajaran Anda</p>
                            <button onclick="showAddCategoryModal()"
                                class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-plus mr-2"></i>Buat Kategori Pertama
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Empty State with Professional Theme -->
            <div class="bg-white/95 backdrop-blur-sm shadow-xl rounded-3xl p-12 text-center border"
                style="border-color: rgba(28,88,113,0.2);">
                <div class="mb-6" style="color: rgba(28,88,113,1);">
                    <div class="rounded-full p-8 inline-block" style="background-color: rgba(28,88,113,0.1);">
                        <i class="fas fa-tags text-5xl" style="color: rgba(28,88,113,1);"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Mulai Dengan Membuat Kategori</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Belum ada kategori untuk mengelompokkan materi pembelajaran. Buat kategori pertama untuk memulai
                    mengorganisir materi dan soal Anda.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="showAddCategoryModal()"
                        class="text-white font-bold py-3 px-8 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg"
                        style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(42,122,157,1) 100%);">
                        <i class="fas fa-tags mr-2"></i>Buat Kategori Pertama
                    </button>
                    <a href="{{ route('materials.create') }}"
                        class="bg-white border-2 font-bold py-3 px-8 rounded-full transition-all duration-300 hover:bg-gray-50"
                        style="border-color: rgba(28,88,113,1); color: rgba(28,88,113,1);">
                        <i class="fas fa-plus mr-2"></i>Upload Materi
                    </a>
                </div>

                <!-- Decorative Elements -->
                <div class="mt-8 flex justify-center space-x-4" style="color: rgba(28,88,113,0.4);">
                    <i class="fas fa-tags text-2xl"></i>
                    <i class="fas fa-layer-group text-3xl"></i>
                    <i class="fas fa-folder-open text-2xl"></i>
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
        @endif
    </div>

    <!-- Include Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')

    <!-- Add Category Modal -->
    <div id="addCategoryModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tags text-2xl text-emerald-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Tambah Kategori Baru</h3>
                <p class="text-gray-600">Buat kategori baru untuk mengelompokkan materi pembelajaran</p>
            </div>

            <form id="addCategoryForm" class="space-y-4">
                @csrf
                <div>
                    <label for="categoryName" class="block text-sm font-semibold mb-2 text-gray-700">
                        <i class="fas fa-tag mr-1"></i>Nama Kategori
                    </label>
                    <input type="text" id="categoryName" name="categoryName" required
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:outline-none transition-colors"
                        placeholder="Contoh: Pengelolaan Limbah, AMDAL, dll..." maxlength="100">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 100 karakter</p>
                </div>

                <div>
                    <label for="categoryDescription" class="block text-sm font-semibold mb-2 text-gray-700">
                        <i class="fas fa-align-left mr-1"></i>Deskripsi (Opsional)
                    </label>
                    <textarea id="categoryDescription" name="categoryDescription" rows="3"
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:outline-none transition-colors resize-none"
                        placeholder="Deskripsi singkat tentang kategori ini..." maxlength="255"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Maksimal 255 karakter</p>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeAddCategoryModal()"
                        class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Buat Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-edit text-2xl text-amber-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Edit Kategori</h3>
                <p class="text-gray-600">Perbarui informasi kategori</p>
            </div>

            <form id="editCategoryForm" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editCategoryId" name="editCategoryId">
                <div>
                    <label for="editCategoryName" class="block text-sm font-semibold mb-2 text-gray-700">
                        <i class="fas fa-tag mr-1"></i>Nama Kategori
                    </label>
                    <input type="text" id="editCategoryName" name="categoryName" required
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-amber-500 focus:outline-none transition-colors"
                        placeholder="Contoh: Pengelolaan Limbah, AMDAL, dll..." maxlength="100">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 100 karakter</p>
                </div>

                <div>
                    <label for="editCategoryDescription" class="block text-sm font-semibold mb-2 text-gray-700">
                        <i class="fas fa-align-left mr-1"></i>Deskripsi (Opsional)
                    </label>
                    <textarea id="editCategoryDescription" name="categoryDescription" rows="3"
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-amber-500 focus:outline-none transition-colors resize-none"
                        placeholder="Deskripsi singkat tentang kategori ini..." maxlength="255"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Maksimal 255 karakter</p>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditCategoryModal()"
                        class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation function - using the one from delete-confirmation-modal component
            // Ensure showDeleteConfirmation is available
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

            // Add Category Modal functions
            window.showAddCategoryModal = function() {
                document.getElementById('addCategoryModal').classList.remove('hidden');
                document.getElementById('categoryName').focus();
            };

            window.closeAddCategoryModal = function() {
                document.getElementById('addCategoryModal').classList.add('hidden');
                document.getElementById('addCategoryForm').reset();
            };

            // Edit Category Modal functions
            window.showEditCategoryModal = function(id, name, description) {
                document.getElementById('editCategoryId').value = id;
                document.getElementById('editCategoryName').value = name;
                document.getElementById('editCategoryDescription').value = description || '';
                document.getElementById('editCategoryModal').classList.remove('hidden');
                document.getElementById('editCategoryName').focus();
            };

            window.closeEditCategoryModal = function() {
                document.getElementById('editCategoryModal').classList.add('hidden');
                document.getElementById('editCategoryForm').reset();
            };

            // Add event listeners for edit category buttons
            document.addEventListener('click', function(e) {
                if (e.target.closest('.edit-category-btn')) {
                    e.preventDefault();
                    const button = e.target.closest('.edit-category-btn');
                    const categoryId = button.getAttribute('data-category-id');
                    const categoryName = button.getAttribute('data-category-name');
                    const categoryDescription = button.getAttribute('data-category-description');
                    showEditCategoryModal(categoryId, categoryName, categoryDescription);
                }
            });

            // Handle add category form submission
            document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const categoryName = document.getElementById('categoryName').value.trim();
                const categoryDescription = document.getElementById('categoryDescription').value.trim();

                if (!categoryName) {
                    alert('Nama kategori harus diisi!');
                    return;
                }

                // Disable submit button and show loading
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Membuat...';

                // Send POST request to create category
                fetch('{{ route('materials.categories.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            categoryName: categoryName,
                            categoryDescription: categoryDescription
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;

                        if (data.success) {
                            closeAddCategoryModal();

                            // Show success message using global toast function
                            const successToast = document.createElement('div');
                            successToast.id = 'categorySuccessToast';
                            successToast.className =
                                'fixed top-8 right-4 z-50 bg-green-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md transform translate-x-full opacity-0 transition-all duration-300';
                            successToast.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-2xl mr-4"></i>
                            <p class="text-base font-medium">${data.message}</p>
                            <button onclick="hideToast('categorySuccessToast')" class="ml-4 text-white hover:text-gray-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                            document.body.appendChild(successToast);

                            // Show and auto-hide toast
                            if (typeof window.showToast === 'function') {
                                showToast('categorySuccessToast');
                            }

                            // Reload page to show new category
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            // Show error message
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat membuat kategori. Silakan coba lagi.');
                    });
            });

            // Handle edit category form submission
            document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const categoryId = document.getElementById('editCategoryId').value;
                const categoryName = document.getElementById('editCategoryName').value.trim();
                const categoryDescription = document.getElementById('editCategoryDescription').value.trim();

                if (!categoryName) {
                    alert('Nama kategori harus diisi!');
                    return;
                }

                // Disable submit button and show loading
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...';

                // Send PUT request to update category
                fetch(`/categories/${categoryId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            categoryName: categoryName,
                            categoryDescription: categoryDescription
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;

                        if (data.success) {
                            closeEditCategoryModal();

                            // Show success message using global toast function
                            const successToast = document.createElement('div');
                            successToast.id = 'categoryUpdateSuccessToast';
                            successToast.className =
                                'fixed top-8 right-4 z-50 bg-green-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md transform translate-x-full opacity-0 transition-all duration-300';
                            successToast.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-2xl mr-4"></i>
                            <p class="text-base font-medium">${data.message}</p>
                            <button onclick="hideToast('categoryUpdateSuccessToast')" class="ml-4 text-white hover:text-gray-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                            document.body.appendChild(successToast);

                            // Show and auto-hide toast
                            if (typeof window.showToast === 'function') {
                                showToast('categoryUpdateSuccessToast');
                            }

                            // Reload page to show updated category
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            // Show error message
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengupdate kategori. Silakan coba lagi.');
                    });
            });

            // Close modal when clicking outside
            document.getElementById('addCategoryModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddCategoryModal();
                }
            });

            document.getElementById('editCategoryModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditCategoryModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAddCategoryModal();
                    closeEditCategoryModal();
                }
            });
        });
    </script>

    <!-- Include Help Modal Component -->
    @include('components.help-modal')

    <!-- Include Tutorial Component -->
    @include('components.tutorial')
    @include('components.footer')
</body>

</html>
