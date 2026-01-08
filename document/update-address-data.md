# Hướng dẫn Cập nhật Dữ liệu Địa chỉ Hành chính Việt Nam

Tài liệu này hướng dẫn cách cập nhật cơ sở dữ liệu địa chỉ (Tỉnh/Thành phố, Quận/Huyện, Xã/Phường) cho hệ thống khi có sự thay đổi từ chính phủ hoặc đơn vị cung cấp dữ liệu.

Hệ thống hiện tại sử dụng dữ liệu từ nguồn API: **AddressKit (cas.so)** và lưu trữ bản sao cục bộ (local cache) để đảm bảo tốc độ và tránh phụ thuộc vào bên thứ ba lúc vận hành.

---

## 1. Nguyên lý hoạt động

1.  **Nguồn dữ liệu**: `https://addresskit.cas.so/` (Dữ liệu mở cập nhật thường xuyên).
2.  **Script tải về**: `download_address.php` (nằm ở thư mục gốc của dự án).
3.  **Nơi lưu trữ**:
    *   File danh sách Tỉnh/Thành: `storage/app/json_address/provinces.json`
    *   Thư mục chi tiết Xã/Phường theo từng tỉnh: `storage/app/json_address/communes/*.json`

## 2. Các bước cập nhật dữ liệu mới

Khi có thông tin về việc sáp nhập, đổi tên hoặc thành lập mới các đơn vị hành chính, bạn thực hiện các bước sau để cập nhật hệ thống:

### Bước 1: Mở Terminal (Command Line)
Truy cập vào thư mục gốc của dự án trên máy chủ hoặc máy local.

### Bước 2: Chạy lệnh cập nhật
Chạy lệnh PHP sau để kích hoạt script tải dữ liệu tự động:

```bash
php download_address.php
```

### Bước 3: Chờ đợi quá trình hoàn tất
*   Script sẽ tự động kết nối đến API AddressKit.
*   Tải danh sách Tỉnh/Thành phố mới nhất.
*   Duyệt qua từng tỉnh để tải danh sách Xã/Phường mới nhất.
*   Quá trình này mất khoảng **1-2 phút** tùy thuộc vào tốc độ mạng (do phải tải chi tiết cho 63 tỉnh thành).
*   Màn hình sẽ hiển thị trạng thái `Downloading communes for...` và kết thúc bằng thông báo `Done!`.

### Bước 4: Kiểm tra lại
Sau khi chạy xong, bạn có thể kiểm tra nhanh bằng cách:
1.  Vào trang quản trị, mục thêm địa chỉ khách hàng.
2.  Thử chọn tỉnh/thành vừa có sự thay đổi để xem dữ liệu đã được cập nhật chưa.

## 3. Khắc phục sự cố thường gặp

*   **Lỗi "Failed to fetch..." hoặc script chạy quá lâu**: Kiểm tra kết nối internet của máy chủ.
*   **Lỗi cấu trúc dữ liệu**: Nếu API AddressKit thay đổi cấu trúc trả về (ví dụ đổi tên trường `provinces` thành `data`), bạn cần mở file `download_address.php` để điều chỉnh lại logic nhận diện dữ liệu (đã có code xử lý các trường hợp phổ biến).

---
*Ngày cập nhật tài liệu: 17/12/2025*
