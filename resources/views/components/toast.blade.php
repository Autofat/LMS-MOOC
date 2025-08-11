@props(['id', 'type' => 'success', 'message'])

@php
    $typeClasses = [
        'success' => 'bg-green-500',
        'error' => 'bg-red-500',
        'warning' => 'bg-yellow-500',
        'info' => 'bg-blue-500'
    ];
    
    $icons = [
        'success' => 'fas fa-check-circle',
        'error' => 'fas fa-exclamation-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'info' => 'fas fa-info-circle'
    ];
@endphp

<div id="{{ $id }}" class="fixed top-8 right-4 z-50 {{ $typeClasses[$type] }} text-white px-8 py-5 rounded-lg shadow-xl max-w-md transform translate-x-full opacity-0 transition-all duration-300">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <i class="{{ $icons[$type] }} text-2xl"></i>
        </div>
        <div class="ml-4 flex-1">
            <p class="text-base font-medium">{{ $message }}</p>
        </div>
        <button onclick="hideToast('{{ $id }}')" class="ml-4 text-white hover:text-gray-200 transition-colors">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
</div>

<script>
    // Global toast functionality
    if (typeof window.hideToast === 'undefined') {
        window.hideToast = function(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.transform = 'translateX(100%)';
                toast.style.opacity = '0';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        window.showToast = function(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');
                }, 100);
                
                // Auto hide after 5 seconds
                setTimeout(() => {
                    hideToast(toastId);
                }, 5000);
            }
        }

        // Auto-show and hide toasts on page load
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('[id$="Toast"]');
            toasts.forEach(toast => {
                showToast(toast.id);
            });
        });
    }
</script>
