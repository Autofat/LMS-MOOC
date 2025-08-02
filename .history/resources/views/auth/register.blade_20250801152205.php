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
        .subtle-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='rgba(28,88,113,0.03)' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen subtle-pattern flex items-center justify-center py-8">
    <div class="max-w-md w-full mx-auto px-4">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border" style="border-color: rgba(28,88,113,0.1);">
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
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold mb-3 flex items-center space-x-2" style="color: rgba(28,88,113,0.9);">
                            <i class="fas fa-lock" style="color: rgba(28,88,113,1);"></i>
                            <span>Konfirmasi Password</span>
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Masukkan ulang password Anda..."
                               class="w-full px-4 py-3 rounded-xl shadow-sm focus:outline-none focus:ring-2 transition-all border-2"
                               style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(28,88,113,0.05) 0%, rgba(35,105,135,0.05) 100%);"
                               required>
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
</body>
</html>
            font-size: 28px;
        }
        
        .register-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .register-body {
            padding: 40px 30px;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-floating > .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 15px;
            height: calc(3.5rem + 2px);
            transition: all 0.3s ease;
        }
        
        .form-floating > .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-floating > label {
            padding: 1rem 15px;
            color: #6c757d;
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }
        
        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 15px;
            color: #6c757d;
            font-size: 14px;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            color: #764ba2;
        }
        
        .alert {
            border: none;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .alert-success {
            background-color: #d1edff;
            color: #0c5460;
        }
        
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        .password-strength {
            font-size: 12px;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #198754; }
        
        .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="register-container">
        <div class="card register-card">
            <!-- Header -->
            <div class="register-header">
                <h2><i class="fas fa-user-plus me-2"></i>Daftar Akun</h2>
                <p>Pembuatan Soal Generated - KemenLH/BPLH</p>
            </div>
            
            <!-- Body -->
            <div class="register-body">
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif
                
                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    
                    <!-- Name -->
                    <div class="form-floating">
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name" 
                               placeholder="Nama Lengkap"
                               value="{{ old('name') }}"
                               required>
                        <label for="name">
                            <i class="fas fa-user me-2"></i>Nama Lengkap
                        </label>
                    </div>
                    
                    <!-- Email -->
                    <div class="form-floating">
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               placeholder="name@example.com"
                               value="{{ old('email') }}"
                               required>
                        <label for="email">
                            <i class="fas fa-envelope me-2"></i>Email Address
                        </label>
                    </div>
                    
                    <!-- Password -->
                    <div class="form-floating">
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="Password"
                               required>
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                    
                    <!-- Confirm Password -->
                    <div class="form-floating">
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Confirm Password"
                               required>
                        <label for="password_confirmation">
                            <i class="fas fa-lock me-2"></i>Konfirmasi Password
                        </label>
                    </div>
                    
                    <!-- Register Button -->
                    <button type="submit" class="btn btn-primary btn-register">
                        <span class="spinner"></span>
                        <i class="fas fa-user-plus me-2"></i>
                        Daftar Sekarang
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="divider">
                    <span>atau</span>
                </div>
                
                <!-- Login Link -->
                <div class="login-link">
                    <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Loading animation on form submit
        document.getElementById('registerForm').addEventListener('submit', function() {
            const btn = document.querySelector('.btn-register');
            btn.classList.add('loading');
            btn.innerHTML = '<span class="spinner"></span>Mendaftar...';
        });
        
        // Auto focus on first input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('name').focus();
        });
        
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            let strength = 0;
            let feedback = '';
            
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            switch (strength) {
                case 0:
                case 1:
                    feedback = '<span class="strength-weak">Password terlalu lemah</span>';
                    break;
                case 2:
                case 3:
                    feedback = '<span class="strength-medium">Password sedang</span>';
                    break;
                case 4:
                case 5:
                    feedback = '<span class="strength-strong">Password kuat</span>';
                    break;
            }
            
            strengthDiv.innerHTML = feedback;
        });
        
        // Password confirmation check
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            
            if (confirmation && password !== confirmation) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
