<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola User - KemenLH/BPLH E-Learning Platform</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .professional-gradient {
            background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 50%, rgba(42,122,157,1) 100%);
        }
        .subtle-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='rgba(28,88,113,0.03)' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen subtle-pattern">
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8">
        <!-- Back Button - Outside Box -->
        <div class="mb-6">
            <a href="{{ route('materials.index') }}" 
               class="inline-flex items-center space-x-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 shadow-md">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <!-- Header -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl border p-8 mb-8" style="border-color: rgba(28,88,113,0.1);">
            <div class="text-center mb-8">
                <div class="professional-gradient rounded-full p-6 inline-block mb-4">
                    <i class="fas fa-users-cog text-4xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold mb-2" style="color: rgba(28,88,113,1);">Kelola User</h1>
                <p class="text-gray-600">Daftar semua user yang memiliki akses ke Platform E-Learning KemenLH/BPLH</p>
            </div>

            <!-- Actions -->
            @if(Auth::user()->email === 'superadmin@elearning.kemenlh.go.id')
                <div class="flex justify-center mb-8">
                    <a href="{{ route('admin.create') }}" 
                       class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center justify-center space-x-2">
                        <i class="fas fa-user-plus"></i>
                        <span>Tambah User Baru</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Toast Messages -->
        @if(session('success'))
            @include('components.toast', ['id' => 'manageSuccessToast', 'type' => 'success', 'message' => session('success')])
        @endif

        @if(session('error'))
            @include('components.toast', ['id' => 'manageErrorToast', 'type' => 'error', 'message' => session('error')])
        @endif

        <!-- User List -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl border p-8" style="border-color: rgba(28,88,113,0.1);">
            <!-- Informasi User -->
            <div class="mt-8 p-4 rounded-xl" style="background: rgba(28,88,113,0.05); border: 1px solid rgba(28,88,113,0.1);">
                <div class="text-sm" style="color: rgba(28,88,113,0.8);">
                    <h4 class="font-semibold mb-2"><i class="fas fa-info-circle mr-2"></i>Informasi User</h4>
                    <ul class="space-y-1 ml-6">
                        <li>• Setiap user memiliki akses penuh ke sistem</li>
                        <li>• User dapat mengelola materi dan soal</li>
                        <li>• User tidak dapat menghapus akun mereka sendiri</li>
                    </ul>
                </div>
            </div>

            <h2 class="text-2xl font-bold mb-6 mt-8" style="color: rgba(28,88,113,1);">
                <i class="fas fa-list mr-2"></i>Daftar User ({{ $admins->count() }})
            </h2>

            @if($admins->count() > 0)
                <div class="grid gap-6">
                    @foreach($admins as $admin)
                        <div class="border rounded-2xl p-6 hover:shadow-lg transition-all duration-300" style="border-color: rgba(28,88,113,0.1); background: linear-gradient(135deg, rgba(28,88,113,0.02) 0%, rgba(35,105,135,0.02) 100%);">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="professional-gradient rounded-full p-3">
                                        <i class="fas fa-user-shield text-xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            {{ $admin->name }}
                                            @if($admin->id === Auth::id())
                                                <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full ml-2">Anda</span>
                                            @endif
                                        </h3>
                                        <p class="text-gray-600">
                                            <i class="fas fa-envelope mr-1"></i>{{ $admin->email }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-calendar mr-1"></i>Bergabung: {{ $admin->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    @if($admin->email === 'superadmin@elearning.kemenlh.go.id')
                                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                                            <i class="fas fa-crown mr-1"></i>Super Admin
                                        </span>
                                    @elseif($admin->is_admin)
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                            <i class="fas fa-shield-alt mr-1"></i>Admin
                                        </span>
                                    @else
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                            <i class="fas fa-user mr-1"></i>User
                                        </span>
                                    @endif
                                    
                                    @if(Auth::user()->email === 'superadmin@elearning.kemenlh.go.id' && $admin->email !== Auth::user()->email)
                                        <form method="POST" action="{{ route('admin.delete', $admin->id) }}" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full text-sm transition-all duration-300 delete-btn"
                                                    data-user-name="{{ $admin->name }}"
                                                    data-user-email="{{ $admin->email }}">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada User</h3>
                    <p class="text-gray-500 mb-6">Sistem belum memiliki user yang terdaftar.</p>
                    @if(Auth::user()->email === 'superadmin@elearning.kemenlh.go.id')
                        <a href="{{ route('admin.create') }}" 
                           class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-full transition-all duration-300">
                            <i class="fas fa-user-plus mr-2"></i>Tambah User Pertama
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        
        <!-- Modal Content -->
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all" style="border: 1px solid rgba(28,88,113,0.1);">
                <!-- Header -->
                <div class="p-6 border-b" style="border-color: rgba(28,88,113,0.1);">
                    <div class="flex items-center space-x-3">
                        <div class="bg-red-100 rounded-full p-3">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Penghapusan</h3>
                            <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>
                </div>
                
                <!-- Body -->
                <div class="p-6">
                    <p class="text-gray-700 mb-4">
                        Apakah Anda yakin ingin menghapus user berikut?
                    </p>
                    
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg professional-gradient">
                                    <span id="deleteUserInitial"></span>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-times text-white text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900" id="deleteUserName"></p>
                                <p class="text-sm text-gray-600" id="deleteUserEmail"></p>
                                <p class="text-xs text-red-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i>Akan dihapus secara permanen
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-red-50 border border-red-200 rounded-xl p-3">
                        <p class="text-red-700 text-sm">
                            <i class="fas fa-warning mr-2"></i>
                            User akan kehilangan akses ke sistem dan semua data terkait akan dihapus secara permanen.
                        </p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="p-6 border-t flex space-x-3 justify-end" style="border-color: rgba(28,88,113,0.1);">
                    <button type="button" 
                            id="cancelDelete"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all duration-300 font-medium">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="button" 
                            id="confirmDelete"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-300 font-medium">
                        <i class="fas fa-trash mr-2"></i>Hapus User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let currentDeleteForm = null;

    // Delete modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const cancelButton = document.getElementById('cancelDelete');
        const confirmButton = document.getElementById('confirmDelete');
        const userNameElement = document.getElementById('deleteUserName');
        const userEmailElement = document.getElementById('deleteUserEmail');

        // Show modal when delete button is clicked
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userName = this.getAttribute('data-user-name');
                const userEmail = this.getAttribute('data-user-email');
                const userInitial = userName.charAt(0).toUpperCase();
                currentDeleteForm = this.closest('.delete-form');
                
                userNameElement.textContent = userName;
                userEmailElement.textContent = userEmail;
                document.getElementById('deleteUserInitial').textContent = userInitial;
                
                deleteModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        // Hide modal when cancel is clicked
        if (cancelButton) {
            cancelButton.addEventListener('click', function() {
                hideModal();
            });
        }

        // Hide modal when clicking outside
        if (deleteModal) {
            deleteModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    hideModal();
                }
            });
        }

        // Confirm delete
        if (confirmButton) {
            confirmButton.addEventListener('click', function() {
                if (currentDeleteForm) {
                    currentDeleteForm.submit();
                }
            });
        }

        function hideModal() {
            if (deleteModal) {
                deleteModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                currentDeleteForm = null;
            }
        }

        // Handle ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && deleteModal && !deleteModal.classList.contains('hidden')) {
                hideModal();
            }
        });
    });
    </script>

    <!-- Include Help Modal Component -->
    @include('components.help-modal')

    <!-- Include Tutorial Component -->
    @include('components.tutorial')
</body>
</html>
