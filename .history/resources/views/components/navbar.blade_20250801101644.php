<!-- Navigation Bar -->
<nav class="bg-white shadow-lg border-b border-gray-200 mb-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo/Brand -->
            <div class="flex items-center space-x-3">
                <div class="bg-white p-2 rounded-lg shadow-md border border-gray-100">
                    <img src="{{ asset('images/ai-generated-logo.png') }}" alt="Pembuatan Soal AI Generated Logo" class="w-10 h-10 sm:w-12 sm:h-12 object-contain">
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-gray-800">Pembuatan Soal AI Generated</h1>
                    <p class="text-xs sm:text-sm text-gray-600">KemenLH/BPLH E-Learning Platform</p>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('materials.index') }}" 
                   class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors {{ request()->routeIs('materials.*') ? 'text-blue-600 font-semibold' : '' }}">
                    <i class="fas fa-folder"></i>
                    <span>Materi</span>
                </a>
                <a href="{{ route('questions.manage') }}" 
                   class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors {{ request()->routeIs('questions.*') ? 'text-blue-600 font-semibold' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>Soal</span>
                </a>
            </div>
            
            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <!-- Welcome Message -->
                <div class="hidden lg:block text-right">
                    <p class="text-sm text-gray-600">Selamat datang,</p>
                    <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                </div>
                
                <!-- User Avatar -->
                <div class="relative">
                    <button id="userMenuButton" class="flex items-center space-x-2 bg-gray-100 hover:bg-gray-200 rounded-full px-3 py-2 transition-colors">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <i class="fas fa-chevron-down text-gray-600 text-xs"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <div class="py-2">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('materials.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-folder mr-2"></i>Dashboard
                            </a>
                            <a href="{{ route('test.n8n.connection') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-plug mr-2"></i>Test n8n
                            </a>
                            <div class="border-t border-gray-100 mt-2 pt-2">
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobileMenuButton" class="text-gray-700 hover:text-blue-600 transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200 py-4">
            <div class="space-y-2">
                <a href="{{ route('materials.index') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition-colors {{ request()->routeIs('materials.*') ? 'text-blue-600 font-semibold' : '' }}">
                    <i class="fas fa-folder mr-2"></i>Materi
                </a>
                <a href="{{ route('questions.manage') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition-colors {{ request()->routeIs('questions.*') ? 'text-blue-600 font-semibold' : '' }}">
                    <i class="fas fa-question-circle mr-2"></i>Soal
                </a>
                <a href="{{ route('test.n8n.connection') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition-colors">
                    <i class="fas fa-plug mr-2"></i>Test n8n
                </a>
                <div class="border-t border-gray-200 pt-2 mt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-red-600 hover:text-red-800 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="container mx-auto px-4 mb-6">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="container mx-auto px-4 mb-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="container mx-auto px-4 mb-6">
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                {{ session('info') }}
            </div>
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User menu dropdown
    const userMenuButton = document.getElementById('userMenuButton');
    const userDropdown = document.getElementById('userDropdown');
    
    userMenuButton.addEventListener('click', function() {
        userDropdown.classList.toggle('hidden');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
            userDropdown.classList.add('hidden');
        }
    });
    
    // Mobile menu
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    
    mobileMenuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });
});
</script>
