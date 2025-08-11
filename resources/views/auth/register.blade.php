<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - KemenLH/BPLH E-Learning Platform</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .professional-gradient {
            background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 50%, rgba(42,122,157,1) 100%);
        }
    </style>
</head>

<body class="bg-white min-h-screen flex items-center justify-center py-8">
    <div class="max-w-md w-full mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl border" style="border-color: rgba(28,88,113,0.1);">
            <!-- Header -->
            <div class="professional-gradient p-8 rounded-t-2xl text-center">
                <div class="mb-4">
                    <i class="fas fa-user-plus text-5xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Daftar</h1>
                <p class="text-blue-100 text-sm">KemenLH/BPLH E-Learning Platform</p>
            </div>
            
            <!-- Body -->
            <div class="p-8">
                <!-- Error Messages -->
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
                
                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
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
                               placeholder="Masukkan nama lengkap Anda..."
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
                               placeholder="Masukkan email Anda..."
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
                                   placeholder="Masukkan password Anda..."
                                   class="w-full px-4 py-3 pr-12 rounded-xl shadow-sm focus:outline-none focus:ring-2 transition-all border-2"
                                   style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(35,105,135,0.05) 100%);"
                                   required minlength="8">
                            <button type="button" 
                                    id="togglePassword"
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
                                   placeholder="Masukkan ulang password Anda..."
                                   class="w-full px-4 py-3 pr-12 rounded-xl shadow-sm focus:outline-none focus:ring-2 transition-all border-2"
                                   style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(35,105,135,0.05) 100%);"
                                   required minlength="8">
                            <button type="button" 
                                    id="togglePasswordConfirm"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 hover:text-gray-800 transition-colors"
                                    onclick="togglePasswordVisibility('password_confirmation', 'passwordConfirmIcon')">
                                <i id="passwordConfirmIcon" class="fas fa-eye" style="color: rgba(28,88,113,0.6);"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Register Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 professional-gradient text-white rounded-xl hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-semibold flex items-center justify-center space-x-2">
                        <i class="fas fa-user-plus"></i>
                        <span>Daftar</span>
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="my-6 flex items-center">
                    <div class="flex-1 border-t" style="border-color: rgba(28,88,113,0.2);"></div>
                    <span class="px-4 text-sm" style="color: rgba(28,88,113,0.6);">atau</span>
                    <div class="flex-1 border-t" style="border-color: rgba(28,88,113,0.2);"></div>
                </div>
                
                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm" style="color: rgba(28,88,113,0.7);">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" 
                           class="font-semibold hover:underline transition-all duration-300"
                           style="color: rgba(28,88,113,1);">
                            Masuk sekarang
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Messages -->
    @if(session('success'))
        @include('components.toast', ['id' => 'successToast', 'type' => 'success', 'message' => session('success')])
    @endif

    @if(session('error'))
        @include('components.toast', ['id' => 'errorToast', 'type' => 'error', 'message' => session('error')])
    @endif

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

