# Hướng dẫn trang tạo sản phẩm (Admin) — `/admin/products/create`

## Mục tiêu
- Tạo sản phẩm mới với ảnh, biến thể (size, màu), giá, kho, và tổ chức (danh mục, bộ sưu tập, chất liệu).
- Hiểu rõ luồng dữ liệu, cách upload ảnh, cấu trúc JSON biến thể, và cách “lấy dữ liệu ra ngoài” qua API hoặc Eloquent.

## Truy cập
- Đường dẫn: `http://localhost:8000/admin/products/create`
- Controller render trang: `app/Http/Controllers/AdminController.php:165` (`productCreate`)
- View chính: `resources/views/admin/product_create.blade.php`

## Dữ liệu động sử dụng trong Form
- Thuộc tính sản phẩm (size, color, …):
  - `GET /admin/products/attributes/json` → danh sách nhóm thuộc tính kèm giá trị
  - `GET /admin/products/attributes/{attributeId}/values/json` → danh sách giá trị theo thuộc tính
  - Controller: `app/Http/Controllers/AdminController.php:418` (`productAttributesJson`), `app/Http/Controllers/AdminController.php:442` (`productAttributeValuesJson`)
- Bộ sưu tập:
  - `GET /admin/products/collections/json`
  - Controller: `app/Http/Controllers/AdminController.php:464` (`productCollectionsJson`)
- Chất liệu (gợi ý từ các sản phẩm đã lưu):
  - `GET /admin/products/materials/json`
  - Controller: `app/Http/Controllers/AdminController.php:491` (`productMaterialsJson`)

## Upload ảnh (sản phẩm/biến thể)
- API: `POST /admin/products/upload-image`
  - Controller: `app/Http/Controllers/AdminController.php:674` (`uploadProductImage`)
  - Hỗ trợ:
    - Upload file: multipart `file` (hoặc `upload`)
    - Tải từ URL: body JSON `{ "url": "https://..." }`
  - Phản hồi mẫu:
    ```json
    {
      "url": "/storage/products/2025/12/13/abc.webp",
      "name": "abc.webp",
      "size": 123456
    }
    ```
- Lưu ý:
  - Ảnh từ API trả về `url` dạng nội bộ `"/storage/..."` là hợp lệ và sẽ được lưu.
  - Ảnh sản phẩm chính lưu với `product_variant_id = null`.
  - Ảnh biến thể lưu với `product_variant_id = id biến thể`.

## Biến thể và cấu trúc JSON gửi lên
- Trên form, phần Biến thể hiển thị các thuộc tính từ API và cho phép chọn 1 giá trị mỗi thuộc tính.
- Trước khi submit, script sẽ chuẩn hoá JSON biến thể vào hidden `#variantsJson`.
- Cấu trúc mảng biến thể gửi lên (mẫu):
```json
[
  {
    "sku": "AB1234SZ",
    "stock": 10,
    "price": 299000,
    "sale_price": 279000,
    "is_active": true,
    "values": [
      { "option_code": "size", "value": "M" },
      { "option_code": "color", "value": "#FF0000" }
    ],
    "images": [
      "/storage/products/2025/12/13/variant-1.webp",
      "/storage/products/2025/12/13/variant-2.webp"
    ]
  }
]
```

## Luồng Submit
- Logic ở view: `resources/views/admin/product_create.blade.php:529`
  - Giai đoạn trước submit:
    - Upload các file ảnh đang là “local file” lên API upload ảnh, lấy `url`.
    - Cập nhật `primary_image_url` nếu cần.
    - Chuẩn hoá các `images_urls[]` (ảnh phụ của sản phẩm chính).
    - Build `variants` payload cuối cùng (ảnh biến thể là danh sách `url`).
  - Gửi form tới `POST /admin/products`.
- Xử lý server khi tạo sản phẩm:
  - Controller: `app/Http/Controllers/AdminController.php:491` (`productStore`)
  - Validate đầu vào (đã nới lỏng `primary_image_url` và `images_urls.*` sang `string` để chấp nhận `"/storage/..."`).
  - Tạo `Product`, ảnh sản phẩm (`ProductImage`) và biến thể (`ProductVariant`).
  - Map thuộc tính biến thể: lưu vào `product_variant_options` theo cặp `{variant_id, attribute_id, attribute_value_id}` (xem: `app/Http/Controllers/AdminController.php:620`).
  - Tạo ảnh biến thể: `app/Http/Controllers/AdminController.php:641`.
  - Chuẩn hoá `in_stock` theo tổng tồn kho biến thể.

## Lấy dữ liệu ra ngoài (API/Eloquent)
- Lấy danh sách gợi ý để hiển thị UI hoặc dùng nơi khác:
  - Thuộc tính: `GET /admin/products/attributes/json`
  - Giá trị thuộc tính: `GET /admin/products/attributes/{id}/values/json`
  - Bộ sưu tập: `GET /admin/products/collections/json`
  - Chất liệu: `GET /admin/products/materials/json`
  - Ví dụ cURL:
    ```bash
    curl -s http://localhost:8000/admin/products/attributes/json
    curl -s http://localhost:8000/admin/products/collections/json
    curl -s http://localhost:8000/admin/products/materials/json
    ```
- Lấy sản phẩm đã tạo để dùng bên ngoài (qua Eloquent hoặc tạo API JSON riêng):
  - Eloquent ví dụ (trong controller tự tạo):
    ```php
    // Ví dụ: trả về JSON product + ảnh + biến thể + thuộc tính biến thể
    $product = \App\Models\Product::with([
      'images' => function($q){ $q->orderBy('position'); },
      'variants' => function($q){ $q->select('id','product_id','sku','price','inventory_quantity','is_active'); },
      'variants.options.attribute',
      'variants.options.attributeValue',
    ])->findOrFail($productId);

    return response()->json($product);
    ```
  - Quan hệ:
    - `Product` → `images`: `app/Models/Product.php:24`
    - `Product` → `variants`: `app/Models/Product.php:20`
    - `ProductVariant` → `options` (bảng nối thuộc tính): `app/Models/ProductVariant.php:22`
    - `ProductImage` dùng khóa `product_variant_id`: `app/Models/ProductImage.php:11`
  - Gợi ý tạo route JSON (tuỳ chọn):
    ```php
    Route::get('/api/products/{id}', function($id){
      $p = \App\Models\Product::with(['images','variants','variants.options.attribute','variants.options.attributeValue'])->findOrFail($id);
      return response()->json($p);
    });
    ```

## Ghi chú SKU
- Ký tự hợp lệ: chữ cái A–Z, số 0–9, và dấu `-`. Tự động chuẩn hóa về chữ hoa, tối đa 16 ký tự (`app/Http/Controllers/AdminController.php:23` — `skuSanitize`).
- SKU sản phẩm chính:
  - Tự sinh nếu không nhập: `PREFIX-BASE36TIME` (`app/Http/Controllers/AdminController.php:62` — `generateProductSkuBase`).
  - `PREFIX` lấy từ `collection`/`vendor` hoặc tên danh mục (rút gọn 2 ký tự) (`app/Http/Controllers/AdminController.php:35-48` — `makePrefix`).
- SKU biến thể:
  - Tự sinh theo chữ ký thuộc tính `SIZE/COLOR`: `PRODUCTSKU-SC` (`app/Http/Controllers/AdminController.php:117` — `buildVariantSku`).
  - `SC` là ghép 2 ký tự: size rút gọn (`XS,S,M,L,...`) và màu rút gọn (`BLACK->B`, `WHITE->W`, ...) (`app/Http/Controllers/AdminController.php:68-99` — `abbrSize`, `abbrColor`, `variantAttrSig`).
- Đảm bảo duy nhất:
  - Nếu SKU trùng, hệ thống cắt theo ngưỡng 16 ký tự và thêm hậu tố chữ số cơ số 36 (`app/Http/Controllers/AdminController.php:49-61` — `ensureUniqueSku`).
- Cập nhật sản phẩm:
  - Nếu bạn xóa SKU (để trống), hệ thống tự sinh SKU mới khi lưu (`app/Http/Controllers/AdminController.php:315-323` — `productUpdate`).

## Lỗi thường gặp và cách xử lý
- “Không lưu” khi có ảnh `"/storage/..."`: đã nới validate sang `string` (không ép `url`) ở `app/Http/Controllers/AdminController.php:503,505`.
- Lỗi cột `variant_id` không tồn tại ở `product_images`: đã chuyển qua `product_variant_id`. Model và chỗ tạo ảnh đã cập nhật.

## Tóm tắt
- Dùng các API JSON sẵn có để lấy dữ liệu UI (attributes/values/collections/materials).
- Khi cần “lấy dữ liệu ra ngoài” (integration), tạo controller trả JSON dựa trên Eloquent như ví dụ, hoặc mở rộng các route hiện hữu theo nhu cầu.
