# Shop06 - Hệ thống Thương mại Điện tử

Dự án Shop06 là một ứng dụng thương mại điện tử hiện đại, đầy đủ tính năng được xây dựng trên nền tảng Laravel Framework 12 và TailwindCSS. Hệ thống bao gồm giao diện người dùng (Client) thân thiện và trang quản trị (Admin) mạnh mẽ để quản lý sản phẩm, đơn hàng, kho hàng và nội dung.

## 🚀 Công nghệ sử dụng

-   **Backend:** PHP 8.2+, Laravel 12.x
-   **Frontend:** Blade Templates, TailwindCSS v4, Vite, Font Awesome
-   **Database:** MySQL
-   **JavaScript:** Axios, Quill Editor

## ✨ Tính năng chính

### 🛒 Dành cho Khách hàng (Client)
-   **Trang chủ & Sản phẩm:** Hiển thị sản phẩm nổi bật, tìm kiếm, lọc sản phẩm theo danh mục, bộ sưu tập, màu sắc (hỗ trợ tiếng Việt).
-   **Chi tiết sản phẩm:** Xem ảnh, chọn biến thể (size, màu), xem nhanh (Quick View), đánh giá sản phẩm.
-   **Giỏ hàng & Thanh toán:** Quản lý giỏ hàng, thanh toán nhanh (Buy Now) hoặc thanh toán thường, tích hợp địa chỉ giao hàng.
-   **Tài khoản & Dashboard:**
    -   Quản lý đơn hàng, lịch sử mua hàng.
    -   Danh sách yêu thích (Wishlist).
    -   Sổ địa chỉ, cập nhật hồ sơ, avatar.
    -   Quản lý số đo cơ thể (Measurements).
-   **Blog:** Xem bài viết, bình luận, chuyên mục tin tức.
-   **Tiện ích:** Gợi ý chọn size (Size Guide), Đa ngôn ngữ (Vi/En).

### 🔧 Dành cho Quản trị viên (Admin)
-   **Dashboard:** Tổng quan doanh thu, đơn hàng mới.
-   **Quản lý Sản phẩm:**
    -   Thêm/Sửa/Xóa sản phẩm, biến thể (SKU), thuộc tính.
    -   Quản lý danh mục, bộ sưu tập.
    -   Upload hình ảnh hàng loạt.
-   **Quản lý Kho hàng (Inventory):**
    -   Quản lý kho bãi (Warehouses).
    -   Theo dõi biến động kho (Stock Movements).
    -   Nhập kho, điều chỉnh, chuyển kho.
-   **Quản lý Đơn hàng:** Xem chi tiết, cập nhật trạng thái, xử lý đơn hàng.
-   **Quản lý Khách hàng:** Danh sách người dùng, xác thực, phân quyền.
-   **Báo cáo (Reports):** Doanh thu, Sản phẩm bán chạy, Khách hàng tiềm năng.
-   **Cấu hình hệ thống:** Cài đặt chung, thanh toán, vận chuyển, menu, banner.
-   **Quản lý Nội dung:** Blog, chuyên mục, bình luận.

## 🛠️ Hướng dẫn Cài đặt

1.  **Clone dự án:**
    ```bash
    git clone <repository-url>
    cd shop06
    ```

2.  **Cài đặt các gói phụ thuộc:**
    ```bash
    composer install
    npm install
    ```

3.  **Cấu hình môi trường:**
    -   Copy file `.env.example` thành `.env`:
        ```bash
        cp .env.example .env
        ```
    -   Cập nhật thông tin Database trong file `.env`.

4.  **Khởi tạo ứng dụng:**
    ```bash
    php artisan key:generate
    php artisan migrate --seed  # Chạy migration và seed dữ liệu mẫu (nếu có)
    ```

5.  **Build Frontend:**
    ```bash
    npm run build
    ```

6.  **Chạy server:**
    ```bash
    php artisan serve
    # Mở trình duyệt tại: http://localhost:8000
    ```

## 📝 Cập nhật gần đây

-   **Giao diện:** Đã cập nhật font chữ toàn trang sang **Arsenal** (Google Fonts) để hỗ trợ hiển thị tiếng Việt tốt hơn và mang lại vẻ hiện đại.
-   **Sản phẩm:** Cải thiện bộ lọc màu sắc (Color Filter) trên sidebar, hỗ trợ nhận diện các alias tiếng Việt (Màu, Màu sắc, v.v.).

## 📄 Bản quyền

Dự án được phát triển trên nền tảng [Laravel](https://laravel.com).
