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
  if (!ta || ta.tagName !== 'TEXTAREA') return;
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
});
