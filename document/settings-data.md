# Tài liệu Cấu trúc Dữ liệu Cài đặt (Settings)

Tài liệu này mô tả cấu trúc dữ liệu cài đặt của hệ thống và hướng dẫn cách truy xuất dữ liệu này trong code.

## 1. Vị trí lưu trữ

Dữ liệu cài đặt được lưu trữ dưới dạng file JSON tại:
- **Disk**: `local`
- **Đường dẫn**: `storage/app/private/settings.json`
- **Đường dẫn tuyệt đối**: `e:\z-PHAN MEN\1-Installed\Ampps\www\shop06\storage\app\private\settings.json`

File này được bảo vệ (private) và không thể truy cập trực tiếp qua trình duyệt.

## 2. Cấu trúc dữ liệu

File JSON chứa một object với các key chính sau:

### 2.1. `store` (Thông tin cửa hàng)
Chứa các thông tin chung về cửa hàng, hiển thị trên website.

| Key | Kiểu | Mô tả |
|-----|------|-------|
| `name` | string | Tên cửa hàng |
| `tagline` | string | Khẩu hiệu (Slogan) |
| `description` | string | Mô tả chi tiết cửa hàng |
| `seo_description` | string | Mô tả SEO meta tag |
| `email` | string | Email liên hệ |
| `phone` | string | Số điện thoại chính |
| `address` | string | Địa chỉ cửa hàng |
| `website` | string | URL website |
| `logo_url` | string | Đường dẫn ảnh Logo |
| `favicon` | string | Đường dẫn ảnh Favicon (Icon trình duyệt) |
| `social_image` | string | Đường dẫn ảnh đại diện khi chia sẻ MXH (OG Image) |
| `theme_color` | string | Mã màu chủ đạo (HEX) |
| `facebook_url` | string | Link Facebook Fanpage |
| `instagram_url` | string | Link Instagram |
| `zalo_phone` | string | Số Zalo liên hệ |
| `hours_weekdays_open` | string | Giờ mở cửa ngày thường |
| `hours_weekdays_close` | string | Giờ đóng cửa ngày thường |
| `hours_sat_open` | string | Giờ mở cửa Thứ 7 |
| `hours_sat_close` | string | Giờ đóng cửa Thứ 7 |
| `hours_sun_open` | string | Giờ mở cửa Chủ Nhật |
| `hours_sun_close` | string | Giờ đóng cửa Chủ Nhật |

### 2.2. `payment` (Cấu hình thanh toán)
Cấu hình các phương thức và thông tin thanh toán.

| Key | Kiểu | Mô tả |
|-----|------|-------|
| `enabled_methods` | array | Danh sách mã các phương thức đang bật (vd: `['cod', 'bank_transfer']`) |
| `cod_enabled` | boolean | Bật/tắt thanh toán khi nhận hàng (COD) |
| `bank_transfer_enabled` | boolean | Bật/tắt chuyển khoản ngân hàng |
| `wallet_enabled` | boolean | Bật/tắt ví điện tử (nếu có) |
| `credit_card_enabled` | boolean | Bật/tắt thẻ tín dụng (nếu có) |
| `bank_account_name` | string | Tên chủ tài khoản ngân hàng |
| `bank_account_number` | string | Số tài khoản ngân hàng |
| `bank_name` | string | Tên ngân hàng |
| `bank_branch` | string | Chi nhánh ngân hàng |
| `transfer_note` | string | Nội dung chuyển khoản mẫu |
| `min_order` | number | Giá trị đơn hàng tối thiểu |
| `cod_fee` | number | Phí thu hộ (nếu có) |
| `cod_fee_type` | string | Loại phí thu hộ (`fixed` hoặc `percent`) |
| `auto_confirm` | boolean | Tự động xác nhận đơn hàng |

### 2.3. `shipping` (Cấu hình vận chuyển)
Cấu hình phí và phương thức vận chuyển.

| Key | Kiểu | Mô tả |
|-----|------|-------|
| `standard_enabled` | boolean | Bật/tắt giao hàng tiêu chuẩn |
| `express_enabled` | boolean | Bật/tắt giao hàng nhanh |
| `sameday_enabled` | boolean | Bật/tắt giao hàng trong ngày |
| `pickup_enabled` | boolean | Bật/tắt nhận tại cửa hàng |
| `free_threshold` | number | Giá trị đơn hàng tối thiểu để Freeship |
| `inner_standard` | number | Phí nội thành - Tiêu chuẩn |
| `inner_express` | number | Phí nội thành - Nhanh |
| `province_standard` | number | Phí ngoại thành/tỉnh - Tiêu chuẩn |
| ... | ... | (Các cấu hình phí khác tương tự) |

## 3. Cách lấy dữ liệu (Hướng dẫn Code)

Hiện tại, việc đọc/ghi cài đặt đang được xử lý trong `AdminController`. Để lấy dữ liệu ở các nơi khác (ví dụ: Controller khác, View, hoặc API), bạn có thể sử dụng đoạn code sau:

### Cách 1: Đọc trực tiếp (Dùng cho Controller/Service)

```php
use Illuminate\Support\Facades\Storage;

function getSettings() {
    $path = 'settings.json';
    // Disk 'local' trỏ tới storage/app/private
    if (!Storage::disk('local')->exists($path)) {
        return []; // Hoặc trả về mảng default
    }
    $json = Storage::disk('local')->get($path);
    return json_decode($json, true) ?? [];
}

// Sử dụng
$settings = getSettings();
$storeName = $settings['store']['name'] ?? 'Shop Name';
```

### Cách 2: Helper Function (Khuyên dùng)

Bạn nên tạo một Helper file (ví dụ `app/Helpers/AppHelper.php`) và đăng ký trong `composer.json` để dùng toàn cục.

**Bước 1:** Tạo file `app/Helpers/AppHelper.php` (nếu chưa có)

```php
<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('app_settings')) {
    function app_settings($key = null, $default = null)
    {
        // Cache settings để không phải đọc file nhiều lần trong 1 request
        static $settings = null;
        
        if ($settings === null) {
            $path = 'settings.json';
            if (Storage::disk('local')->exists($path)) {
                $settings = json_decode(Storage::disk('local')->get($path), true);
            } else {
                $settings = [];
            }
        }

        if ($key === null) {
            return $settings;
        }

        // Hỗ trợ dot notation (vd: 'store.name')
        return data_get($settings, $key, $default);
    }
}
```

**Bước 2:** Đăng ký trong `composer.json`
```json
"autoload": {
    "files": [
        "app/Helpers/AppHelper.php"
    ],
    ...
}
```

**Bước 3:** Chạy `composer dump-autoload`

**Bước 4:** Sử dụng ở bất kỳ đâu (Blade, Controller)
```php
// Trong Blade
<title>{{ app_settings('store.name', 'My Shop') }}</title>
<img src="{{ app_settings('store.logo_url') }}">

// Trong PHP
$minOrder = app_settings('payment.min_order', 0);
```
