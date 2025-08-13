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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .professional-gradient {
            background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 50%, rgba(42,122,157,1) 100%);
        }
        .welcome-bg {
            background: linear-gradient(135deg, rgba(52, 121, 155, 0.1) 0%, rgba(28,88,113,0.05) 100%);
        }
        .hero-illustration {
            background: linear-gradient(135deg, #1c5871 0%, #236987 100%);
            border-radius: 50%;
            position: relative;
            overflow: hidden;
        }
        .floating-sparkle {
            position: absolute;
            animation: float 3s ease-in-out infinite;
        }
        .floating-sparkle:nth-child(1) { top: 20%; right: 30%; animation-delay: 0s; }
        .floating-sparkle:nth-child(2) { top: 15%; right: 15%; animation-delay: 1s; }
        .floating-sparkle:nth-child(3) { top: 25%; right: 20%; animation-delay: 2s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-10px) scale(1.1); }
        }
        
        .trophy-shine {
            animation: shine 2s ease-in-out infinite;
        }
        
        @keyframes shine {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .celebrate-animation {
            animation: celebrate 1.5s ease-in-out infinite;
        }
        
        @keyframes celebrate {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-5px) rotate(5deg); }
            75% { transform: translateY(-3px) rotate(-5deg); }
        }
        
        .dot-pattern {
            background-image: radial-gradient(circle, rgba(28,88,113,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>

<body class="welcome-bg min-h-screen flex items-center justify-center py-8 dot-pattern">
    <div class="max-w-6xl w-full mx-auto px-4">
        <!-- Mobile Welcome Header -->
        <div class="lg:hidden text-center mb-8">
            <div class="mb-6">
                <img src="{{ asset('images/kemenlh-logo.png') }}" 
                     alt="Logo KemenLH" 
                     class="h-12 mx-auto">
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                Selamat Datang di<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-teal-600">
                    E-Learning KemenLH
                </span>
            </h1>
            <p class="text-gray-600 text-sm">
                Mulailah perjalanan Anda di e-learning KemenLH
            </p>
        </div>
        
        <div class="grid lg:grid-cols-2 gap-12 items-center min-h-screen lg:min-h-[90vh]">
            
            <!-- Welcome Section - Left Side -->
            <div class="hidden lg:flex lg:flex-col lg:justify-center lg:items-center lg:p-6">
                <div class="text-center space-y-6">
                    <!-- Hero Illustration -->
                    <div class="relative mx-auto w-80 h-80 hero-illustration flex items-center justify-center mb-8">
                        <!-- Floating Sparkles -->
                        <div class="floating-sparkle">
                            <i class="fas fa-star text-white text-3xl"></i>
                        </div>
                        <div class="floating-sparkle">
                            <i class="fas fa-star text-white text-2xl"></i>
                        </div>
                        <div class="floating-sparkle">
                            <i class="fas fa-star text-white text-2xl"></i>
                        </div>
                        
                        <!-- Main Illustration Content -->
                        <div class="relative z-10 flex items-center justify-center">
                            <!-- Trophy -->
                            <div class="trophy-shine">
                                <i class="fas fa-trophy text-yellow-400 text-7xl mb-4"></i>
                            </div>
                            
                            <!-- Celebrating People -->
                            <div class="absolute -bottom-8 left-0 right-0 flex justify-center items-end space-x-4">
                                <div class="celebrate-animation">
                                    <i class="fas fa-user text-white text-3xl"></i>
                                </div>
                                <div class="celebrate-animation" style="animation-delay: 0.5s;">
                                    <i class="fas fa-user text-white text-3xl"></i>
                                </div>
                                <div class="celebrate-animation" style="animation-delay: 1s;">
                                    <i class="fas fa-user text-white text-3xl"></i>
                                </div>
                                <div class="celebrate-animation" style="animation-delay: 1.5s;">
                                    <i class="fas fa-user text-white text-3xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute top-10 left-10 w-6 h-6 bg-green-400 rounded-full opacity-80"></div>
                        <div class="absolute bottom-16 right-8 w-5 h-5 bg-yellow-300 rounded-full opacity-70"></div>
                        <div class="absolute top-1/2 left-4 w-4 h-4 bg-blue-300 rounded-full opacity-60"></div>
                    </div>
                    
                    <!-- Welcome Text -->
                    <div class="space-y-6">
                        <h1 class="text-4xl font-bold text-gray-800">
                            Selamat Datang di<br>
                            <span class="text-gray-800">
                                E-Learning KemenLH
                            </span>
                        </h1>
                        <p class="text-gray-600 text-lg leading-relaxed max-w-lg mx-auto">
                            Mulailah perjalanan Anda di e-learning KemenLH dan jadilah bagian dari komunitas yang peduli dan berkomitmen untuk masa depan yang lebih baik.
                        </p>
                        
                        <!-- Progress Dots -->
                        <div class="flex justify-center space-x-3 pt-6">
                            <div class="w-4 h-4 bg-gray-400 rounded-full"></div>
                            <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                            <div class="w-4 h-4 bg-yellow-400 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Login Form Section - Right Side -->
            <div class="w-full flex flex-col justify-center items-center lg:p-6">
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden w-full max-w-lg">
                    <!-- Header with Logo -->
                    <div class="professional-gradient px-10 py-10 text-center relative overflow-hidden">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-4 left-4 w-20 h-20 border-2 border-white rounded-full"></div>
                            <div class="absolute bottom-4 right-4 w-16 h-16 border border-white rounded-full"></div>
                            <div class="absolute top-1/2 right-8 w-8 h-8 border border-white rounded-full"></div>
                        </div>
                        
                        <div class="relative z-10">
                            <!-- Logo -->
                            <div class="mb-6">
                                <img src="{{ asset('images/kemenlh-logo.png') }}" 
                                     alt="Logo KemenLH" 
                                     class="h-16 mx-auto filter brightness-0 invert">
                            </div>
                            
                            <h2 class="text-3xl font-bold text-white mb-2">Masuk</h2>
                            <p class="text-blue-100 text-sm">KemenLH/BPLH E-Learning Platform</p>
                        </div>
                    </div>
                    
                    <!-- Body -->
                    <div class="px-10 py-8">
                        
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
                                       class="w-full px-4 py-4 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all border-2 hover:border-blue-300"
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
                                           class="w-full px-4 py-4 pr-12 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all border-2 hover:border-blue-300"
                                           style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(35,105,135,0.05) 100%);"
                                           required>
                                    <button type="button" 
                                            id="togglePassword"
                                            class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-600 hover:text-gray-800 transition-colors"
                                            onclick="togglePasswordVisibility()">
                                        <i id="passwordIcon" class="fas fa-eye" style="color: rgba(28,88,113,0.6);"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Remember Me -->
                            <div class="flex items-center">
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
                            </div>
                            
                            <!-- Login Button -->
                            <button type="submit" 
                                    class="w-full py-4 px-4 professional-gradient text-white rounded-xl hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-semibold flex items-center justify-center space-x-2 transform hover:scale-105">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Masuk ke Platform</span>
                            </button>
                        </form>
                        
                        <!-- Admin Access Notice -->
                        <div class="mt-6 text-center">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-shield-alt mr-1"></i>
                                Akses khusus admin KemenLH/BPLH
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages - Toast Style -->
    <!-- Toast Messages -->
    @if(session('success'))
        @include('components.toast', ['id' => 'loginSuccessToast', 'type' => 'success', 'message' => session('success')])
    @endif

    @if(session('error') || $errors->any())
        @php
            $errorMessage = session('error') ?: $errors->first();
        @endphp
        @include('components.toast', ['id' => 'loginErrorToast', 'type' => 'error', 'message' => $errorMessage])
    @endif

    <script>
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

