<!-- Navigation Bar -->
<nav class="shadow-xl border-b mb-8"
    style="background: linear-gradient(135deg, rgba(28,88,113,1) 0%, rgba(35,105,135,1) 50%, rgba(42,122,157,1) 100%); border-color: rgba(28,88,113,0.6);">
    <div class="container mx-auto px-4">
        <div class="flex items-center py-4">
            <!-- Logo/Brand - Far Left -->
            <div class="flex items-center space-x-3 mr-8">
                <div class="bg-white rounded-full p-2 shadow-lg">
                    <img src="{{ asset('images/kemenlh-logo.png') }}" alt="KemenLH/BPLH Logo"
                        class="w-8 h-8 object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-bold tracking-wide text-white">KemenLH / BPLH</span>
                    <span class="text-2xl text-white font-bold tracking-wide">E-Learning</span>
                </div>
            </div>

            <!-- Navigation Links - Center -->
            <div class="hidden md:flex items-center space-x-6 flex-1 justify-center">
                <a href="{{ route('materials.index') }}"
                    class="flex items-center space-x-2 text-blue-100 hover:text-white transition-colors {{ request()->routeIs('materials.*') ? 'text-white font-semibold bg-white/20 px-3 py-2 rounded-lg backdrop-blur-sm' : '' }}">
                    <i class="fas fa-folder-open"></i>
                    <span>Materi</span>
                </a>
                <a href="{{ route('questions.manage') }}"
                    class="flex items-center space-x-2 text-blue-100 hover:text-white transition-colors {{ request()->routeIs('questions.*') ? 'text-white font-semibold bg-white/20 px-3 py-2 rounded-lg backdrop-blur-sm' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>Soal</span>
                </a>
                @if(Auth::user()->is_admin)
                <a href="{{ route('admin.manage') }}"
                    class="flex items-center space-x-2 text-blue-100 hover:text-white transition-colors {{ request()->routeIs('admin.*') ? 'text-white font-semibold bg-white/20 px-3 py-2 rounded-lg backdrop-blur-sm' : '' }}">
                    <i class="fas fa-users-cog"></i>
                    <span>User</span>
                </a>
                @endif
            </div>

            <!-- User Menu - Far Right -->
            <div class="flex items-center space-x-4">
                <!-- Welcome Message -->
                <div class="hidden lg:block text-right">
                    <p class="text-sm text-blue-100">Selamat datang,</p>
                    <p class="font-semibold text-white">{{ Auth::user()->name }}</p>
                </div>

                <!-- User Avatar -->
                <div class="relative">
                    <button id="userMenuButton"
                        class="flex items-center space-x-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-full px-3 py-2 transition-colors border border-white/30">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm"
                            style="background: linear-gradient(135deg, rgba(28,88,113,0.8) 0%, rgba(42,122,157,0.8) 100%);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <i class="fas fa-chevron-down text-blue-100 text-xs"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="userDropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50 backdrop-blur-sm"
                        style="border: 1px solid rgba(28,88,113,0.2);">
                        <div class="py-2">
                            <div class="px-4 py-2 border-b" style="border-color: rgba(28,88,113,0.1);">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('materials.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-home mr-2" style="color: rgba(28,88,113,1);"></i>Dashboard
                            </a>
                            <div class="border-t mt-2 pt-2" style="border-color: rgba(28,88,113,0.1);">
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden ml-auto">
                <button id="mobileMenuButton" class="text-teal-100 hover:text-white transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-teal-500 py-4">
            <div class="space-y-2">
                <a href="{{ route('materials.index') }}"
                    class="block py-2 text-teal-100 hover:text-white transition-colors {{ request()->routeIs('materials.*') ? 'text-white font-semibold' : '' }}">
                    <i class="fas fa-folder-open mr-2"></i>Materi
                </a>
                <a href="{{ route('questions.manage') }}"
                    class="block py-2 text-teal-100 hover:text-white transition-colors {{ request()->routeIs('questions.*') ? 'text-white font-semibold' : '' }}">
                    <i class="fas fa-question-circle mr-2"></i>Soal
                </a>
                @if(Auth::user()->is_admin)
                <a href="{{ route('admin.manage') }}"
                    class="block py-2 text-teal-100 hover:text-white transition-colors {{ request()->routeIs('admin.*') ? 'text-white font-semibold' : '' }}">
                    <i class="fas fa-users-cog mr-2"></i>User
                </a>
                @endif
                <div class="border-t border-teal-500 pt-2 mt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left py-2 text-red-200 hover:text-red-100 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User menu dropdown
        const userMenuButton = document.getElementById('userMenuButton');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', function() {
                userDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }

        // Mobile menu
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>
