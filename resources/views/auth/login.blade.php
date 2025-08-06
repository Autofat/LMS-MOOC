<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - KemenLH/BPLH E-Learning Platform</title>
    
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
                    <i class="fas fa-graduation-cap text-5xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Masuk</h1>
                <p class="text-blue-100 text-sm">KemenLH/BPLH E-Learning Platform</p>
            </div>
            
            <!-- Body -->
            <div class="p-8">
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
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
                               required autofocus>
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-lock" style="color: rgba(28,88,113,1);"></i>
                            <span>Password</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan password Anda..."
                                   class="w-full px-4 py-3 pr-12 rounded-xl shadow-sm focus:outline-none focus:ring-2 transition-all border-2"
                                   style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(35,105,135,0.05) 100%);"
                                   required>
                            <button type="button" 
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 hover:text-gray-800 transition-colors"
                                    onclick="togglePasswordVisibility()">
                                <i id="passwordIcon" class="fas fa-eye" style="color: rgba(28,88,113,0.6);"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember" 
                               class="w-4 h-4 rounded border-2 focus:ring-2 focus:ring-blue-500"
                               style="border-color: rgba(28,88,113,0.3); accent-color: rgba(28,88,113,1);">
                        <label for="remember" class="ml-2 text-sm" style="color: rgba(28,88,113,0.7);">
                            Ingat saya
                        </label>
                    </div>
                    
                    <!-- Login Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 professional-gradient text-white rounded-xl hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-semibold flex items-center justify-center space-x-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </button>
                </form>
                
                <!-- Admin Access Only Notice -->
                <div class="mt-6 text-center">
                    <p class="text-xs" style="color: rgba(28,88,113,0.6);">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Akses khusus admin KemenLH/BPLH
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages - Toast Style -->
    <div id="toastContainer" class="fixed top-8 right-4 z-50 space-y-3">
        @if(session('success'))
            <div id="loginSuccessToast" class="bg-green-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-base font-medium">{{ session('success') }}</p>
                    </div>
                    <button onclick="hideLoginToast('loginSuccessToast')" class="ml-4 text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div id="loginErrorToast" class="bg-red-500 text-white px-8 py-5 rounded-lg shadow-xl max-w-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-2xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        @if(session('error'))
                            <p class="text-base font-medium">{{ session('error') }}</p>
                        @endif
                        @foreach($errors->all() as $error)
                            <p class="text-base font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                    <button onclick="hideLoginToast('loginErrorToast')" class="ml-4 text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Login page specific toast functionality - simplified approach like admin
        window.hideLoginToast = function(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }
        
        // Auto hide toasts after 5 seconds - only run once
        const loginSuccessToast = document.getElementById('loginSuccessToast');
        const loginErrorToast = document.getElementById('loginErrorToast');
        
        if (loginSuccessToast) {
            setTimeout(() => {
                hideLoginToast('loginSuccessToast');
            }, 5000);
        }
        
        if (loginErrorToast) {
            setTimeout(() => {
                hideLoginToast('loginErrorToast');
            }, 5000);
        }
    });

    // Toggle password visibility
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('passwordIcon');
        
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

