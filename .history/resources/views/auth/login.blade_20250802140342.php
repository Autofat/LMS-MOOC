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
                <!-- Success Message -->
                @if(session('success'))
                    <div class="px-4 py-3 rounded-xl mb-6 flex items-center space-x-3" style="background: linear-gradient(135deg, rgba(34,197,94,0.1) 0%, rgba(16,185,129,0.08) 100%); border: 1px solid rgba(34,197,94,0.3); color: rgba(5,150,105,0.9);">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                
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
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan password Anda..."
                               class="w-full px-4 py-3 rounded-xl shadow-sm focus:outline-none focus:ring-2 transition-all border-2"
                               style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(35,105,135,0.05) 100%);"
                               required>
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
                
                <!-- Divider -->
                <div class="my-6 flex items-center">
                    <div class="flex-1 border-t" style="border-color: rgba(28,88,113,0.2);"></div>
                    <span class="px-4 text-sm" style="color: rgba(28,88,113,0.6);">atau</span>
                    <div class="flex-1 border-t" style="border-color: rgba(28,88,113,0.2);"></div>
                </div>
                
                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-sm" style="color: rgba(28,88,113,0.7);">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" 
                           class="font-semibold hover:underline transition-all duration-300"
                           style="color: rgba(28,88,113,1);">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

