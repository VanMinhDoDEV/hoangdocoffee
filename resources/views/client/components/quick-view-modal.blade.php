<div id="quick-view-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-0 bg-opacity-75 transition-opacity backdrop-filter backdrop-blur-sm" aria-hidden="true" id="quick-view-backdrop"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <!-- Modal panel -->
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl">
            
            <!-- Close button -->
            <div class="absolute right-4 top-4 z-10">
                <button type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none" id="quick-view-close">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div id="quick-view-body" class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 min-h-[400px]">
                <!-- Loading state -->
                <div class="flex items-center justify-center h-full w-full absolute inset-0 bg-white z-0" id="quick-view-loading">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
                </div>
                <!-- Dynamic content will be injected here -->
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('quick-view-modal');
        const backdrop = document.getElementById('quick-view-backdrop');
        const closeBtn = document.getElementById('quick-view-close');

        function closeQuickView() {
            modal.classList.add('hidden');
            document.getElementById('quick-view-body').innerHTML = `
                <div class="flex items-center justify-center h-full w-full absolute inset-0 bg-white z-0" id="quick-view-loading">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
                </div>`;
            document.body.style.overflow = '';
        }

        backdrop.addEventListener('click', closeQuickView);
        closeBtn.addEventListener('click', closeQuickView);
        
        // Expose close function globally if needed
        window.closeQuickView = closeQuickView;
    });
</script>
