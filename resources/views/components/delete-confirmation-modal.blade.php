<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center h-full w-full hidden z-50">
    <div class="relative mx-auto p-5 border w-96 max-w-md shadow-lg rounded-md bg-white transform transition-all">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.732 6.5c-.77.833-.192 2.5 1.268 2.5z"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Konfirmasi Hapus</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="deleteModalMessage">
                    Yakin ingin menghapus soal ini? Aksi ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <div class="flex space-x-3 justify-center">
                    <button id="confirmDelete" 
                        class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition-colors">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                    <button id="cancelDelete" 
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentForm = null;

    // Make the function globally accessible
    window.showDeleteConfirmation = function(form, message = 'Yakin ingin menghapus soal ini? Aksi ini tidak dapat dibatalkan.') {
        currentForm = form;
        document.getElementById('deleteModalMessage').textContent = message;
        document.getElementById('deleteModal').classList.remove('hidden');
        
        // Focus on cancel button for better UX
        document.getElementById('cancelDelete').focus();
    }

    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        currentForm = null;
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (currentForm) {
                // Remove the onsubmit handler to prevent double confirmation
                currentForm.onsubmit = null;
                currentForm.submit();
            }
            hideDeleteModal();
        });

        document.getElementById('cancelDelete').addEventListener('click', function() {
            hideDeleteModal();
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
                hideDeleteModal();
            }
        });
    });
</script>
