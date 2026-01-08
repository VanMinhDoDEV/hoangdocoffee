<!-- Order Detail Modal -->
<div id="orderDetailModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="z-index: 9999;">
  <!-- Background backdrop -->
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeOrderModal()"></div>

  <!-- Modal panel -->
  <div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl">
        <!-- Modal Header -->
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">{{ __('messages.order_details') }}</h3>
                <button type="button" onclick="closeOrderModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="bg-gray-50 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
          <div id="orderDetailContent" class="mt-2">
            <!-- AJAX Content will be loaded here -->
            <div class="flex justify-center py-10">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-gray-900"></div>
            </div>
          </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-white px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200">
          <button type="button" onclick="closeOrderModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">{{ __('messages.close') }}</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    function openOrderModal(orderId) {
        const modal = document.getElementById('orderDetailModal');
        const content = document.getElementById('orderDetailContent');
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent body scrolling

        // Show loading state
        content.innerHTML = `
            <div class="flex justify-center py-10">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-gray-900"></div>
            </div>
        `;

        // Fetch data
        fetch(`/admin/orders/ajax/${orderId}`)
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = '<p class="text-center text-red-500 py-4">Có lỗi xảy ra khi tải thông tin đơn hàng.</p>';
            });
    }

    function closeOrderModal() {
        const modal = document.getElementById('orderDetailModal');
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore body scrolling
    }

    // Close on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeOrderModal();
        }
    });
</script>
