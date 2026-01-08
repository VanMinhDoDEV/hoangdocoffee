# Hướng dẫn tích hợp Quill và xử lý ảnh (nội bộ, không CDN)

## Mục tiêu
- Tích hợp Quill (v2) làm trình soạn thảo cho trường “Bài viết” sản phẩm.
- Xử lý ảnh bằng cách upload file lên server nội bộ và chèn URL trả về vào nội dung editor.

## Yêu cầu
- Node/NPM đã cài.
- Vite đang dùng với `laravel-vite-plugin`.
- CSRF token sẵn có trong `<meta name="csrf-token">`.

## Cài đặt
- Cài Quill:
```
npm i quill@^2.0.0
```
- Chạy dev server:
```
npm run dev
```

## Tích hợp vào View
- Thêm CSRF meta trong trang tạo sản phẩm:
`resources/views/admin/product_create.blade.php:4`
```
<meta name="csrf-token" content="{{ csrf_token() }}">
```
- Dùng `@vite` để nạp CSS/JS nội bộ:
`resources/views/admin/product_create.blade.php:7`
```
@vite(['resources/css/app.css','resources/js/app.js'])
```
- Đặt `textarea` làm nguồn dữ liệu có `data-upload-url`:
`resources/views/admin/product_create.blade.php:72`
```
<textarea id="articleInput"
          name="article"
          rows="8"
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          data-upload-url="{{ route('admin.products.upload_image') }}"></textarea>
```

## Khởi tạo Quill trong app entry
- Khởi tạo editor, ẩn `textarea`, đồng bộ nội dung HTML:
`resources/js/app.js`
```js
import './bootstrap';
import 'quill/dist/quill.snow.css';

document.addEventListener('DOMContentLoaded', () => {
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
```

## API upload ảnh
- Route upload ảnh sản phẩm:
`routes/web.php:38`
```
Route::post('/admin/products/upload-image', [AdminController::class, 'uploadProductImage'])->name('admin.products.upload_image');
```
- Controller xử lý:
`app/Http/Controllers/AdminController.php:548`
```
public function uploadProductImage(Request $request) {
  $hasFile = $request->hasFile('file') || $request->hasFile('upload');
  $urlInput = trim((string)$request->input('url', ''));
  if (!$hasFile && !$urlInput) {
      return response()->json(['status' => 'error', 'message' => 'Thiếu file hoặc URL'], 422);
  }
  $dir = 'products/'.date('Y/m/d');
  if ($hasFile) {
      $field = $request->hasFile('upload') ? 'upload' : 'file';
      $request->validate([$field => ['required','image','max:10240']]);
      $file = $request->file($field);
      $filename = $this->uniqueFilename($dir, $file->getClientOriginalName());
      \Illuminate\Support\Facades\Storage::disk('public')->putFileAs($dir, $file, $filename);
      return response()->json(['url' => '/storage/'.$dir.'/'.$filename, 'name' => $file->getClientOriginalName(), 'size' => (int)$file->getSize()]);
  }
  // Tải từ URL (không dùng cho Quill handler ảnh)
  // ...
}
```

## Tùy biến
- Thay đổi toolbar: chỉnh mảng `container` trong `modules.toolbar`.
- Giới hạn loại ảnh: chỉnh `input.accept`.
- Kích thước tối đa: thêm ràng buộc client nếu cần; backend đang giới hạn `max:10240` (10MB).

## Ghi chú
- Nội dung editor được lưu dưới dạng HTML vào `textarea#articleInput` để backend nhận.
- Nếu cần hỗ trợ dán ảnh clipboard hoặc kéo thả ảnh vào editor, có thể mở rộng handler để bắt sự kiện paste/drop, upload và chèn URL tương tự.
