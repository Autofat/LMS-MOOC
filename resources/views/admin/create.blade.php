<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah User - KemenLH/BPLH E-Learning Platform</title>
    
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
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl border p-8 mb-8" style="border-color: rgba(28,88,113,0.1);">
                <div class="text-center mb-8">
                    <div class="professional-gradient rounded-full p-6 inline-block mb-4">
                        <i class="fas fa-user-plus text-4xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold mb-2" style="color: rgba(28,88,113,1);">Tambah User Baru</h1>
                    <p class="text-gray-600">Buat akun user baru untuk mengakses Platform E-Learning KemenLH/BPLH</p>
                </div>

                <!-- Success/Error Messages -->
                @if($errors->any())
                    <div class="bg-gradient-to-r from-red-100 to-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-xl mb-6">
                        <div class="flex items-center space-x-3 mb-2">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                            <strong>Terdapat kesalahan:</strong>
                        </div>
                        <ul class="list-disc list-inside ml-6">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- User Info -->
                <div class="mb-8 p-4 rounded-xl" style="background: rgba(28,88,113,0.05); border: 1px solid rgba(28,88,113,0.1);">
                    <div class="text-sm" style="color: rgba(28,88,113,0.8);">
                        <h4 class="font-semibold mb-2"><i class="fas fa-info-circle mr-2"></i>Informasi User</h4>
                        <ul class="space-y-1 ml-6">
                            <li>• User yang dibuat akan memiliki akses penuh ke sistem</li>
                            <li>• User dapat mengelola materi, soal, dan user lainnya</li>
                            <li>• Password minimal 8 karakter untuk keamanan</li>
                        </ul>
                    </div>
                </div>

                <!-- Create Admin Form -->
                <form method="POST" action="{{ route('admin.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-user" style="color: rgba(28,88,113,1);"></i>
                            <span>Nama Lengkap</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="Masukkan nama lengkap user..."
                               class="w-full px-4 py-3 rounded-xl shadow-sm focus:outline-none focus:ring-2 transition-all border-2"
                               style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(35,105,135,0.05) 100%);"
                               required autofocus>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-envelope" style="color: rgba(28,88,113,1);"></i>
                            <span>Email Address</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="Masukkan email user..."
                               class="w-full px-4 py-3 rounded-xl shadow-sm focus:outline-none focus:ring-2 transition-all border-2"
                               style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(35,105,135,0.05) 100%);"
                               required>
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-lock" style="color: rgba(28,88,113,1);"></i>
                            <span>Password (minimal 8 karakter)</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan password..."
                                   class="w-full px-4 py-3 pr-12 rounded-xl shadow-sm focus:outline-none focus:ring-2 transition-all border-2"
                                   style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(35,105,135,0.05) 100%);"
                                   required minlength="8">
                            <button type="button" 
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 hover:text-gray-800 transition-colors"
                                    onclick="togglePasswordVisibility('password', 'passwordIcon')">
                                <i id="passwordIcon" class="fas fa-eye" style="color: rgba(28,88,113,0.6);"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-lock" style="color: rgba(28,88,113,1);"></i>
                            <span>Konfirmasi Password</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Masukkan ulang password..."
                                   class="w-full px-4 py-3 pr-12 rounded-xl shadow-sm focus:outline-none focus:ring-2 transition-all border-2"
                                   style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(35,105,135,0.05) 100%);"
                                   required minlength="8">
                            <button type="button" 
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 hover:text-gray-800 transition-colors"
                                    onclick="togglePasswordVisibility('password_confirmation', 'passwordConfirmIcon')">
                                <i id="passwordConfirmIcon" class="fas fa-eye" style="color: rgba(28,88,113,0.6);"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 flex space-x-4">
                        <a href="{{ route('admin.manage') }}" 
                           class="flex-1 py-3 px-6 bg-gray-500 hover:bg-gray-600 text-white rounded-xl hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all duration-300 font-semibold flex items-center justify-center space-x-2">
                            <i class="fas fa-times"></i>
                            <span>Batal</span>
                        </a>
                        <button type="submit" 
                                class="flex-1 py-3 px-6 professional-gradient text-white rounded-xl hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-semibold flex items-center justify-center space-x-2">
                            <i class="fas fa-user-plus"></i>
                            <span>Buat User</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Toggle password visibility
    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const passwordIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    }
    </script>
</body>
</html>
