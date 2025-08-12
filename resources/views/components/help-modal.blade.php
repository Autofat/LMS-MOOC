<!-- Help Modal Component -->
<div id="helpModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 text-white">
            <div class="flex items-center justify-between">
                <h3 id="helpModalTitle" class="text-xl font-bold flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Bantuan
                </h3>
                <button onclick="closeHelpModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div id="helpModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Tips: Gunakan tombol bantuan di setiap halaman untuk panduan spesifik
                </p>
                <div class="flex space-x-2">
                    <a href="{{ route('help.index') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        <i class="fas fa-book mr-1"></i>Panduan Lengkap
                    </a>
                    <button onclick="closeHelpModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openHelpModal() {
        document.getElementById('helpModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeHelpModal() {
        document.getElementById('helpModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('helpModal');
        if (event.target === modal) {
            closeHelpModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeHelpModal();
        }
    });
</script>
