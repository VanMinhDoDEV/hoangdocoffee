# Menu Builder — Chức năng thêm menu, lấy dữ liệu và kiểu dữ liệu

## Tổng quan
- Menu Builder nằm trong trang Admin › Cài đặt › Tổng quan.
- Cho phép tạo nhiều menu, thêm mục từ các nguồn có sẵn (Danh mục sản phẩm, Bộ sưu tập, Chuyên mục bài viết) hoặc liên kết tự chọn.
- Hỗ trợ kéo thả, lồng cấp, đổi tên hiển thị và tùy ý sửa URL từng mục.

## Luồng dữ liệu
- Tải dữ liệu:
  - Server đọc cấu hình từ `storage/app/private/settings.json` và truyền `menus` vào view.
  - JavaScript khởi tạo từ biến `serverMenus` và render lên trình chỉnh sửa.
  - Tham chiếu: `resources/views/admin/settings/general.blade.php:898`
- Lưu dữ liệu:
  - Khi bấm “Lưu Cấu Trúc”, JS serialize cây mục và gửi JSON về endpoint.
  - Endpoint ghi vào `settings.json` trên ổ “local”.
  - Tham chiếu:
    - `resources/views/admin/settings/general.blade.php:874`
    - `app/Http/Controllers/AdminController.php:416` (lưu menus)
    - `app/Http/Controllers/AdminController.php:411` (ghi file)

## Điểm lưu trữ
- File: `storage/app/private/settings.json`
- Disk: `config/filesystems.php` thiết lập `local` trỏ về `storage_path('app/private')`.
- Khóa dữ liệu:
  - `menus`: cấu trúc toàn bộ menu
  - `store`, `payment`, `shipping`: cấu hình khác của cửa hàng

## Nguồn phần tử
- Danh mục sản phẩm:
  - Nút “+ Thêm” sinh URL theo ngôn ngữ:
    - VI: `/danh-muc/{slug}`
    - EN: `/categories/{slug}`
  - Tham chiếu: `resources/views/admin/settings/general.blade.php:396`
- Bộ sưu tập:
  - VI: `/bo-suu-tap/{slug}`
  - EN: `/collections/{slug}`
  - Tham chiếu: `resources/views/admin/settings/general.blade.php:415`
- Chuyên mục bài viết (Blog):
  - VI: `/blog/chuyen-muc/{slug}`
  - EN: `/blog/categories/{slug}`
  - Tham chiếu: `resources/views/admin/settings/general.blade.php:431`
- Liên kết tự chọn:
  - Cho phép để trống URL → tạo mục “Heading” (không click).
  - Nếu có URL → mục “Link” (click).
  - Tham chiếu: `resources/views/admin/settings/general.blade.php:744`

## Xử lý tạo mục và cây
- Thêm mục:
  - Hàm: `addToTree(name, url, type, objectId)` thêm một node vào gốc cây.
  - Tham chiếu: `resources/views/admin/settings/general.blade.php:723`
- Hiển thị node:
  - Cho phép sửa “Tên hiển thị” và “Đường dẫn” trực tiếp.
  - Tự động hiển thị loại “Link” hoặc “Heading” dựa trên URL.
  - Tham chiếu: `resources/views/admin/settings/general.blade.php:765`
- Kéo thả lồng cấp:
  - Mỗi node có container con `nested-sortable` cho phép lồng nhiều cấp.
  - Tham chiếu: `resources/views/admin/settings/general.blade.php:796`
- Serialize cây:
  - JS quét DOM và trả về mảng các mục theo cấu trúc bên dưới.
  - Tham chiếu: `resources/views/admin/settings/general.blade.php:844`

## Kiểu dữ liệu
- Cấu trúc một mục (node):
```json
{
  "id": "item_xxxxx",
  "name": "Áo khoác",
  "url": "/danh-muc/thoi-trang-nam/ao-khoac",
  "type": "category",        // hoặc "collection", "post_category", "custom", "heading"
  "objectId": "123",         // id đối tượng nguồn (nếu có)
  "children": [ ... ]        // danh sách mục con
}
```
- Cấu trúc một menu:
```json
{
  "id": "menu_abc",
  "name": "Main Menu",
  "type": "Mega Menu",       // hoặc kiểu khác tùy UI
  "items": [ { ...node }, { ...node } ]
}
```
- Payload lưu:
```json
{
  "menus": {
    "menu_abc": { "id": "menu_abc", "name": "Main Menu", "type": "Mega Menu", "items": [ ... ] },
    "menu_def": { "id": "menu_def", "name": "Footer Menu", "type": "Link List", "items": [ ... ] }
  },
  "store": { ... },
  "payment": { ... },
  "shipping": { ... }
}
```

## Đa ngôn ngữ & URL
- Dựa vào `app()->getLocale()` để sinh URL nguồn phần tử đúng ngôn ngữ (VI/EN).
- Route song ngữ cho danh mục sản phẩm:
  - EN: `/categories/{slug}` và bắt đa cấp `/categories/{path}`
  - VI: `/danh-muc/{slug}` và bắt đa cấp `/danh-muc/{path}`
- Controller xử lý đa cấp:
  - Lấy slug là segment cuối cùng để tìm danh mục.
  - Tham chiếu: `app/Http/Controllers/ProductController.php:64`
- Blog chuyên mục song ngữ:
  - EN: `/blog/categories/{slug}` và `/blog/categories/{path}`
  - VI: `/blog/chuyen-muc/{slug}` và `/blog/chuyen-muc/{path}`

## API/Endpoints liên quan
- Lưu menu: `POST /admin/settings/menus` (`admin.settings.menus.save`)
  - Tham chiếu route: `routes/web.php:147`
  - Controller: `AdminController@settingsMenusSave` — `app/Http/Controllers/AdminController.php:416`
- Đọc cấu hình: `AdminController@readSettings` — `app/Http/Controllers/AdminController.php:372`
- Ghi cấu hình: `AdminController@writeSettings` — `app/Http/Controllers/AdminController.php:411`

## Hiển thị ngoài frontend (gợi ý tích hợp)
- Tải `settings.json` và đọc `menus`.
- Render theo `items` với logic:
  - Nếu `type === 'heading'` hoặc `url === ''` → hiển thị `<span>` không click.
  - Ngược lại → hiển thị `<a href="url">name</a>`.
  - Lặp `children` để tạo dropdown/mega menu.

## Ghi chú bảo mật
- `settings.json` được lưu trong `storage/app/private` — không public.
- Khi cần public hóa hình ảnh/icon, hãy sử dụng `storage/app/public` hoặc CDN.

