import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';
import 'quill/dist/quill.snow.css';
import 'flatpickr/dist/flatpickr.css';
import flatpickr from 'flatpickr';
import { Vietnamese } from 'flatpickr/dist/l10n/vn.js';

document.addEventListener('DOMContentLoaded', () => {
  // Flatpickr Initialization
  const datePickers = [
    ...document.querySelectorAll('.datepicker'),
    document.getElementById('start-date'),
    document.getElementById('end-date')
  ].filter(el => el); // Filter out nulls

  if (datePickers.length > 0) {
      const currentLocale = document.documentElement.lang;
      const isVietnamese = currentLocale === 'vi' || currentLocale === 'vi-VN';
      
      const config = {
          dateFormat: "Y-m-d",
          altInput: true,
          altFormat: "d/m/Y",
          locale: isVietnamese ? Vietnamese : "default",
          allowInput: true
      };

      datePickers.forEach(input => {
        // Prevent double initialization if element has both ID and class or already initialized
        if (!input._flatpickr) {
            flatpickr(input, config);
        }
      });
  }

  const ta = document.getElementById('articleInput');
  if (ta && ta.tagName === 'TEXTAREA') {
    const editor = document.createElement('div');
    editor.id = 'articleEditor';
    editor.className = 'w-full px-3 py-2 border border-gray-300 rounded-lg min-h-[200px] bg-white';
    ta.insertAdjacentElement('beforebegin', editor);
    ta.style.display = 'none';
    import('quill').then(({ default: Quill }) => {
      const quill = new Quill(editor, {
        theme: 'snow',
        modules: {
          toolbar: {
            container: [
              [{ header: [1, 2, 3, false] }],
              ['bold', 'italic', 'underline', 'strike'],
              [{ list: 'ordered' }, { list: 'bullet' }],
              ['link', 'image', 'blockquote', 'code-block'],
              ['clean']
            ],
            handlers: {
              image: function () {
                const uploadUrl = ta.dataset.uploadUrl || '';
                const csrf = (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || '';
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.onchange = async () => {
                  const file = input.files && input.files[0];
                  if (!file || !uploadUrl) return;
                  const fd = new FormData();
                  fd.append('file', file);
                  try {
                    const resp = await fetch(uploadUrl, {
                      method: 'POST',
                      credentials: 'same-origin',
                      headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
                      },
                      body: fd
                    });
                    const data = await resp.json();
                    const url = data && data.url ? data.url : null;
                    if (!url) return;
                    const range = quill.getSelection(true);
                    quill.insertEmbed(range ? range.index : 0, 'image', url, 'user');
                    quill.setSelection((range ? range.index : 0) + 1, 0, 'user');
                  } catch (_) {}
                };
                input.click();
              }
            }
          }
        }
      });
      if (ta.value && ta.value.trim().length) {
        quill.clipboard.dangerouslyPasteHTML(ta.value);
      }
      const sync = () => { ta.value = quill.root.innerHTML; };
      quill.on('text-change', sync);
      const form = ta.closest('form');
      if (form) form.addEventListener('submit', sync);
    }).catch(() => {});
  }

  // Polling Notifications for Admin
  if (window.location.pathname.startsWith('/admin')) {
    // Topbar Elements
    const notifBtn = document.getElementById('admin-notif-btn');
    const notifBadge = document.getElementById('admin-notif-badge');
    const notifDropdown = document.getElementById('admin-notif-dropdown');
    const notifList = document.getElementById('admin-notif-list');
    const notifClear = document.getElementById('admin-notif-clear');
    
    let activeNotifications = [];

    // Toggle Dropdown
    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                notifDropdown.classList.add('hidden');
            }
        });
    }

    // Clear Notifications
    if (notifClear) {
        notifClear.addEventListener('click', () => {
            activeNotifications = [];
            updateTopbarUI();
        });
    }

    const updateTopbarUI = () => {
        if (!notifBadge || !notifList) return;

        // Update Badge
        if (activeNotifications.length > 0) {
            notifBadge.textContent = activeNotifications.length;
            notifBadge.classList.remove('hidden');
        } else {
            notifBadge.classList.add('hidden');
        }

        // Update List
        if (activeNotifications.length === 0) {
            notifList.innerHTML = `
                <div class="p-8 text-center text-gray-400 text-sm flex flex-col items-center">
                    <i class="far fa-bell-slash text-2xl mb-2 opacity-50"></i>
                    <p>Chưa có thông báo mới</p>
                </div>
            `;
        } else {
            notifList.innerHTML = activeNotifications.map(item => {
                if (item.type === 'order') {
                    return `
                        <a href="/admin/orders/${item.id}" class="block p-3 border-b border-gray-50 hover:bg-blue-50 transition-colors">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-bold text-blue-600 text-sm"><i class="fas fa-shopping-cart mr-1"></i> #${item.code}</span>
                                <span class="text-[10px] text-gray-400">${item.time}</span>
                            </div>
                            <p class="text-sm text-gray-800 font-medium truncate">${item.customer}</p>
                            <p class="text-xs text-red-500 font-bold mt-1">${item.total}</p>
                        </a>
                    `;
                } else if (item.type === 'comment') {
                    return `
                        <a href="/admin/posts/comments?status=pending" class="block p-3 border-b border-gray-50 hover:bg-green-50 transition-colors">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-bold text-green-600 text-sm"><i class="fas fa-comment mr-1"></i> Bình luận mới</span>
                                <span class="text-[10px] text-gray-400">${item.time}</span>
                            </div>
                            <p class="text-sm text-gray-800 font-medium truncate">${item.author}</p>
                            <p class="text-xs text-gray-500 mt-1 truncate">"${item.content}"</p>
                            <p class="text-[10px] text-gray-400 mt-0.5 truncate">Bài: ${item.post}</p>
                        </a>
                    `;
                } else if (item.type === 'review') {
                    return `
                        <a href="/admin/reviews?status=pending" class="block p-3 border-b border-gray-50 hover:bg-yellow-50 transition-colors">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-bold text-yellow-600 text-sm"><i class="fas fa-star mr-1"></i> Đánh giá mới</span>
                                <span class="text-[10px] text-gray-400">${item.time}</span>
                            </div>
                            <p class="text-sm text-gray-800 font-medium truncate">${item.author} (${item.rating}★)</p>
                            <p class="text-xs text-gray-500 mt-1 truncate">"${item.content}"</p>
                            <p class="text-[10px] text-gray-400 mt-0.5 truncate">SP: ${item.product}</p>
                        </a>
                    `;
                }
                return '';
            }).join('');
        }
    };

    // Create container if it doesn't exist
    let container = document.getElementById('admin-notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'admin-notification-container';
        container.className = 'fixed bottom-5 right-5 z-[9999] flex flex-col gap-3 w-80 pointer-events-none';
        document.body.appendChild(container);
    }

    // State for Last IDs
    let lastIds = {
        order: null,
        comment: null,
        review: null
    };

    const adminNotifPrefs = { loaded: false, enabled: false, soundUrl: null };
    const loadAdminNotifPrefs = async () => {
        if (adminNotifPrefs.loaded) return;
        try {
            const resp = await fetch('/admin/notifications/preferences', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (resp.ok) {
                const cfg = await resp.json();
                adminNotifPrefs.enabled = !!cfg.enabled;
                adminNotifPrefs.soundUrl = cfg.sound_url || null;
            }
        } catch (_) {}
        adminNotifPrefs.loaded = true;
    };

    const checkNotifications = async () => {
        try {
            await loadAdminNotifPrefs();
            // STEP 1: Fast Check (PHP Raw File) - Ultra low latency
            const params = new URLSearchParams({
                last_order_id: lastIds.order || 0,
                last_comment_id: lastIds.comment || 0,
                last_review_id: lastIds.review || 0,
                t: new Date().getTime()
            });
            
            const fastUrl = `/check-activity.php?${params.toString()}`;
            const fastResp = await fetch(fastUrl);
            
            if (fastResp.status === 204) return;
            
            // STEP 2: Fetch Details (Laravel API)
            const apiUrl = `/admin/notifications/check?${params.toString()}`;
            const resp = await fetch(apiUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            if (resp.status === 204) return;
            if (!resp.ok) return;

            const data = await resp.json();
            
            // Update State
            if (data.last_ids) {
                // If first load (was null), just set it
                const isFirstLoad = (lastIds.order === null);
                lastIds = data.last_ids;
                
                // On first load, don't show toasts if not needed, or show limited
                // But user wants to see notifications on browser open, so we add to list
            }

            // Combine all new items
            const newItems = [
                ...(data.orders || []), 
                ...(data.comments || []), 
                ...(data.reviews || [])
            ];

            if (newItems.length > 0) {
                // Add to Topbar List (Prepend)
                activeNotifications = [...newItems, ...activeNotifications];
                updateTopbarUI();

                // Show Toasts (Limit to 3 latest to avoid spam)
                if (adminNotifPrefs.enabled) {
                    const toastsToShow = newItems.slice(0, 3);
                    
                    toastsToShow.forEach(item => {
                        const notif = document.createElement('div');
                        notif.className = 'bg-white border-l-4 shadow-lg rounded-r-lg p-4 transform transition-all duration-500 translate-x-full opacity-0 pointer-events-auto flex items-start gap-3';
                        
                        let iconClass = 'fa-bell text-gray-500';
                        let borderColor = 'border-gray-500';
                        let title = 'Thông báo mới';
                        let content = '';
                        
                        if (item.type === 'order') {
                            iconClass = 'fa-shopping-cart text-blue-500';
                            borderColor = 'border-blue-500';
                            title = `Đơn hàng mới #${item.code}`;
                            content = `${item.customer} - ${item.total}`;
                        } else if (item.type === 'comment') {
                            iconClass = 'fa-comment text-green-500';
                            borderColor = 'border-green-500';
                            title = 'Bình luận mới';
                            content = `${item.author}: "${item.content}"`;
                        } else if (item.type === 'review') {
                            iconClass = 'fa-star text-yellow-500';
                            borderColor = 'border-yellow-500';
                            title = 'Đánh giá mới';
                            content = `${item.author} (${item.rating}★): "${item.content}"`;
                        }

                        notif.classList.add(borderColor);
                         notif.innerHTML = `
                             <div class="flex-shrink-0 pt-1">
                                 <i class="fas ${iconClass} text-xl"></i>
                             </div>
                             <div class="flex-1 min-w-0">
                                 <h4 class="text-sm font-bold text-gray-900 mb-0.5">${title}</h4>
                                 <p class="text-sm text-gray-600 truncate">${content}</p>
                                 <span class="text-xs text-gray-400 mt-1 block">${item.time}</span>
                             </div>
                             <button class="text-gray-400 hover:text-gray-600 ml-2" onclick="event.stopPropagation(); this.parentElement.remove()">
                                 <i class="fas fa-times"></i>
                             </button>
                         `;
                         
                         container.appendChild(notif);
                         notif.addEventListener('click', () => {
                             if (item.type === 'order') {
                                 window.location.href = `/admin/orders/${item.id}`;
                             } else if (item.type === 'comment') {
                                 window.location.href = '/admin/posts/comments?status=pending';
                             } else if (item.type === 'review') {
                                 window.location.href = '/admin/reviews?status=pending';
                             }
                         });

                        // Animation In
                        requestAnimationFrame(() => {
                            notif.classList.remove('translate-x-full', 'opacity-0');
                        });

                        // Audio (only if enabled and sound exists)
                        if (adminNotifPrefs.soundUrl) {
                            const audio = new Audio(adminNotifPrefs.soundUrl);
                            audio.play().catch(() => {});
                        }

                        // Auto Remove
                        setTimeout(() => {
                            notif.classList.add('translate-x-full', 'opacity-0');
                            setTimeout(() => notif.remove(), 500);
                        }, 5000);
                    });
                }
            }

        } catch (e) {
            console.error('Notification check failed', e);
        }
    };

    // Start Polling (5s)
    setInterval(checkNotifications, 5000);
    // Run immediately
    checkNotifications();
  }
});
