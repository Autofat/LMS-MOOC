<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $category }} - KemenLH/BPLH E-Learning Platform</title>
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
            'id' => 'categoryDetailSuccessToast',
            'type' => 'success',
            'message' => session('success'),
        ])
    @endif

    @if (session('error'))
        @include('components.toast', [
            'id' => 'categoryDetailErrorToast',
            'type' => 'error',
            'message' => session('error'),
        ])
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
                    {{ $category }}
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    Kelola sub kategori dan unduh semua soal dari kategori {{ $category }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('materials.index') }}"
                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold py-3 px-6 rounded-full border border-white/30 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Kelola Materi
                    </a>
                    @if ($totalQuestions > 0)
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
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border"
                style="border-color: rgba(28,88,113,0.2);">
                <div class="flex items-center">
                    <div class="p-3 rounded-full" style="background-color: rgba(28,88,113,0.1);">
                        <i class="fas fa-tags text-xl" style="color: rgba(28,88,113,1);"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Sub Kategori</p>
                        <p class="text-2xl font-bold" style="color: rgba(28,88,113,1);">{{ $totalSubCategories ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>

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
        </div>

        <!-- Sub Categories Section -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border mb-8"
            style="border-color: rgba(28,88,113,0.2);">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold flex items-center" style="color: rgba(28,88,113,1);">
                    <i class="fas fa-layer-group mr-3"></i>Sub Kategori
                </h2>
                <button onclick="showAddSubCategoryModal()"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 shadow-md">
                    <i class="fas fa-plus mr-2"></i>Tambah Sub Kategori
                </button>
            </div>

            @if (isset($subCategories) && $subCategories->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($subCategories as $subCategory)
                        <div
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-gray-800 text-lg">{{ $subCategory->name }}</h3>
                                <div class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    {{ $subCategory->materials_count ?? 0 }} materi
                                </div>
                            </div>

                            @if ($subCategory->description)
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $subCategory->description }}</p>
                            @endif

                            <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                                <span>
                                    <i class="fas fa-question-circle mr-1"></i>
                                    {{ $subCategory->questions_count ?? 0 }} soal
                                </span>
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('materials.sub-categories.detail', ['subCategory' => $subCategory->id]) }}"
                                    class="flex-1 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105"
                                    style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%); box-shadow: 0 4px 6px rgba(28,88,113,0.3);"
                                    onmouseover="this.style.background='linear-gradient(135deg, rgba(35,105,135,1) 0%, rgba(42,122,157,1) 100%)'"
                                    onmouseout="this.style.background='linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 100%)'"
                                    title="Lihat detail sub kategori {{ $subCategory->name }}">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>

                                @if (($subCategory->questions_count ?? 0) > 0)
                                    <a href="{{ route('materials.download.subcategory.excel', $subCategory->id) }}"
                                        class="flex-1 text-white text-center py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105"
                                        style="background: linear-gradient(135deg, rgba(42,122,157,1) 0%, rgba(49,139,179,1) 100%); box-shadow: 0 4px 6px rgba(42,122,157,0.3);"
                                        onmouseover="this.style.background='linear-gradient(135deg, rgba(49,139,179,1) 0%, rgba(56,156,201,1) 100%)'"
                                        onmouseout="this.style.background='linear-gradient(135deg, rgba(42,122,157,1) 0%, rgba(49,139,179,1) 100%)'"
                                        title="Download {{ $subCategory->questions_count }} soal dari sub kategori {{ $subCategory->name }}">
                                        <i class="fas fa-file-excel mr-1"></i>Download Soal
                                        ({{ $subCategory->questions_count }})
                                    </a>
                                @else
                                    <span
                                        class="flex-1 text-gray-500 text-center py-2 px-3 rounded-lg text-sm bg-gray-200"
                                        title="Belum ada soal untuk sub kategori ini">
                                        <i class="fas fa-file-excel mr-1"></i>Belum Ada Soal
                                    </span>
                                @endif

                                <button
                                    class="edit-subcategory-btn bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white py-2 px-3 rounded-lg text-sm transition-all duration-300 transform hover:scale-105"
                                    data-subcategory-id="{{ $subCategory->id }}"
                                    data-subcategory-name="{{ $subCategory->name }}"
                                    data-subcategory-description="{{ $subCategory->description ?? '' }}"
                                    title="Edit sub kategori {{ $subCategory->name }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form method="POST" action="{{ route('sub-categories.destroy', $subCategory->id) }}"
                                    onsubmit="event.preventDefault(); showDeleteConfirmation(this, 'Yakin ingin menghapus sub kategori {{ $subCategory->name }}? Semua materi dalam sub kategori ini akan dihapus.'); return false;"
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
                <div class="text-center py-8">
                    <div class="bg-gray-100 rounded-full p-6 inline-block mb-4">
                        <i class="fas fa-layer-group text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Sub Kategori</h3>
                    <p class="text-gray-500 mb-4">Tambahkan sub kategori untuk mengelompokkan materi dengan lebih
                        spesifik.</p>
                    <button onclick="showAddSubCategoryModal()"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>Tambah Sub Kategori Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Simple Toast functionality
        window.hideToast = function(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }
        }

        // Modal functions
        function showAddSubCategoryModal() {
            document.getElementById('addSubCategoryModal').classList.remove('hidden');
        }

        function closeAddSubCategoryModal() {
            document.getElementById('addSubCategoryModal').classList.add('hidden');
            document.getElementById('addSubCategoryForm').reset();
        }

        function closeEditSubCategoryModal() {
            document.getElementById('editSubCategoryModal').classList.add('hidden');
        }

        // Single DOMContentLoaded handler
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded fired');

            // The toast component will handle auto-hiding automatically
            // No need for manual auto-hide logic since we're using the standard component

            // Handle add subcategory form
            const addForm = document.getElementById('addSubCategoryForm');
            if (addForm) {
                addForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;

                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menambahkan...';

                    fetch('{{ route('materials.sub-categories.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;

                            if (data.success) {
                                closeAddSubCategoryModal();

                                // Hide any existing toasts
                                const existingToasts = document.querySelectorAll('[id$="Toast"]');
                                existingToasts.forEach(toast => {
                                    if (toast) {
                                        hideToast(toast.id);
                                    }
                                });

                                // Show success toast
                                const successToast = document.createElement('div');
                                successToast.id = 'addSuccessToast';
                                successToast.className =
                                    'fixed top-8 right-4 z-50 bg-green-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md';
                                successToast.style.display = 'block';
                                successToast.innerHTML = `
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-2xl mr-4"></i>
                                        <p class="text-base font-medium">${data.message || 'Sub kategori berhasil ditambahkan!'}</p>
                                        <button onclick="hideToast('addSuccessToast')" class="ml-4 text-white hover:text-gray-200">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                `;

                                document.body.appendChild(successToast);

                                // Auto-hide toast after 4 seconds
                                setTimeout(() => {
                                    hideToast('addSuccessToast');
                                }, 4000);

                                // Reload page after showing toast to reflect new subcategory
                                setTimeout(() => {
                                    if (data.redirect_url) {
                                        window.location.href = data.redirect_url;
                                    } else {
                                        location.reload();
                                    }
                                }, 2000);

                            } else {
                                // Show error message
                                const errorToast = document.createElement('div');
                                errorToast.id = 'addErrorToast';
                                errorToast.className =
                                    'fixed top-8 right-4 z-50 bg-red-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md';
                                errorToast.style.display = 'block';
                                errorToast.innerHTML = `
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-circle text-2xl mr-4"></i>
                                        <p class="text-base font-medium">${data.message || 'Terjadi kesalahan'}</p>
                                        <button onclick="hideToast('addErrorToast')" class="ml-4 text-white hover:text-gray-200">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                `;

                                document.body.appendChild(errorToast);

                                // Auto-hide toast after 5 seconds
                                setTimeout(() => {
                                    hideToast('addErrorToast');
                                }, 5000);
                            }
                        })
                        .catch(error => {
                            alert('Terjadi kesalahan: ' + error.message);
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        });
                });
            }

            // Handle edit buttons
            const editButtons = document.querySelectorAll('.edit-subcategory-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-subcategory-id');
                    const name = this.getAttribute('data-subcategory-name');
                    const description = this.getAttribute('data-subcategory-description') || '';

                    document.getElementById('editSubCategoryId').value = id;
                    document.getElementById('editSubCategoryName').value = name;
                    document.getElementById('editSubCategoryDescription').value = description;
                    document.getElementById('editSubCategoryModal').classList.remove('hidden');
                });
            });

            // Handle edit form
            const editForm = document.getElementById('editSubCategoryForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const id = document.getElementById('editSubCategoryId').value;
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'));
                    formData.append('_method', 'PUT');
                    formData.append('name', document.getElementById('editSubCategoryName').value);
                    formData.append('description', document.getElementById('editSubCategoryDescription')
                        .value);

                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;

                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memperbarui...';

                    fetch(`/sub-categories/${id}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;

                            if (data.success) {
                                closeEditSubCategoryModal();

                                // Hide any existing toasts
                                const existingToasts = document.querySelectorAll('[id$="Toast"]');
                                existingToasts.forEach(toast => {
                                    if (toast) {
                                        hideToast(toast.id);
                                    }
                                });

                                // Update subcategory data in place
                                const subCategoryCard = document.querySelector(
                                    `button[data-subcategory-id="${id}"]`).closest(
                                    '.bg-gradient-to-r');
                                if (subCategoryCard) {
                                    // Update subcategory name
                                    const nameElement = subCategoryCard.querySelector('h3');
                                    if (nameElement) {
                                        nameElement.textContent = data.subCategory?.name || document
                                            .getElementById('editSubCategoryName').value;
                                    }

                                    // Update subcategory description
                                    const descriptionElement = subCategoryCard.querySelector(
                                        '.text-sm.text-gray-600.mb-3');
                                    const newDescription = data.subCategory?.description || document
                                        .getElementById('editSubCategoryDescription').value;

                                    if (newDescription && newDescription.trim()) {
                                        if (descriptionElement) {
                                            descriptionElement.textContent = newDescription;
                                        } else {
                                            // Add description if it doesn't exist
                                            const nameDiv = subCategoryCard.querySelector(
                                                '.flex.items-center.justify-between.mb-3');
                                            if (nameDiv) {
                                                const descriptionP = document.createElement('p');
                                                descriptionP.className =
                                                    'text-sm text-gray-600 mb-3 line-clamp-2';
                                                descriptionP.textContent = newDescription;
                                                nameDiv.parentNode.insertBefore(descriptionP, nameDiv
                                                    .nextSibling);
                                            }
                                        }
                                    } else if (descriptionElement) {
                                        // Remove description if it's empty
                                        descriptionElement.remove();
                                    }

                                    // Update edit button data attributes
                                    const editButton = subCategoryCard.querySelector(
                                        '.edit-subcategory-btn');
                                    if (editButton) {
                                        editButton.setAttribute('data-subcategory-name', data
                                            .subCategory?.name || document.getElementById(
                                                'editSubCategoryName').value);
                                        editButton.setAttribute('data-subcategory-description', data
                                            .subCategory?.description || document.getElementById(
                                                'editSubCategoryDescription').value || '');
                                    }
                                }

                                // Show success toast
                                const successToast = document.createElement('div');
                                successToast.id = 'editSuccessToast';
                                successToast.className =
                                    'fixed top-8 right-4 z-50 bg-green-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md';
                                successToast.style.display = 'block';
                                successToast.innerHTML = `
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-2xl mr-4"></i>
                                        <p class="text-base font-medium">${data.message || 'Sub kategori berhasil diperbarui!'}</p>
                                        <button onclick="hideToast('editSuccessToast')" class="ml-4 text-white hover:text-gray-200">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                `;

                                document.body.appendChild(successToast);

                                // Auto-hide toast after 4 seconds
                                setTimeout(() => {
                                    hideToast('editSuccessToast');
                                }, 4000);

                            } else {
                                // Show error message
                                const errorToast = document.createElement('div');
                                errorToast.id = 'editErrorToast';
                                errorToast.className =
                                    'fixed top-8 right-4 z-50 bg-red-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md';
                                errorToast.style.display = 'block';
                                errorToast.innerHTML = `
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-circle text-2xl mr-4"></i>
                                        <p class="text-base font-medium">${data.message || 'Terjadi kesalahan'}</p>
                                        <button onclick="hideToast('editErrorToast')" class="ml-4 text-white hover:text-gray-200">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                `;

                                document.body.appendChild(errorToast);

                                // Auto-hide toast after 5 seconds
                                setTimeout(() => {
                                    hideToast('editErrorToast');
                                }, 5000);
                            }
                        })
                        .catch(error => {
                            alert('Terjadi kesalahan: ' + error.message);
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        });
                });
            }

            // Delete confirmation fallback
            setTimeout(function() {
                if (typeof window.showDeleteConfirmation !== 'function') {
                    window.showDeleteConfirmation = function(form, message) {
                        if (confirm(message)) {
                            form.onsubmit = null;
                            form.submit();
                        }
                    };
                }
            }, 100);
        });
    </script>

    <!-- Add Sub Category Modal -->
    <div id="addSubCategoryModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-layer-group text-2xl text-green-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Tambah Sub Kategori</h3>
                <p class="text-gray-600">Buat sub kategori baru untuk kategori "{{ $category }}"</p>
            </div>

            <form id="addSubCategoryForm" class="space-y-4">
                @csrf
                <input type="hidden" name="category_id" value="{{ $categoryModel->id ?? '' }}">

                <div>
                    <label for="subCategoryName" class="block text-sm font-semibold mb-2 text-gray-700">
                        <i class="fas fa-tag mr-1"></i>Nama Sub Kategori
                    </label>
                    <input type="text" id="subCategoryName" name="name" required
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:outline-none transition-colors"
                        placeholder="Contoh: AMDAL, UKL-UPL, dll..." maxlength="255">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 255 karakter</p>
                </div>

                <div>
                    <label for="subCategoryDescription" class="block text-sm font-semibold mb-2 text-gray-700">
                        <i class="fas fa-align-left mr-1"></i>Deskripsi (Opsional)
                    </label>
                    <textarea id="subCategoryDescription" name="description" rows="3"
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:outline-none transition-colors resize-none"
                        placeholder="Deskripsi singkat tentang sub kategori ini..."></textarea>
                </div>

                <div class="flex space-x-4 pt-4">
                    <button type="button" onclick="closeAddSubCategoryModal()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Sub Category Modal -->
    <div id="editSubCategoryModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-edit text-2xl text-amber-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Edit Sub Kategori</h3>
                <p class="text-gray-600">Perbarui informasi sub kategori</p>
            </div>

            <form id="editSubCategoryForm" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editSubCategoryId" name="editSubCategoryId">
                <div>
                    <label for="editSubCategoryName" class="block text-sm font-semibold mb-2 text-gray-700">
                        <i class="fas fa-tag mr-1"></i>Nama Sub Kategori
                    </label>
                    <input type="text" id="editSubCategoryName" name="subCategoryName" required
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-amber-500 focus:outline-none transition-colors"
                        placeholder="Contoh: Pelatihan Dasar, Pelatihan Lanjutan, dll..." maxlength="100">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 100 karakter</p>
                </div>

                <div>
                    <label for="editSubCategoryDescription" class="block text-sm font-semibold mb-2 text-gray-700">
                        <i class="fas fa-align-left mr-1"></i>Deskripsi (Opsional)
                    </label>
                    <textarea id="editSubCategoryDescription" name="subCategoryDescription" rows="3"
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-amber-500 focus:outline-none transition-colors resize-none"
                        placeholder="Deskripsi singkat tentang sub kategori ini..." maxlength="255"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Maksimal 255 karakter</p>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditSubCategoryModal()"
                        class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Sub Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Include Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')

    {{-- Footer --}}
    @include('components.footer')
</body>

</html>
