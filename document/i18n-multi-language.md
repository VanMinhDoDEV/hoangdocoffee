# Hướng dẫn đa ngôn ngữ (i18n) cho dự án

## Mục tiêu
- Hỗ trợ hiển thị giao diện theo ngôn ngữ người dùng chọn trong phần cài đặt admin.
- Mặc định toàn hệ thống dùng Tiếng Việt, fallback cũng là Tiếng Việt.
- Chuẩn hoá cách viết code theo format đa ngôn ngữ để dễ mở rộng và bảo trì.

## Kiến trúc i18n hiện tại
- `config/app.php`:
  - `locale` mặc định `vi` (`config/app.php:81`)
  - `fallback_locale` là `vi` (`config/app.php:83`)
  - `faker_locale` là `vi_VN` (`config/app.php:85`)
- `SetLocale` middleware:
  - Được chạy trong nhóm `web` (`bootstrap/app.php:15`)
  - Ưu tiên ngôn ngữ admin từ `settings.json` cho các đường dẫn bắt đầu bằng `admin` (`app/Http/Middleware/SetLocale.php:36`)
  - Fallback admin là `vi` nếu chưa cấu hình (`app/Http/Middleware/SetLocale.php:37`)
  - Frontend dùng `session('locale')` hoặc `config('app.locale')` (`app/Http/Middleware/SetLocale.php:39`)
- Lưu và hiển thị ngôn ngữ admin:
  - Controller đọc ngôn ngữ admin từ `settings.json` và truyền sang view (`app/Http/Controllers/AdminController.php:2403`–`2405`)
  - View `profile` bind ngôn ngữ vào `<select>` (`resources/views/admin/settings/profile.blade.php:168`–`171`)
  - Khi lưu, ghi `settings['admin']['language']` và cập nhật `session('locale')` (`app/Http/Controllers/AdminController.php:2519`–`2526`)
- Chuyển ngôn ngữ frontend theo session:
  - `routes/web.php:13`–`18` cập nhật `session('locale')` khi truy cập `/lang/{locale}`
- File dịch:
  - `lang/vi/messages.php`, `lang/en/messages.php`
  - Chưa có `lang/ja/messages.php`; nếu dùng tiếng Nhật, cần bổ sung file tương ứng.

## Quy tắc viết code đa ngôn ngữ
- Blade:
  - Dùng `__('messages.key')` thay vì viết chuỗi trực tiếp.
  - Ví dụ: `resources/views/admin/collections/collections.blade.php:4`–`10` đã dùng `__('messages.collections_title')`.
  - Không hard-code chuỗi trong Blade; nếu cần text tạm thời, thêm key mới vào `messages.php` rồi tham chiếu.
- Controller:
  - Không trả text cứng; với flash message, dùng key dịch, ví dụ `return back()->with('status', __('messages.saved_success'));`.
  - Chỉ truyền dữ liệu sang view; việc hiển thị text do `__('...')` xử lý.
- JavaScript:
  - Tiêm chuỗi dịch từ Blade sang JS bằng biến render sẵn:
    ```php
    <script>
      window.i18n = {
        save: "{{ __('messages.save') }}",
        cancel: "{{ __('messages.cancel') }}"
      };
    </script>
    ```
  - Tránh nhúng chuỗi cố định trong JS; nếu cần, đọc từ `window.i18n`.
- Route chuyển ngôn ngữ:
  - Frontend: dùng `GET /lang/{locale}` (chỉ hỗ trợ các mã cho phép).
  - Admin: ngôn ngữ lấy theo `settings.json` để đảm bảo đồng bộ cho toàn bộ giao diện quản trị.

## Thêm ngôn ngữ mới
- Tạo thư mục `lang/{code}` và file `messages.php` với đầy đủ key:
  - Ví dụ cho tiếng Nhật: `lang/ja/messages.php`
- Cập nhật UI chọn ngôn ngữ admin:
  - Thêm `<option value="{code}">...</option>` trong `resources/views/admin/settings/profile.blade.php:168`–`171`
  - Cập nhật rule validator để chấp nhận `{code}` mới (`app/Http/Controllers/AdminController.php:2496`–`2497`)
- Kiểm tra `SetLocale` không hạn chế mã ngôn ngữ; hiện tại đọc từ `settings.json` nên hoạt động linh hoạt.

## Quy ước đặt key dịch
- Sử dụng cấu trúc nhóm theo màn hình/chức năng, ví dụ:
  - `collections_title`, `collections_description`
  - `inventory_management`, `orders`, `customers`
- Tránh key quá dài, dùng snake_case, và giữ nhất quán giữa các ngôn ngữ.
- Gom nhóm theo domain nếu cần: `inventory.*`, `reports.*`, nhưng trong file `messages.php` dạng phẳng giúp tìm kiếm nhanh.

## Luồng hoạt động điển hình
- Admin chọn ngôn ngữ trong trang Hồ sơ:
  - Hiển thị hiện tại từ `settings.json` (`AdminController.php:2403`–`2405`, `profile.blade.php:168`–`171`)
  - Khi lưu, ghi lại `settings['admin']['language']` và `session('locale')` (`AdminController.php:2519`–`2526`)
  - Middleware áp dụng ngôn ngữ cho mọi trang `/admin` (`SetLocale.php:36`–`44`)
- Frontend chuyển ngôn ngữ bằng route `/lang/{locale}` (`routes/web.php:13`–`18`)

## Kiểm thử
- Đổi ngôn ngữ admin tại `Cài đặt > Hồ sơ`, sau đó truy cập các trang `/admin` để xác nhận.
- Kiểm thử nhanh bằng lệnh:
  - `php artisan test` (đã có test cơ bản, xem kết quả để đảm bảo không lỗi)
- Soát lại các view: đảm bảo dùng `__('messages.*')` thay vì hard-code.

## Câu hỏi thường gặp
- Tại sao `/lang/{locale}` không đổi ngôn ngữ admin?
  - Admin ưu tiên ngôn ngữ từ `settings.json` để đồng bộ và không phụ thuộc session frontend.
- Nếu chưa có file dịch cho ngôn ngữ mới thì sao?
  - Chuỗi sẽ fallback về `vi` theo `config/app.php:83`. Nên bổ sung đầy đủ key cho ngôn ngữ mới.

## Bảo trì
- Khi thêm màn hình mới, tạo key dịch trong `lang/vi/messages.php` và đối ứng trong các ngôn ngữ còn lại.
- Tránh trộn ngôn ngữ trong cùng view; thống nhất sử dụng `__('messages.*')`.
- Định kỳ kiểm tra thiếu key bằng cách so sánh số lượng key giữa các file `messages.php`.

