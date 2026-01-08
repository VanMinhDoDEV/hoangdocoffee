<div class="shop-toast-container" id="shopToastContainer"></div>

<style>
    .shop-toast-container {
      position: fixed;
      top: 90px;
      right: 20px;
      z-index: 99;
      display: flex;
      flex-direction: column;
      gap: 12px;
      max-width: 380px;
      width: 100%;
      pointer-events: none; /* Allow clicking through container */
    }

    .shop-toast {
      background: white;
      border-radius: 12px;
      padding: 16px 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      display: flex;
      align-items: center;
      gap: 12px;
      animation: slideIn 0.3s ease-out;
      border-left: 4px solid;
      position: relative;
      overflow: hidden;
      pointer-events: auto; /* Re-enable pointer events for toasts */
      opacity: 1; /* Ensure visibility */
    }

    @keyframes slideIn {
      from {
        transform: translateX(400px);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slideOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(400px);
        opacity: 0;
      }
    }

    .shop-toast.removing {
      animation: slideOut 0.3s ease-out forwards;
    }

    .shop-toast-icon {
      flex-shrink: 0;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }

    .shop-toast-content {
      flex: 1;
    }

    .shop-toast-title {
      font-size: 15px;
      font-weight: 600;
      margin-bottom: 4px;
    }

    .shop-toast-message {
      font-size: 14px;
      color: #6b7280;
    }

    .shop-toast-close {
      flex-shrink: 0;
      width: 24px;
      height: 24px;
      background: none;
      border: none;
      cursor: pointer;
      color: #9ca3af;
      font-size: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      transition: all 0.2s;
    }

    .shop-toast-close:hover {
      background: #f3f4f6;
      color: #374151;
    }

    .shop-toast-progress {
      position: absolute;
      bottom: 0;
      left: 0;
      height: 3px;
      background: currentColor;
      opacity: 0.3;
      animation: progress 3s linear;
    }

    @keyframes progress {
      from {
        width: 100%;
      }
      to {
        width: 0;
      }
    }

    .shop-toast.success {
      border-left-color: #10b981;
    }
    .shop-toast.success .shop-toast-title {
      color: #10b981;
    }
    .shop-toast.success .shop-toast-progress {
        color: #10b981;
    }

    .shop-toast.error {
      border-left-color: #ef4444;
    }
    .shop-toast.error .shop-toast-title {
      color: #ef4444;
    }
    .shop-toast.error .shop-toast-progress {
        color: #ef4444;
    }

    .shop-toast.warning {
      border-left-color: #f59e0b;
    }
    .shop-toast.warning .shop-toast-title {
      color: #f59e0b;
    }
    .shop-toast.warning .shop-toast-progress {
        color: #f59e0b;
    }

    .shop-toast.info {
      border-left-color: #3b82f6;
    }
    .shop-toast.info .shop-toast-title {
      color: #3b82f6;
    }
    .shop-toast.info .shop-toast-progress {
        color: #3b82f6;
    }
</style>

<script>
    let toastIdCounter = 0;

    window.showToast = function(type, title, message) {
      const container = document.getElementById('shopToastContainer');
      if (!container) return;
      
      const toast = document.createElement('div');
      const toastId = `toast-${toastIdCounter++}`;
      toast.id = toastId;
      toast.className = `shop-toast ${type}`;
      
      const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ'
      };

      const defaultTitles = {
        success: 'Thành công',
        error: 'Lỗi',
        warning: 'Cảnh báo',
        info: 'Thông tin'
      };

      toast.innerHTML = `
        <div class="shop-toast-icon">${icons[type] || 'ℹ'}</div>
        <div class="shop-toast-content">
          <div class="shop-toast-title">${title || defaultTitles[type]}</div>
          <div class="shop-toast-message">${message}</div>
        </div>
        <button class="shop-toast-close" aria-label="Đóng thông báo">×</button>
        <div class="shop-toast-progress"></div>
      `;

      const closeBtn = toast.querySelector('.shop-toast-close');
      closeBtn.onclick = () => removeToast(toast);

      container.appendChild(toast);

      // Auto remove after 3 seconds
      setTimeout(() => {
        removeToast(toast);
      }, 3000);
    };

    function removeToast(toast) {
      if (!toast.classList.contains('removing')) {
        toast.classList.add('removing');
        toast.addEventListener('animationend', () => {
          if (toast.parentElement) {
            toast.parentElement.removeChild(toast);
          }
        });
      }
    }
</script>
