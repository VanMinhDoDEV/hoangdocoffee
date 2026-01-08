<!-- Alert Modal Component -->
<div id="shop-alert-backdrop" class="shop-modal-backdrop" style="display: none;">
    <!-- Modal Container -->
    <div class="shop-modal-container">
        <div id="shop-alert-content" class="shop-modal-content">
            <!-- Decorative Top -->
            <div class="shop-modal-header">
                <div class="shop-modal-icon-wrapper shimmer">
                    <!-- Coffee Cup Icon -->
                    <svg class="shop-modal-icon" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.5,3H6C4.9,3,4,3.9,4,5v5.71c0,3.83,2.95,7.18,6.78,7.29c3.96,0.12,7.22-3.06,7.22-7v-1h0.5c1.93,0,3.5-1.57,3.5-3.5S20.43,3,18.5,3z M16,5v3H6V5H16z M18.5,8H18V5h0.5C19.33,5,20,5.67,20,6.5S19.33,8,18.5,8z M4,19h16v2H4V19z" />
                    </svg>
                </div>
                <div class="decorative-line"></div>
                <h3 id="shop-alert-title" class="shop-modal-title">
                    Xác nhận
                </h3>
                <p id="shop-alert-message" class="shop-modal-message">
                    Bạn có chắc chắn muốn thực hiện hành động này không?
                </p>
            </div>
            
            <!-- Modal Actions -->
            <div class="shop-modal-actions" id="shop-alert-actions">
                <button id="shop-alert-cancel" class="shop-btn shop-btn-outline">
                    <span>Hủy Bỏ</span>
                </button>
                <button id="shop-alert-confirm" class="shop-btn shop-btn-gradient">
                    <span>Đồng Ý</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600&display=swap');
    @import url("https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap");

    .shop-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(62, 39, 35, 0.6);
        z-index: 9999;
        backdrop-filter: blur(4px);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .shop-modal-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        pointer-events: none;
    }

    .shop-modal-content {
        pointer-events: auto;
        width: 100%;
        max-width: 420px;
        background: var(--surface-card-color, #ffffff);
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        position: relative;
        transform: scale(0.9) translateY(-20px);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
    }

    .shop-modal-backdrop[style*="display: block"] {
        opacity: 1;
    }

    .shop-modal-backdrop[style*="display: block"] .shop-modal-content {
        transform: scale(1) translateY(0);
    }

    /* Gradient Border Effect */
    .shop-modal-content::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 16px;
        padding: 2px;
        background: linear-gradient(135deg, var(--primary-color, #795548) 0%, var(--accent-color, #c15d2b) 100%);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0.5;
        pointer-events: none;
    }

    .shop-modal-header {
        padding: 2rem 2rem 0.5rem;
        text-align: center;
    }

    .shop-modal-icon-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(121, 85, 72, 0.1) 0%, rgba(193, 93, 43, 0.1) 100%);
    }

    .shop-modal-icon {
        width: 40px;
        height: 40px;
        color: #795548;
        animation: float 3s ease-in-out infinite;
    }

    .shop-modal-title {
        font-family: 'Oswald', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main-color, #012a40);
        margin-bottom: 0.75rem;
    }

    .shop-modal-message {
        font-family: 'Quicksand', sans-serif;
        font-weight: 500;
        color: var(--text-main-color, #012a40);
        opacity: 0.8;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .shop-modal-actions {
        padding: 1.5rem 2rem 2rem;
        display: flex;
        gap: 0.75rem;
    }

    .shop-btn {
        flex: 1;
        padding: 0.875rem 1.25rem;
        border-radius: 9999px;
        font-family: 'Quicksand', sans-serif;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .shop-btn-outline {
        background: transparent;
        border: 2px solid var(--primary-color, #795548);
        color: var(--primary-color, #795548);
    }

    .shop-btn-outline:hover {
        background-color: rgba(121, 85, 72, 0.05);
    }

    .shop-btn-gradient {
        border: none;
        background: linear-gradient(135deg, var(--primary-color, #795548) 0%, var(--accent-color, #c15d2b) 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .shop-btn-gradient:hover {
        background: linear-gradient(135deg, var(--accent-color, #c15d2b) 0%, var(--primary-color, #795548) 100%);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .decorative-line {
        height: 2px;
        width: 100%;
        background: linear-gradient(90deg, transparent 0%, var(--primary-color, #795548) 50%, transparent 100%);
        opacity: 0.2;
        margin-bottom: 1rem;
    }

    .shimmer {
        position: relative;
        overflow: hidden;
    }
    
    .shimmer::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0% { left: -100%; }
        50%, 100% { left: 100%; }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }
</style>

<script>
    (function() {
        const backdrop = document.getElementById('shop-alert-backdrop');
        const modalContent = document.getElementById('shop-alert-content');
        const titleEl = document.getElementById('shop-alert-title');
        const messageEl = document.getElementById('shop-alert-message');
        const cancelBtn = document.getElementById('shop-alert-cancel');
        const confirmBtn = document.getElementById('shop-alert-confirm');
        const actionsEl = document.getElementById('shop-alert-actions');
        
        let currentOnConfirm = null;
        let currentOnCancel = null;

        function hide() {
            backdrop.style.opacity = '0';
            // Wait for transition to finish
            setTimeout(() => {
                backdrop.style.display = 'none';
            }, 300);
        }

        function show() {
            backdrop.style.display = 'block';
            // Force reflow
            void backdrop.offsetWidth;
            backdrop.style.opacity = '1';
        }

        cancelBtn.onclick = function() {
            hide();
            if (currentOnCancel) currentOnCancel();
        };

        confirmBtn.onclick = function() {
            hide();
            if (currentOnConfirm) currentOnConfirm();
        };

        backdrop.onclick = function(e) {
            // Only close if clicked on backdrop (not content)
            // But we have an inner container now, so we check if target is backdrop or container
            // The container covers the screen but has pointer-events: none, content has auto.
            // If we click on container (which passes through to backdrop if not caught?), wait.
            // Actually, we bound the click to backdrop.
            if (e.target === backdrop || e.target.classList.contains('shop-modal-container')) {
                hide();
                if (currentOnCancel) currentOnCancel();
            }
        };

        window.ShopAlert = {
            confirm: function(title, message, onConfirm, onCancel, confirmText = 'Đồng Ý', cancelText = 'Hủy Bỏ') {
                titleEl.textContent = title;
                messageEl.textContent = message;
                
                confirmBtn.querySelector('span').textContent = confirmText;
                cancelBtn.querySelector('span').textContent = cancelText;
                
                cancelBtn.style.display = 'inline-flex';
                
                currentOnConfirm = onConfirm;
                currentOnCancel = onCancel;
                
                show();
            },
            
            alert: function(title, message, onOk, okText = 'Đóng') {
                titleEl.textContent = title;
                messageEl.textContent = message;
                
                confirmBtn.querySelector('span').textContent = okText;
                cancelBtn.style.display = 'none';
                
                currentOnConfirm = onOk;
                currentOnCancel = null;
                
                show();
            },
            
            success: function(message, onOk) {
                this.alert('Thành công', message, onOk, 'Tuyệt vời');
            },
            
            info: function(message, onOk) {
                this.alert('Thông báo', message, onOk, 'Đóng');
            },

            error: function(message, onOk) {
                this.alert('Lỗi', message, onOk, 'Đóng');
            }
        };
    })();
</script>
