-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th1 08, 2026 lúc 12:44 AM
-- Phiên bản máy phục vụ: 8.0.39
-- Phiên bản PHP: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `hoangdocoffee`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ward` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `name`, `phone`, `address_line`, `ward`, `city`, `is_default`, `created_at`, `updated_at`) VALUES
(4, 5, 'Minh Béo', '0375564831', '123', 'Phường Móng Cái 1', 'Tỉnh Quảng Ninh', 1, '2025-12-16 22:09:23', '2025-12-16 22:09:23'),
(5, 8, 'Hoàng Thị Huyền', '0987624932', 'hiệp lộc 2', 'Xã Phúc Thọ', 'Thành phố Hà Nội', 1, '2025-12-16 22:13:52', '2025-12-16 22:13:52'),
(7, 8, 'Hoàng thị Huyền', '0375564835', '42 thôn 10 Hải xuân', 'Phường Móng Cái 1', 'Tỉnh Quảng Ninh', 0, '2025-12-16 22:34:19', '2025-12-16 22:34:19'),
(11, 4, 'Văn Minh Đỗ', '0375564838', '42 Thôn 10 Hải Xuân', 'Phường Móng Cái 1', 'Tỉnh Quảng Ninh', 1, '2025-12-24 01:46:02', '2025-12-24 01:46:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bundles`
--

CREATE TABLE `bundles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bundles`
--

INSERT INTO `bundles` (`id`, `name`, `slug`, `description`, `price`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Combo Tiết Kiệm', 'combo-tiet-kiem', 'Gói combo 2 áo thun + 1 quần lót.', 399000.00, 1, '2025-12-04 06:31:49', '2025-12-04 06:31:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bundle_items`
--

CREATE TABLE `bundle_items` (
  `id` bigint UNSIGNED NOT NULL,
  `bundle_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bundle_items`
--

INSERT INTO `bundle_items` (`id`, `bundle_id`, `quantity`, `created_at`, `updated_at`, `product_variant_id`) VALUES
(1, 1, 2, '2025-12-04 06:31:49', '2025-12-04 06:31:49', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `meta_title`, `meta_description`, `image_url`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(13, 'Máy Pha cà phê', 'may-pha-ca-phe', NULL, NULL, NULL, NULL, 1, 1, '2026-01-07 01:11:49', '2026-01-07 01:14:42'),
(14, 'Macap', 'macap', NULL, NULL, NULL, 13, 2, 1, '2026-01-07 01:12:25', '2026-01-07 01:12:25'),
(15, 'Bravilor', 'bravilor', NULL, NULL, NULL, 13, 3, 1, '2026-01-07 01:13:02', '2026-01-07 01:13:02'),
(16, 'Royal', 'royal', NULL, NULL, NULL, 13, 5, 1, '2026-01-07 01:13:58', '2026-01-07 01:14:57'),
(17, 'Iberital', 'iberital', NULL, NULL, NULL, 13, 4, 1, '2026-01-07 01:14:22', '2026-01-07 01:14:33'),
(18, 'Tự động', 'tu-dong', NULL, NULL, NULL, 13, 7, 1, '2026-01-07 01:21:43', '2026-01-07 01:21:57'),
(19, 'Cà phê rang', 'ca-phe-rang', NULL, NULL, NULL, NULL, 0, 1, '2026-01-07 01:23:00', '2026-01-07 01:23:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `collections`
--

CREATE TABLE `collections` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `collection_images`
--

CREATE TABLE `collection_images` (
  `id` bigint UNSIGNED NOT NULL,
  `collection_id` bigint UNSIGNED NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `combos`
--

CREATE TABLE `combos` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `free_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `combos`
--

INSERT INTO `combos` (`id`, `name`, `slug`, `description`, `price`, `is_active`, `free_shipping`, `created_at`, `updated_at`) VALUES
(1, 'test combo', 'test-combo', NULL, 1000000.00, 1, 0, '2026-01-05 02:40:51', '2026-01-05 02:40:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `combo_lines`
--

CREATE TABLE `combo_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `combo_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_measurements`
--

CREATE TABLE `customer_measurements` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `size_chart_id` bigint UNSIGNED DEFAULT NULL,
  `shoulder_cm` decimal(5,1) DEFAULT NULL,
  `bust_cm` decimal(5,1) DEFAULT NULL,
  `waist_cm` decimal(5,1) DEFAULT NULL,
  `hips_cm` decimal(5,1) DEFAULT NULL,
  `height_cm` decimal(5,1) DEFAULT NULL,
  `weight_kg` decimal(5,1) DEFAULT NULL,
  `computed_size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `customer_measurements`
--

INSERT INTO `customer_measurements` (`id`, `user_id`, `size_chart_id`, `shoulder_cm`, `bust_cm`, `waist_cm`, `hips_cm`, `height_cm`, `weight_kg`, `computed_size`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 35.0, 88.0, 70.0, 94.0, 152.0, 42.0, NULL, '2025-12-23 02:51:53', '2025-12-23 02:51:53'),
(2, 5, NULL, 35.0, 88.0, 70.0, 94.0, 152.0, 42.0, NULL, '2025-12-23 02:57:19', '2025-12-23 02:57:19'),
(3, 5, NULL, 35.0, 88.0, 70.0, 94.0, 152.0, 42.0, NULL, '2025-12-23 02:57:44', '2025-12-23 02:57:44'),
(4, 5, NULL, 25.0, 88.0, 70.0, 94.0, 152.0, 42.0, 'M', '2025-12-23 02:59:46', '2025-12-23 02:59:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_profiles`
--

CREATE TABLE `customer_profiles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `club_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'basic',
  `lifetime_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `reward_points` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `customer_profiles`
--

INSERT INTO `customer_profiles` (`id`, `user_id`, `club_level`, `lifetime_value`, `reward_points`, `created_at`, `updated_at`) VALUES
(2, 4, 'basic', 0.00, 0, '2025-12-04 07:06:02', '2025-12-04 07:06:02'),
(3, 5, 'basic', 0.00, 0, '2025-12-16 16:56:45', '2025-12-16 16:56:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_04_000100_create_products_table', 2),
(5, '2025_12_04_000110_create_variants_table', 2),
(6, '2025_12_04_000120_create_product_images_table', 2),
(7, '2025_12_04_000130_create_bundles_table', 2),
(8, '2025_12_04_000140_create_bundle_items_table', 2),
(9, '2025_12_04_000150_create_customer_profiles_table', 2),
(10, '2025_12_04_000160_create_addresses_table', 2),
(11, '2025_12_04_000170_create_orders_table', 2),
(12, '2025_12_04_000180_create_order_items_table', 2),
(13, '2025_12_04_000190_create_shipments_table', 2),
(14, '2025_12_04_000200_create_size_charts_table', 2),
(15, '2025_12_04_001000_add_is_admin_to_users_table', 3),
(16, '2025_12_04_001100_add_role_to_users_table', 4),
(17, '2025_12_04_001200_drop_is_admin_from_users_table', 5),
(18, '2025_12_06_000300_create_categories_table', 6),
(19, '2025_12_08_000001_add_product_extended_fields', 7),
(20, '2025_12_08_000100_create_options_table', 8),
(21, '2025_12_08_000110_create_option_values_table', 8),
(22, '2025_12_08_000120_create_variant_values_table', 8),
(23, '2025_12_08_000130_alter_variants_add_extended_fields', 8),
(24, '2025_12_08_000140_create_option_sets_table', 9),
(25, '2025_12_08_000150_create_option_set_options_table', 9),
(26, '2025_12_08_000200_create_skus_table', 10),
(27, '2025_12_08_000210_create_sku_values_table', 10),
(28, '2025_12_08_000220_drop_option_tables', 11),
(29, '2025_12_08_000230_rename_skus_to_product_variants', 12),
(30, '2025_12_08_000240_rename_sku_values_to_variant_attributes', 12),
(31, '2025_12_08_000250_create_product_attributes_tables', 13),
(32, '2025_12_08_000260_create_product_variant_options_table', 14),
(33, '2025_12_08_000270_alter_product_variants_add_columns', 14),
(34, '2025_12_08_000280_migrate_variant_foreign_keys_to_product_variants', 15),
(35, '2025_12_08_000290_drop_legacy_variant_tables', 15),
(36, '2025_12_08_000260_add_type_to_product_attributes_table', 16),
(37, '2025_12_08_001000_update_product_images_variant_fk', 17),
(38, '2025_12_09_000300_add_article_to_products_table', 18),
(39, '2025_12_09_001000_drop_best_price_from_products', 19),
(40, '2025_12_09_001100_create_collections_table', 20),
(41, '2025_12_09_001200_add_seo_to_collections', 21),
(42, '2025_12_09_001300_create_collection_images_table', 22),
(43, '2025_12_09_001400_add_image_url_to_collections', 23),
(44, '2025_12_14_000500_add_shipping_mode_to_products', 24),
(45, '2025_12_14_001000_add_quantity_to_products', 25),
(46, '2025_12_14_001100_add_product_sku_to_products', 26),
(47, '2025_12_14_001300_add_seo_and_image_to_categories', 27),
(48, '2025_12_15_000100_create_warehouses_table', 28),
(49, '2025_12_15_000110_create_warehouse_inventories_table', 28),
(50, '2025_12_15_000120_create_stock_movements_table', 28),
(51, '2025_12_17_044023_add_is_guest_to_users_table', 29),
(52, '2025_12_17_045311_add_shipping_columns_to_orders_table', 30),
(53, '2025_12_18_103000_add_view_count_to_products_table', 31),
(54, '2025_12_18_123410_add_payment_status_to_orders_table', 32),
(55, '2025_12_18_123950_remove_district_from_tables', 33),
(56, '2025_12_19_000000_add_profile_fields_to_users_table', 34),
(57, '2025_12_19_091252_create_post_categories_table', 35),
(58, '2025_12_19_100815_create_posts_table', 36),
(59, '2025_12_19_100831_create_post_tags_table', 36),
(60, '2025_12_19_100949_create_post_post_tag_table', 36),
(61, '2025_12_20_024842_create_post_comments_table', 37),
(62, '2025_12_20_115549_create_reviews_table', 38),
(63, '2025_12_21_053950_add_is_featured_to_products_table', 39),
(64, '2025_12_23_000001_create_size_charts_table', 40),
(65, '2025_12_23_000002_create_customer_measurements_table', 40),
(66, '2025_12_23_000003_create_wishlists_table', 40),
(67, '2025_12_25_103307_add_parent_id_to_reviews_table', 41),
(68, '2026_01_01_151516_add_video_url_to_products_table', 42),
(69, '2026_01_01_163922_create_testimonials_table', 43),
(70, '2026_01_05_000100_create_volume_pricings_table', 44),
(71, '2026_01_05_000200_create_promotion_rules_table', 44),
(72, '2026_01_05_000300_create_combos_and_combo_lines_tables', 44),
(73, '2026_01_05_000400_alter_promotion_rules_add_code_columns', 45),
(74, '2026_01_06_000200_add_free_shipping_to_combos', 46),
(75, '2026_01_06_000210_add_free_shipping_flags_to_pricing_and_rules', 47),
(76, '2026_01_06_000220_add_product_variant_id_to_combo_lines', 48),
(77, '2026_01_06_000100_update_combo_and_pricing_to_variants', 49),
(78, '2026_01_06_000230_update_combo_lines_unique_to_variant', 50),
(79, '2026_01_06_000240_add_free_shipping_flags_to_combo_pricing_rules', 50),
(80, '2026_01_07_000310_alter_product_variants_price_precision', 51);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `coupon_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_ward` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `snapshot_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `snapshot_sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `snapshot_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `snapshot_size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `author_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('published','draft','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `views` bigint UNSIGNED NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `thumbnail`, `excerpt`, `content`, `author_id`, `category_id`, `status`, `views`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'MỞ RỘNG TRẢI NGHIỆM THỜI TRANG DÀNH CHO MỌI KHÁCH HÀNG', 'mo-rong-trai-nghiem-thoi-trang-danh-cho-moi-khach-hang', 'posts/2025/12/20/z7220268566990-43ee8553742d58f85e39cc96fda354b4-1200x646.jpg', 'Hãy cùng Elise khám phá những điểm đến thời trang mới mẻ, nơi bạn có thể cảm nhận trọn vẹn tinh thần tinh tế, tính ứng dụng và phong cách sống đậm chất Elise trong từng sản phẩm.', '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(102, 102, 102);\">Elise tiếp tục đánh dấu bước phát triển mạnh mẽ trong hành trình nâng tầm trải nghiệm khách hàng với nhiều showroom mới được khai trương trên toàn quốc, mang đến cho khách hàng cơ hội tiếp cận những thiết kế thời trang mới nhất của Elise trong một không gian sang trọng, hiện đại hơn.</span></p><p><br></p><p><img src=\"http://localhost:8000/storage/products/2025/12/20/z7220268566990_43ee8553742d58f85e39cc96fda354b4-1200x646.jpg\"></p><p class=\"ql-align-center\"><em style=\"background-color: rgb(255, 255, 255); color: rgb(102, 102, 102);\">Không gian Showroom 178 Hai Bà Trưng, Phường Tân Định, TP. HCM</em></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(102, 102, 102);\">Tọa lạc tại các tuyến phố lớn, khu trung tâm đông đúc và những vị trí đắc địa là ưu điểm lớn giúp Elise được nhiều khách hàng ghé đến, trải nghiệm và lựa chọn sản phẩm. Tại mỗi không gian mới, Elise mang đến không gian tinh tế, tối ưu và hiện đại tạo nên hành trình mua sắm trọn vẹn cho mọi khách hàng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(102, 102, 102);\"><img src=\"https://elise.vn/wp/wp-content/uploads/2025/12/z7220268529391_4fbcefeab1b8d33be417e1659180222b-1200x900.jpg\" height=\"900\" width=\"1200\"></span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(102, 102, 102);\">Sự ra mắt của loạt showroom mới lần này cũng đánh dấu nỗ lực không ngừng của Elise trong việc mở rộng hệ sinh thái thời trang và nâng cao chất lượng phục vụ, mang các thiết kế cao cấp đến gần hơn với mọi tín đồ thời trang trên khắp cả nước.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(102, 102, 102);\"><img src=\"https://elise.vn/wp/wp-content/uploads/2025/12/z7220268529395_f8d0d7d51c7a882028cc085ba5465648-1200x900.jpg\" height=\"900\" width=\"1200\"></span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(102, 102, 102);\"><img src=\"https://elise.vn/wp/wp-content/uploads/2025/12/z7220268566934_f60d40ac5bcc77e89c7f59063743af9d-1200x900.jpg\" height=\"900\" width=\"1200\"></span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(102, 102, 102);\">Hãy cùng Elise khám phá những điểm đến thời trang mới mẻ, nơi bạn có thể cảm nhận trọn vẹn tinh thần tinh tế, tính ứng dụng và phong cách sống đậm chất Elise trong từng sản phẩm.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(102, 102, 102);\">Tham khảo danh sách những Showroom Elise mới khai trương:</span></p>', 4, NULL, 'published', 0, '2025-12-19 18:42:37', '2025-12-19 18:42:37', '2026-01-07 03:19:52', '2026-01-07 03:19:52'),
(2, '4 công thức layer áo gile đáng thử mùa Đông 2025', '4-cong-thuc-layer-ao-gile-dang-thu-mua-dong-2025', 'posts/2025/12/25/getty-images.webp', 'Đây là mùa của những cách mặc linh hoạt: cài kín, buông mở, layer chồng lớp – mỗi lựa chọn đều mở ra một hướng thể hiện khác nhau để cá tính người mặc được bộc lộ rõ ràng và tự nhiên.', '<p><span style=\"color: rgb(0, 0, 0);\">Từ vị trí của một món đồ bổ trợ, gile đang dần được đặt vào trung tâm bản phối mùa Đông này: gọn gàng, linh hoạt và đủ sức tạo điểm nhấn cho tổng thể trang phục. Gừ vị trí của một món đồ bổ trợ, gile đang dần được đặt vào trung tâm bản phối mùa Đông này: gọn gàng, linh hoạt và đủ sức tạo điểm nhấn cho tổng thể trang phục. Gile mùa Thu–Đông năm nay mang diện mạo phóng khoáng hơn ở phom dáng, dứt khoát trong cấu trúc và giàu ngẫu hứng ở cách xử lý chất liệu. Len, dạ, tweed hay da được đặt cạnh nhau để tạo nên cảm giác đối lập có chủ ý, cân bằng giữa cổ điển và hiện đại. Đây là mùa của những cách mặc linh hoạt: cài kín, buông mở, layer chồng lớp – mỗi lựa chọn đều mở ra một hướng thể hiện khác nhau để cá tính người mặc được bộc lộ rõ ràng và tự nhiên.&nbsp;</span></p><h2><span style=\"color: rgb(0, 0, 0);\">Áo gile trở lại mạnh mẽ trong cuộc chơi đa lớp&nbsp;</span></h2><p><span style=\"color: rgb(0, 0, 0);\">Khi xu hướng đa lớp lên ngôi, gile không chỉ đóng vai trò lớp trung gian mà còn biến hóa thành điểm nhấn ấn tượng, tạo chiều sâu cho tổng thể trang phục. Trên sàn diễn Thu–Đông, áo gile được các nhà mốt khai thác như một lớp trung gian giàu dụng ý: gile len được xếp chồng lên sơ mi oversized để tạo độ tương phản phom dáng, gile tweed đi cùng váy midi mềm rủ nhằm làm dịu cấu trúc cổ điển, trong khi phiên bản denim được đặt dưới</span><a href=\"https://www.elle.vn/xu-huong-thoi-trang/ao-khoac-dang-dai-ton-chi-cho-thoi-trang-mua-thu-duoc-dien-nhu-the-nao/\" rel=\"noopener noreferrer\" target=\"_blank\" style=\"color: inherit; background-color: initial;\">&nbsp;trench coat dáng dài</a><span style=\"color: rgb(0, 0, 0);\">, mang lại cảm giác phóng khoáng và hiện đại.&nbsp;</span></p><p><br></p><p class=\"ql-align-center\"><span style=\"background-color: rgb(244, 244, 244); color: rgb(77, 77, 77);\"><img src=\"https://static.elle.vn/img/JeZyeQ0Fg-bHbqXPlT8EWIbkdvA_elHwH9iH35fgl2E/rs:fit:0:0/min-height:300/plain/http://www.elle.vn/app/uploads/2025/12/06/718260/max-mara-fall-winter-2025-collection-the-untamed-heroine-look-7-1200x1500-1-1024x1280.jpg@webp\" alt=\"Gile tông nâu mang hơi ấm vintage giữa sàn runway thời trang. \" height=\"1280\" width=\"1024\">Áo khoác chần bông dáng dài màu nâu được mặc bên trong lớp áo khoác gile lông cừu dáng dài ton-sur-ton. (Ảnh: MaxMara)</span></p><p class=\"ql-align-center\"><span style=\"background-color: rgb(244, 244, 244); color: rgb(77, 77, 77);\"><img src=\"https://static.elle.vn/img/iDZTlKrsZC6jhKBko30LvxFKigEVWHvJp8jllYiMJTQ/rs:fit:0:0/min-height:300/plain/http://www.elle.vn/app/uploads/2025/12/15/718260/Getty-Images.avif@webp\" alt=\"Áo gile - món phụ kiện Thu-Đông vừa giữ ấm vừa thời trang. \" height=\"1488\" width=\"992\">Ảnh: Getty Images</span></p><p><br></p><h2><span style=\"color: rgb(0, 0, 0);\">Áo phao gile + áo len oversize</span></h2><p><span style=\"color: rgb(0, 0, 0);\">Áo phao gile kết hợp áo len mang đến cách mặc linh hoạt cho những ngày lạnh vừa, khi bạn cần giữ ấm nhưng vẫn muốn tổng thể trông nhẹ nhàng. Với bản phối ba lớp, áo thun được đặt trong cùng để tạo nền thoải mái, tiếp đến là cardigan hoặc áo len mỏng giúp giữ phom và thêm chiều dày, cuối cùng là áo phao gile đóng vai trò lớp ngoài bảo vệ. Quần jeans tối màu đơn giản giúp cân bằng phần thân trên nhiều lớp, giữ cho outfit không bị rối mà vẫn dễ ứng dụng hằng ngày.</span></p><p class=\"ql-align-center\"><span style=\"background-color: rgb(244, 244, 244); color: rgb(77, 77, 77);\"><img src=\"https://static.elle.vn/img/rmY9-K_TrHKsDu-aD-lPP6HRxQFZZR5-7DeRGe1LeqE/rs:fit:0:0/min-height:300/plain/http://www.elle.vn/app/uploads/2025/12/15/718260/7cc70eb1c699f507af4b6ee7b79694a0-1024x1280.jpg@webp\" alt=\"Layering áo phao gile cùng áo cardigan màu vàng pastel \" height=\"1280\" width=\"1024\">Ảnh: @RitaPerskaya</span></p><p><span style=\"color: rgb(0, 0, 0);\">Nếu ưu tiên sự gọn gàng, cách layer hai lớp là lựa chọn hiệu quả hơn: áo len cổ lọ oversize mặc bên trong kết hợp cùng áo phao gile bên ngoài. Phom rộng của áo len tạo cảm giác ấm áp và thư thái, trong khi gile giúp tổng thể trông chắc chắn hơn. Khi đi cùng quần lửng và boots cao cổ, outfit chuyển sang tinh thần thời trang rõ nét, vừa có cấu trúc, vừa đủ phóng khoáng cho mùa lạnh.</span></p><p class=\"ql-align-center\"><span style=\"background-color: rgb(244, 244, 244); color: rgb(77, 77, 77);\"><img src=\"https://static.elle.vn/img/_tClh_C8PSfdPNX-8kRqdt1frvWae5qQtZ21GA5X3yw/rs:fit:0:0/min-height:300/plain/http://www.elle.vn/app/uploads/2025/12/15/718260/6587e218430fdd75985b50e5badb5aa5-1024x1536.jpg@webp\" alt=\"Layer áo gile phao đen cùng áo len cổ cao màu trắng \" height=\"1536\" width=\"1024\">Layer áo gile phao đen cùng áo len cổ cao màu trắng – màu sắc đối ngược càng tăng thêm vẻ độc đáo cho outfit. (Ảnh: Getty Images)</span></p><h2><span style=\"color: rgb(0, 0, 0);\">Gile len dáng lửng + chân váy bút chì + boots cao cổ</span></h2><p><span style=\"color: rgb(0, 0, 0);\">Gile len dáng lửng là lựa chọn lý tưởng để tạo điểm nhấn cho những bản phối mùa lạnh nhờ khả năng định hình tỉ lệ cơ thể một cách rõ ràng. Khi kết hợp cùng chân váy bút chì và boots cổ cao, tổng thể vừa giữ được sự gọn gàng cần thiết, vừa mở ra nhiều sắc thái phong cách khác nhau. Với chân</span><a href=\"https://www.elle.vn/xu-huong-thoi-trang/chan-vay-but-chi-cong-so-2025/\" rel=\"noopener noreferrer\" target=\"_blank\" style=\"color: inherit; background-color: initial;\">&nbsp;váy bút chì</a><span style=\"color: rgb(0, 0, 0);\">&nbsp;ngắn, outfit mang tinh thần trẻ trung và linh hoạt hơn, phần boots cổ cao đóng vai trò cân bằng độ dài, giúp đôi chân trông thon gọn và mạnh mẽ.</span></p><p class=\"ql-align-center\"><span style=\"background-color: rgb(244, 244, 244); color: rgb(77, 77, 77);\"><img src=\"https://static.elle.vn/img/NjTEGdMADEjeTKGR_J24PzEZe3cb6nMXrRu7zMk1nXM/rs:fit:0:0/min-height:300/plain/http://www.elle.vn/app/uploads/2025/12/15/718260/chaleco-cambridge-rojo-mod-aw25-eseoese_l.jpg@webp\" alt=\"Mix&amp;Match áo len ghile cùng chân váy bút chì chuẩn style công sở \" height=\"1500\" width=\"1000\">Ảnh: eseOese</span></p><p><span style=\"color: rgb(0, 0, 0);\">Trong khi đó, khi chuyển sang chân váy bút chì dài, gile dáng lửng tiếp tục phát huy ưu thế ở phần thân trên, tạo cảm giác cao ráo, còn boots cổ cao gót nâng tổng thể theo hướng nữ tính và sắc nét hơn. Cùng một công thức nền, chỉ cần thay đổi độ dài váy và kiểu giày, gile len đã đủ sức thích ứng với nhiều bối cảnh và cá tính khác nhau.</span></p><p class=\"ql-align-center\"><span style=\"background-color: rgb(244, 244, 244); color: rgb(77, 77, 77);\"><img src=\"https://static.elle.vn/img/MqLF10omRsdkwiWQGo6GfPUnsDt_jNLw2HEI67z3lHM/rs:fit:0:0/min-height:300/plain/http://www.elle.vn/app/uploads/2025/12/02/718260/SnapInsta-Ai_3773730981396884945-1024x1280.jpg@webp\" alt=\"Ghi lê tweed ca rô trên nền váy len dáng dài\" height=\"1280\" width=\"1024\">Ảnh: @bazaaruk</span></p><p><br></p>', 4, NULL, 'published', 13, '2025-12-24 19:22:43', '2025-12-24 19:22:43', '2026-01-07 03:19:50', '2026-01-07 03:19:50'),
(3, 'Các kiểu đồ bơi nữ kín đáo: 5 lựa chọn thanh lịch, tự tin', 'cac-kieu-do-boi-nu-kin-dao-5-lua-chon-thanh-lich-tu-tin', 'posts/2025/12/25/cac-kieu-do-boi-nu-kin-dao-6082-768x576.jpg', 'Không phải ai cũng cảm thấy thoải mái với những mẫu đồ bơi quá táo bạo hay ôm sát cơ thể. Nếu bạn đang tìm kiếm các kiểu đồ bơi nữ kín đáo mà vẫn đẹp và hiện đại, bài viết này là dành cho bạn.', '<p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Không phải ai cũng cảm thấy thoải mái với những mẫu đồ bơi quá táo bạo hay ôm sát cơ thể. Nếu bạn đang tìm kiếm các kiểu đồ bơi nữ kín đáo mà vẫn đẹp và hiện đại, bài viết này là dành cho&nbsp;bạn.&nbsp;sẽ gợi&nbsp;ý những thiết kế vừa thanh lịch, vừa giúp bạn tự tin khi xuống nước. Dù đi biển hay đi hồ bơi, bạn vẫn có thể thoải mái vận động và giữ được sự tinh tế. Cùng khám phá ngay nhé.</span></p><h2 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đồ Bơi Nữ Kín Đáo – Xu Hướng Thời Trang Biển Của Nàng Hiện Đại</span></h2><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bạn không cần phải hở nhiều để trở nên cuốn hút. Một bộ đồ bơi kín đáo, vừa vặn sẽ giúp bạn tự tin, thoải mái và thể hiện cá tính một cách tinh tế. Lựa chọn này không chỉ giúp che khuyết điểm nhẹ nhàng mà còn phù hợp với mọi hoạt động dưới nước. Phụ nữ hiện đại ngày càng ưu tiên cảm giác an toàn và thẩm mỹ trong từng chi tiết trang phục.</span></p><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://n7media.coolmate.me/image/August2025/cac-kieu-do-boi-nu-kin-dao-5-lua-chon-thanh-lich-tu-tin-6082_466.jpg\" alt=\"Đồ bơi kín đáo là tuyên ngôn tự tin của nàng thời nay\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đồ bơi kín đáo là tuyên ngôn tự tin của nàng thời nay</em></p><p><br></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://mcdn.coolmate.me/image/March2025/ao-drop-arm-chay-nu-mesh-tank-31-den_37.jpg\" alt=\"Áo Drop Arm Chạy bộ nữ Mesh Tank\"></span></p><p><br></p><h2 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Khám Phá Các Kiểu Đồ Bơi Nữ Kín Đáo Phổ Biến Nhất Hiện Nay</span></h2><h3 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">1. Đồ Bơi Liền Thân Kín Đáo</span></h3><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bạn chắc chắn sẽ thấy tự tin hơn khi diện một bộ đồ bơi liền thân ôm sát cơ thể. Kiểu dáng này giúp che khuyết điểm vùng bụng và tạo cảm giác vóc dáng thon gọn. Những chi tiết như khoét lưng nhẹ, chiết eo hay chất vải co giãn càng tăng thêm phần nữ tính. Đây là lựa chọn lý tưởng nếu bạn muốn vừa kín đáo vừa dễ vận động. Đặc biệt, bạn có thể diện nó thoải mái khi bơi lội hoặc chơi thể thao trên bãi biển.</span></p><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://n7media.coolmate.me/image/August2025/cac-kieu-do-boi-nu-kin-dao-5-lua-chon-thanh-lich-tu-tin-6082_121.jpg\" alt=\"Đồ bơi liền thân giúp che bụng và tôn dáng cho mọi vóc người\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đồ bơi liền thân giúp che bụng và tôn dáng cho mọi vóc người</em></p><h3 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">2. Đồ Bơi Kín Đáo Tay Dài</span></h3><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Nếu bạn muốn bảo vệ làn da khỏi nắng gắt, đồ bơi tay dài chính là lựa chọn tuyệt vời. Thiết kế này mang đến vẻ ngoài thể thao, cá tính và rất phù hợp với người thích vận động. Ngoài việc che bắp tay khéo léo, đồ bơi tay dài còn thường được tích hợp khả năng chống tia UV hiệu quả. Bạn sẽ cảm thấy thoải mái và an tâm dù ở dưới nắng suốt cả buổi. Đây là kiểu đồ bơi vừa kín đáo vừa có tính ứng dụng cao.</span></p><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://n7media.coolmate.me/image/August2025/cac-kieu-do-boi-nu-kin-dao-5-lua-chon-thanh-lich-tu-tin-6082_329.jpg\" alt=\"Đồ bơi tay dài lý tưởng cho người sợ nắng và thích vận động ngoài trời\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đồ bơi tay dài lý tưởng cho người sợ nắng và thích vận động ngoài trời</em></p><h3 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">3. Tankini</span></h3><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Tankini mang đến sự linh hoạt tối đa khi bạn cần mặc đồ bơi mà vẫn muốn kín đáo. Bạn sẽ có một chiếc áo dáng tank top kết hợp cùng quần bơi, vừa che bụng tốt lại cực kỳ tiện lợi. Kiểu dáng này dễ phối và dễ thay đồ, đặc biệt phù hợp khi đi du lịch biển nhiều ngày. Bạn hoàn toàn có thể chọn kiểu&nbsp;</span><span style=\"background-color: transparent; color: rgb(47, 90, 207);\">quần short nữ</span><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">, váy hoặc tam&nbsp;giác tùy sở thích. Tankini là lựa chọn rất thực tế nhưng không hề kém phần thời trang.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://n7media.coolmate.me/image/August2025/cac-kieu-do-boi-nu-kin-dao-5-lua-chon-thanh-lich-tu-tin-6082_274.jpg\" alt=\"Tankini kín đáo, dễ mặc và linh hoạt cho mọi hoàn cảnh đi biển\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Tankini kín đáo, dễ mặc và linh hoạt cho mọi hoàn cảnh đi biển</em></p><p><br></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://mcdn.coolmate.me/image/March2025/quan-short-the-thao-nu-french-terry-3.5-inch-03631-be_15.jpg\" alt=\"Quần Shorts thể thao nữ French Terry 3.5inch\"></span></p><p><br></p><h3 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">4. Đồ Bơi Dáng Váy</span></h3><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bạn sẽ trở nên duyên dáng và nữ tính hơn khi mặc đồ bơi dáng váy xòe. Thiết kế này giúp che đi vùng hông và đùi rất hiệu quả, nhất là với dáng người quả lê. Với màu sắc tươi sáng hoặc họa tiết hoa, bạn có thể vừa bơi vừa thoải mái dạo biển. Dáng váy chữ A cũng giúp tôn vòng eo và tạo hiệu ứng chân dài hơn. Đây là lựa chọn an toàn mà vẫn cuốn hút trong mắt người đối diện.</span></p><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://n7media.coolmate.me/image/August2025/cac-kieu-do-boi-nu-kin-dao-5-lua-chon-thanh-lich-tu-tin-6082_179.jpg\" alt=\"Đồ bơi dáng váy giúp che khuyết điểm đùi và tôn nét nữ tính nhẹ nhàng\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đồ bơi dáng váy giúp che khuyết điểm đùi và tôn nét nữ tính nhẹ nhàng</em></p><h3 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">5. Bikini Kín Đáo Cạp Cao/Kèm Quần Short</span></h3><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bạn vẫn có thể mặc bikini một cách kín đáo nếu chọn kiểu có quần cạp cao hoặc dáng short. Phần quần giúp che bụng dưới và mang lại cảm giác an tâm khi vận động. Kết hợp&nbsp;với&nbsp;</span><span style=\"background-color: transparent; color: rgb(47, 90, 207);\">áo croptop nữ</span><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;hoặc&nbsp;</span><span style=\"background-color: transparent; color: rgb(47, 90, 207);\">bralette</span><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;sẽ tạo nên tổng thể cân đối và hiện đại. Bạn có thể tự tin tắm nắng, chơi thể thao mà không sợ lộ hàng. Đây là phong cách trẻ trung cho những ai muốn thử sức với bikini nhưng vẫn còn e dè.</span></p><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://n7media.coolmate.me/image/August2025/cac-kieu-do-boi-nu-kin-dao-5-lua-chon-thanh-lich-tu-tin-6082_284.jpg\" alt=\"Bikini cạp cao là lựa chọn lý tưởng cho những ai muốn vừa kín vừa tôn dáng\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bikini cạp cao là lựa chọn lý tưởng cho những ai muốn vừa kín vừa tôn dáng</em></p><h2 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bí Kíp Lựa Chọn Đồ Bơi Nữ Kín Đáo Vừa Vặn</span></h2><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đồ bơi kín đáo ngày càng được nhiều chị em lựa chọn nhờ khả năng che phủ tốt mà vẫn đảm bảo thẩm mỹ. Để mặc đẹp và cảm thấy thoải mái, bạn hãy ghi nhớ vài lưu ý dưới đây trước khi mua.</span></p><ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Chọn dáng đồ bơi tôn vóc dáng:&nbsp;Hãy ưu tiên những thiết kế tạo hiệu ứng thon gọn như chiết eo, cổ chữ V, dáng suông hoặc váy nhẹ. Form dáng phù hợp sẽ giúp bạn tự tin hơn dù ở bất kỳ nơi đâu.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Ưu tiên chất liệu co giãn và thoáng mát:&nbsp;Vải có thành phần&nbsp;</span><span style=\"background-color: transparent; color: rgb(47, 90, 207);\">Spandex</span><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;hoặc&nbsp;</span><span style=\"background-color: transparent; color: rgb(47, 90, 207);\">Polyester</span><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;giúp đồ bơi ôm&nbsp;sát, không thấm nước và nhanh khô. Chất liệu tốt cũng góp phần giữ form đồ lâu dài.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Chú ý đến màu sắc và họa tiết:&nbsp;Gam màu tối hoặc trung tính luôn là lựa chọn an toàn giúp cơ thể trông gọn gàng hơn. Họa tiết nhỏ, đơn giản sẽ dễ phối và phù hợp với nhiều hoàn cảnh.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Chọn size vừa vặn, không quá chật hoặc quá rộng:&nbsp;Một bộ đồ bơi vừa khít giúp bạn di chuyển linh hoạt và giữ được sự kín đáo. Hãy đo đúng số đo cơ thể và xem kỹ bảng size trước khi mua.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Ưu tiên tính năng bảo vệ da:&nbsp;Một số dòng đồ bơi có khả năng chống tia UV hoặc kháng clo, giúp bảo vệ làn da và tăng tuổi thọ sản phẩm. Đây là điểm cộng lớn nếu bạn bơi thường xuyên ngoài trời.</span></li></ol><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://n7media.coolmate.me/image/August2025/cac-kieu-do-boi-nu-kin-dao-5-lua-chon-thanh-lich-tu-tin-6082_900.jpg\" alt=\"Đồ bơi kín đáo không hề nhàm chán nếu bạn chọn đúng với phong cách của mình\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đồ bơi kín đáo không hề nhàm chán nếu bạn chọn đúng với phong cách của mình</em></p><p><br></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://mcdn.coolmate.me/image/March2025/bra-coolflex-everyday-active-587-den-1_34.jpg\" alt=\"Áo Bra CoolFlex Everyday Trắng (Lỗi Nhỏ Mác Ép)\"></span></p><p><br></p><h2 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Giải Đáp Thắc Mắc Thường Gặp Về Đồ Bơi Nữ Kín Đáo</span></h2><h3 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(67, 67, 67);\">Mặc Đồ Bơi Kín Đáo Có Bị Lỗi Thời Không?</span></h3><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Hoàn toàn không. Đồ bơi kín đáo hiện nay rất đa dạng về kiểu dáng, từ cổ điển thanh lịch đến hiện đại, trẻ trung. Đây là xu hướng được nhiều phụ nữ hiện đại lựa chọn vì sự tinh tế, thoải mái và bảo vệ da.</span></p><h3 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(67, 67, 67);\">Kiểu Đồ Bơi Kín Đáo Nào Che Bụng Mỡ Tốt Nhất?</span></h3><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đồ bơi liền thân có chiết eo, Tankini và bikini cạp cao là ba lựa chọn hàng đầu để che khuyết điểm vòng hai. Chúng giúp tạo hiệu ứng eo thon và bụng phẳng một cách tự nhiên.</span></p><h3 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(67, 67, 67);\">Làm Sao Để Đồ Bơi Bền Màu Và Không Bị Giãn?</span></h3><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Hãy giặt đồ bơi bằng tay với nước lạnh ngay sau khi sử dụng, tránh dùng chất tẩy mạnh và không phơi trực tiếp dưới ánh nắng gay gắt. Lựa chọn chất liệu có khả năng kháng clo cũng giúp sản phẩm bền hơn đáng kể.</span></p><h2 class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Lời Kết</span></h2><p class=\"ql-align-justify\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Hy vọng các kiểu đồ bơi nữ kín đáo trong bài viết đã giúp bạn có thêm lựa chọn phù hợp với phong cách riêng. Đồ bơi không cần quá hở mới đẹp, chỉ cần vừa vặn, kín đáo và hợp dáng là bạn đã đủ nổi bật. Đừng ngần ngại chọn thiết kế khiến bạn thấy thoải mái&nbsp;nhất.&nbsp;</span><span style=\"background-color: transparent; color: rgb(47, 90, 207);\">Coolblog</span><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;sẽ còn nhiều gợi ý thời trang bơi lội khác đang chờ bạn khám phá. Nhớ theo dõi để không bỏ lỡ nhé.</span></p><p><br></p><p>nguồn : coolmate</p>', 4, NULL, 'published', 1, '2025-12-24 19:29:10', '2025-12-24 19:29:10', '2026-01-07 03:19:48', '2026-01-07 03:19:48'),
(4, 'Phối Đồ Quần Legging Nơi Công Sở: Thanh Lịch, Tránh Lỗi Kém', 'phoi-do-quan-legging-noi-cong-so-thanh-lich-tranh-loi-kem', 'posts/2025/12/25/phoi-do-quan-legging-noi-cong-so-thanh-lich-tranh-loi-kem-duyen-6057-768x576.jpg', 'Quần legging vốn được yêu thích bởi sự thoải mái, nhưng lại thường bị e ngại sẽ làm mất đi vẻ chuyên nghiệp nơi công sở. Tuy nhiên, mọi định kiến sẽ hoàn toàn được xóa bỏ khi bạn nắm vững nghệ thuật phối đồ tinh tế.', '<p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Quần legging vốn được yêu thích bởi sự thoải mái, nhưng lại thường bị e ngại sẽ làm mất đi vẻ chuyên nghiệp nơi công sở. Tuy nhiên, mọi định kiến sẽ hoàn toàn được xóa bỏ khi bạn nắm vững nghệ thuật phối đồ tinh tế.&nbsp;sẽ đồng hành cùng bạn gợi ý những bí quyết then chốt từ cách chọn một chiếc legging chuẩn mực cho đến những công thức kết hợp đầy thời thượng.&nbsp;</span></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Bí quyết chọn quần legging chuẩn cho môi trường công sở</strong></h2><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Trước khi nghĩ đến việc phối đồ, việc chọn đúng chiếc quần legging là yếu tố quyết định đến 90% sự thành công của bộ trang phục.</span></p><h3 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">1. Chất liệu và độ dày</span></h3><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Để legging trở nên lịch sự, chất liệu là yếu tố quyết định tất cả. Một chiếc quần legging công sở bắt buộc phải có chất liệu dày dặn và đứng form. Hãy ưu tiên các loại vải như:</span></p><ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Ponte di Roma:</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;Một loại vải dệt kim đôi rất dày dặn, bền, ít nhăn và có khả năng che khuyết điểm cực tốt.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cotton Umi:&nbsp;</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Vải có bề mặt mịn, co giãn tốt nhưng vẫn giữ được phom dáng, không bị bai dão sau nhiều lần giặt.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Scuba:&nbsp;</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chất vải có cấu trúc, bề mặt trơn láng, tạo cảm giác sang trọng, gần giống quần tây nhưng co giãn và thoải mái hơn.</span></li></ol><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Lợi ích lớn nhất của các chất liệu này là giúp tránh xuyên thấu và không lộ vùng nhạy cảm hai lỗi trang phục chí mạng nơi công sở. Hãy nói không với những chiếc legging tập gym mỏng, chất vải bóng hoặc quá co giãn, bởi chúng sẽ phá vỡ hoàn toàn sự chuyên nghiệp cần có.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/July2025/phoi-do-voi-quan-legging-noi-cong-so-1.jpg\" alt=\"Cận cảnh chất liệu vải Ponte di Roma dày dặn, che khuyết điểm tốt\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cận cảnh chất liệu vải Ponte di Roma dày dặn, che khuyết điểm tốt</em></p><p><br></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://mcdn.coolmate.me/image/March2025/legging-coolflex-everyday-active-4-den_77.jpg\" alt=\"Legging Coolflex Everyday Active\"></span></p><h3 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">2. Màu sắc và kiểu dáng</span></h3><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Với môi trường văn phòng một chiếc legging thanh lịch nằm ở sự đơn giản trong cả màu sắc và kiểu dáng. Hãy ưu tiên những gam màu trung tính kinh điển như đen, navy, xám đậm để đảm bảo vẻ ngoài chuyên nghiệp, dễ phối đồ và tôn lên vóc dáng.&nbsp;</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Về thiết kế, phom dáng trơn là lựa chọn tối ưu, nhưng một đường gân nổi chạy dọc thân quần sẽ là điểm nhấn đắt giá, khéo léo tạo hiệu ứng chân dài và mang lại cảm giác của một chiếc tregging thời thượng. Trong khi đó, những chi tiết như họa tiết sặc sỡ, cut-out hay phối lưới cần được tiết chế hoàn toàn để giữ trọn vẹn nét trang trọng cho bộ trang phục.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/July2025/phoi-do-voi-quan-legging-noi-cong-so-2.jpg\" alt=\"Ưu tiên những gam màu trung tính để dễ phối đồ và tôn dáng\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Ưu tiên những gam màu trung tính để dễ phối đồ và tôn dáng</em></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Phối đồ quần legging nơi công sở thanh lịch và chuyên nghiệp</strong></h2><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Vốn bị mặc định là trang phục thiếu chỉn chu, quần legging thường bị “cấm cửa” tại môi trường công sở. Tuy nhiên, chỉ cần nắm vững vài quy tắc phối đồ thông minh, bạn hoàn toàn có thể biến chúng thành lựa chọn vừa thanh lịch, vừa chuyên nghiệp mà vẫn đảm bảo sự thoải mái tối đa.</span></p><h3 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">1. Nguyên tắc vàng phối đồ&nbsp;</span></h3><ol><li data-list=\"ordered\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Che chắn khéo léo:</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;Đây là quy tắc bất di bất dịch. Áo mặc cùng legging phải có độ dài tối thiểu qua hông, lý tưởng nhất là che phủ toàn bộ vòng ba. Điều này không chỉ đảm bảo sự kín đáo, lịch sự mà còn giúp bạn tự tin tuyệt đối trong mọi hoạt động.</span></li><li data-list=\"ordered\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cân bằng tỷ lệ:&nbsp;</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Quần đã ôm (bottom-fit) thì áo nên rộng (top-loose). Hãy kết hợp legging với áo dáng suông, oversized hoặc các loại áo khoác có cấu trúc. Việc này tạo ra sự hài hòa, cân đối cho tổng thể, tránh cảm giác bó chặt từ trên xuống dưới.</span></li><li data-list=\"ordered\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chất liệu và màu sắc ăn ý:</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;Hãy tạo ra một tổng thể có gu. Ví dụ, một chiếc legging chất Ponte dày dặn sẽ rất ăn ý với áo blazer vải tuytsi hoặc áo len cashmere, thay vì một chiếc áo voan mỏng manh thiếu sự liên kết.</span></li></ol><h3 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">2. Gợi ý phối đồ chi tiết với quần legging cho nàng công sở</span></h3><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Dưới đây là 1 số cách phối giúp bạn trông thật thời trang nơi công sở:</span></p><h4 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Quần legging với áo sơ mi dáng dài/Oversized</span></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Sự kết hợp này mang đến phong cách đầy phong cách,&nbsp;nơi vẻ đẹp hiện đại, phóng khoáng được cân bằng một cách hoàn hảo bởi nét thanh lịch vốn có. Một vạt áo buông hờ tạo nên sự uyển chuyển cho mỗi bước đi, trong khi đó, một chút biến tấu với kiểu sơ vin vạt trước lại là nét chấm phá duyên dáng, vừa tạo điểm nhấn cho tổng thể, vừa khéo léo thu hút ánh nhìn vào vòng eo thon gọn.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/July2025/phoi-do-voi-quan-legging-noi-cong-so-3.jpg\" alt=\"Sự kết hợp đầy phong cách hiện đại&nbsp;\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Sự kết hợp đầy phong cách hiện đại&nbsp;</em></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://mcdn.coolmate.me/image/March2025/legging-yoga-ribbed-33292-den_35.jpg\" alt=\"Quần Legging Ribbed\"></span></p><p><br></p><h4 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Quần legging với áo Blazer/Vest</span></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Kết hợp legging và blazer tạo nên một bộ đồ công sở vừa chỉn chu vừa thời thượng. Bạn có thể mặc cùng áo thun đơn giản, áo hai dây hoặc áo blouse mỏng bên trong. Nếu cần một chiếc&nbsp;</span><span style=\"color: rgb(47, 90, 207); background-color: transparent;\">áo thun</span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;chất lượng, sản phẩm của Coolmate với chất vải mềm mịn, giữ phom tốt chính là gợi ý hoàn hảo.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/July2025/phoi-do-voi-quan-legging-noi-cong-so-4.jpg\" alt=\"Combo này tạo vừa chỉnh chu mà vừa thời thượng\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Combo này tạo vừa chỉnh chu mà vừa thời thượng</em></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://mcdn.coolmate.me/image/March2025/ao-thun-nu-chay-bo-core-tee-slimfit-1-den.jpg\" alt=\"Áo thun nữ chạy bộ Core Tee Slim\"></span></p><p><br></p><h4 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Quần legging với áo len dáng dài/Tunic</span></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Một lựa chọn ấm áp và dịu dàng cho những ngày se lạnh. Phối đồ quần legging mùa đông cho dân văn phòng với áo len dáng dài hoặc áo tunic (kiểu áo chẽn dài qua hông) vừa thoải mái, mềm mại lại có khả năng che phủ rất tốt.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/July2025/phoi-do-voi-quan-legging-noi-cong-so-5.jpg\" alt=\"Lựa chọn hoàn hảo cho những ngày se lạnh\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Lựa chọn hoàn hảo cho những ngày se lạnh</em></p><p><br></p><h4 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Quần legging với Cardigan/Trench Coat dáng dài</span></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Đây chính là bí quyết layering thông minh, giúp bạn nâng tầm set đồ một cách ngoạn mục. Một chiếc cardigan hay trench coat dáng dài không chỉ làm tròn nhiệm vụ giữ ấm mà còn là chìa khóa tạo nên vẻ ngoài sang trọng, chỉn chu. Hơn thế nữa, lớp áo khoác này còn khéo léo tạo ra một đường thẳng dọc cơ thể, giúp vóc dáng trông cao ráo và thời thượng hơn hẳn.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/July2025/phoi-do-voi-quan-legging-noi-cong-so-6.jpg\" alt=\"Set đồ ấy tạo nên 1 vẻ ngoài sang trọng\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Set đồ ấy tạo nên 1 vẻ ngoài sang trọng</em></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Hoàn thiện set đồ với giày dép và phụ kiện&nbsp;</strong></h2><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chính những chi tiết tưởng chừng nhỏ bé này mới là yếu tố chốt hạ, quyết định sự thành công cho toàn bộ diện mạo của bạn.</span></p><h3 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">1. Lựa chọn giày dép chuẩn với legging nơi văn phòng</span></h3><ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nên chọn:&nbsp;</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Giày cao gót, ankle boots, loafer (giày lười), hoặc ballet flats (giày búp bê). Những lựa chọn này giúp hack dáng, kéo dài đôi chân và ngay lập tức cộng thêm điểm thanh lịch, chuyên nghiệp.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nên tránh:&nbsp;</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Giày thể thao hầm hố, dép lê, xăng đan. Chúng phá vỡ không khí chuyên nghiệp của môi trường công sở. Một đôi sneaker da tối giản có thể là ngoại lệ nếu văn hóa công ty bạn thực sự thoải mái theo phong cách smart-casual.</span></li></ol><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/July2025/phoi-do-voi-quan-legging-noi-cong-so-7_31.jpg\" alt=\"Chọn giày cao gót để trông thật thanh lịch\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chọn giày cao gót để trông thật thanh lịch</em></p><h3 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">2. Phụ Kiện</span></h3><ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Túi xách:&nbsp;</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Ưu tiên túi có phom dáng cứng cáp, màu trung tính để tăng vẻ chuyên nghiệp.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Trang sức:&nbsp;</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chọn các loại trang sức mảnh, nhỏ xinh như dây chuyền, khuyên tai, đồng hồ để tạo điểm nhấn tinh tế.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Thắt lưng:&nbsp;</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Một chiếc thắt lưng mảnh vắt ngoài áo oversized hoặc áo khoác là mẹo hay để định hình vòng eo.</span></li></ol><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/July2025/phoi-do-voi-quan-legging-noi-cong-so-8.jpg\" alt=\"Ưu tiên những túi có phom dáng cứng cáp\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Ưu tiên những túi có phom dáng cứng cáp</em></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Những điều tối kỵ khi mặc quần legging&nbsp;</strong></h2><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Hãy ghi nhớ những quy tắc bất thành văn dưới đây để đảm bảo bạn luôn xuất hiện một cách chuyên nghiệp và tinh tế nhất.</span></p><h3 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">1. Lỗi Chọn Legging</span></h3><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Đây là lỗi phản cảm và thiếu kín đáo nhất. Để lộ nội y dưới lớp quần legging mỏng là điều cấm kỵ tuyệt đối. Tương tự, những chiếc legging bóng, có họa tiết rực rỡ mang “DNA” của phòng tập và hoàn toàn không phù hợp với môi trường đòi hỏi sự nghiêm túc.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/July2025/phoi-do-voi-quan-legging-noi-cong-so-9.jpg\" alt=\"Tránh chọn những chiếc quần quá mỏng gây phản cảm\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Tránh chọn những chiếc quần quá mỏng gây phản cảm</em></p><p><br></p><h3 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">2. Lỗi phối đồ</span></h3><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Một nguyên tắc bất biến khi diện legging nơi công sở chính là đảm bảo sự che phủ tinh tế cho vòng ba. Vì vậy, việc kết hợp chúng với áo croptop hay những chiếc áo quá ngắn là điều tối kỵ, có thể phá vỡ hoàn toàn vẻ ngoài chuyên nghiệp. Cần ghi nhớ rằng legging không phải là một chiếc quần tây độc lập có thể đứng riêng trong một bộ trang phục công sở.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/July2025/phoi-do-voi-quan-legging-noi-cong-so-10.jpg\" alt=\"Không kết hợp với những chiếc áo ngắn gây vẻ ngoài thiếu chuyên nghiệp\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Không kết hợp với những chiếc áo ngắn gây vẻ ngoài thiếu chuyên nghiệp</em></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Giải đáp thắc mắc thường gặp&nbsp;</strong></h2><h3 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Quần legging có thể thay thế quần tây không?&nbsp;</span></h3><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Không. Legging có cấu trúc mềm và ôm sát, không thể thay thế phom dáng đứng đắn của quần tây. Chúng luôn cần một lớp áo đủ dài để che phủ.</span></p><h3 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Làm sao để chọn quần legging không lộ vùng nhạy cảm?&nbsp;</span></h3><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Có hai bí quyết chính: chọn chất liệu thật dày dặn (như Ponte, Scuba) và luôn mặc áo dài qua hông.</span></p><h3 class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Phối legging với giày thể thao đi làm được không?&nbsp;</span></h3><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nên hạn chế. Nếu văn hóa công ty cho phép phong cách smart-casual tự do, bạn có thể chọn một đôi sneaker da tối giản. Tuy nhiên, loafer hay ankle boots vẫn là lựa chọn an toàn và thanh lịch hơn.</span></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Lời kết</strong></h2><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Với những bí quyết trên, legging hoàn toàn có thể trở thành người bạn đồng hành tuyệt vời, mang đến sự thoải mái mà vẫn giữ trọn nét thanh lịch, chuyên nghiệp. Hãy tự tin thử nghiệm để tìm ra phong cách legging công sở của riêng mình. Đừng quên theo dõi&nbsp;</span><a href=\"https://www.coolmate.me/blog/\" rel=\"noopener noreferrer\" target=\"_blank\" style=\"color: rgb(47, 90, 207); background-color: transparent;\">CoolBog</a><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;để theo dõi thêm nhiều bài viết hay về thời trang nhé!</span></p>', 4, NULL, 'published', 20, '2025-12-24 19:33:51', '2025-12-24 19:33:51', '2026-01-07 03:19:45', '2026-01-07 03:19:45');
INSERT INTO `posts` (`id`, `title`, `slug`, `thumbnail`, `excerpt`, `content`, `author_id`, `category_id`, `status`, `views`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 'Những Mẫu Áo Kiểu Đẹp Hot Nhất 2025: Chọn & Phối Chuẩn Gu', 'nhung-mau-ao-kieu-dep-hot-nhat-2025-chon-phoi-chuan-gu', 'posts/2025/12/25/nhung-mau-ao-kieu-dep-hot-nhat-2025-chon-phoi-chuan-gu-5481-1-768x576.jpg', 'Tủ đồ đầy ắp nhưng vẫn cảm thấy thiếu một chiếc áo vừa điệu đà lại vừa thoải mái để thay đổi phong cách thời trang? Đừng lo, áo kiểu chính là trang phục giúp bạn F5 bản thân cực nhanh, lại vô cùng đa dạng và luôn hợp mốt', '<p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Tủ đồ đầy ắp nhưng vẫn cảm thấy thiếu một chiếc áo vừa điệu đà lại vừa thoải mái để thay đổi phong cách thời trang? Đừng lo, áo kiểu chính là trang phục giúp bạn F5 bản thân cực nhanh, lại vô cùng đa dạng và luôn hợp mốt. Bài viết dưới dây,&nbsp;sẽ chia sẻ về thế giới áo kiểu nữ, đồng thời nắm vững bí kíp chọn áo cho từng vóc dáng, độ tuổi. Cùng tìm hiểu những gợi ý hay ho nhé!</span></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Áo kiểu là gì? Khám phá thế giới đa dạng của items này</strong></h2><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Áo kiểu khác áo cơ bản như thế nào?</strong></h2><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Khác với những chiếc áo basic như áo thun trơn hay sơ mi trắng cổ đức vốn tập trung vào sự tối giản và tính ứng dụng cao, áo kiểu hay áo cách điệu lại là sân chơi của sự sáng tạo. Điểm khác biệt lớn nhất nằm ở kiểu dáng và các chi tiết nhấn nhá.</span></p><p class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu thường có những đặc trưng sau:</strong></p><ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cách điệu ở cổ áo</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Thay vì cổ tròn, cổ bẻ thông thường, áo kiểu có thể là cổ vuông, cổ V sâu, cổ yếm, cổ bèo, cổ nơ, cổ trụ…</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cách điệu ở tay áo</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Tay phồng, tay loe, tay cánh tiên, tay lỡ cut-out, tay dài phối ren… tạo nên sự đa dạng và nét riêng.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Phom dáng đa dạng</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Không chỉ có dáng suông, ôm cơ bản, áo kiểu còn có dáng croptop, peplum, bất đối xứng.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chất liệu phong phú</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Bên cạnh cotton, thun quen thuộc, áo kiểu thường sử dụng các chất liệu đa dạng hơn như voan, lụa, ren, đũi, linen để tạo hiệu ứng thẩm mỹ khác nhau</span></li></ol><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-1.jpg\" alt=\"Áo kiểu có nhiều điểm khác biệt so với áo thun\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu có nhiều điểm khác biệt so với áo thun</em></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Vì sao áo kiểu được yêu thích?</strong></h2><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Không phải ngẫu nhiên mà áo kiểu lại trở thành item “must-have” trong tủ đồ của phái đẹp. Sức hút của chúng đến từ:</span></p><ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Khả năng tôn dáng, che khuyết điểm</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Nhờ thiết kế đa dạng, áo kiểu có thể giúp bạn khoe khéo ưu điểm hoặc giấu đi nhược điểm một cách tinh tế.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Thể hiện phong cách cá nhân</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Từ nữ tính, điệu đà đến thanh lịch, cá tính hay quyến rũ, luôn có một kiểu áo phù hợp để bạn thể hiện chất riêng của mình.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Phù hợp nhiều dịp mặc</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Cùng một chiếc áo kiểu, chỉ cần thay đổi cách phối đồ là bạn có thể tự tin diện đi làm, đi chơi, hẹn hò hay dự tiệc nhẹ.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Mang lại sự mới mẻ</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Áo kiểu là cách đơn giản và nhanh chóng nhất để làm mới những set đồ cơ bản, tránh sự nhàm chán trong phong cách hàng ngày.</span></li></ol><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-2.jpg\" alt=\"Áo kiểu có thể sử dụng trong nhiều hoàn cảnh khác nhau\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu có thể sử dụng trong nhiều hoàn cảnh khác nhau</em></p><p><br></p><p><br></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://mcdn.coolmate.me/image/April2025/ao-tee-ribbed-712-be.jpg\" alt=\"Áo Tee Ribbed\"></span></p><p><br></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Top những mẫu áo kiểu đẹp và thịnh hành nhất 2025</strong></h2><h3 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu theo thiết kế tay áo</strong></h3><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">1. Áo kiểu tay phồng</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Mẫu áo này được thiết kế phồng ở vai, giữa tay hoặc cổ tay vẫn chưa hề hạ nhiệt. Kiểu áo này mang hơi hướng cổ điển pha lẫn hiện đại, vừa giúp che khuyết điểm bắp tay to, vừa tạo vẻ nữ tính, yêu kiều hoặc sang trọng tùy vào độ phồng và chất liệu. Từ kiểu phồng nhẹ nhàng đến phồng lớn ấn tượng, áo kiểu tay phồng dễ dàng kết hợp với nhiều item khác nhau.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-3.jpg\" alt=\"Kiểu áo tay phồng điệu đà\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Kiểu áo tay phồng điệu đà</em></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">2. Áo kiểu tay lỡ/tay dài cách điệu</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Không chỉ dừng lại ở tay phồng, các mẫu áo kiểu tay lỡ hoặc tay dài cũng được biến tấu đa dạng. Bạn có thể bắt gặp kiểu tay loe nhẹ nhàng, tay xếp ly tinh tế, tay cut-out cá tính hay tay phối viền độc đáo. Đây là lựa chọn tuyệt vời cho các nàng công sở yêu thích sự thanh lịch, kín đáo mà vẫn muốn thể hiện gu thời trang tinh tế.&nbsp;</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-4.jpg\" alt=\"Áo kiểu tay lỡ\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu tay lỡ</em></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">3. Áo kiểu sát nách/2 dây</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Khi hè về, áo sát nách và áo 2 dây là những item không thể thiếu. Mang đến sự mát mẻ, trẻ trung và một chút gợi cảm, các kiểu dáng này rất đa dạng: từ dây bản to năng động, dây mảnh quyến rũ đến các kiểu dây buộc, dây xích phá cách. Bạn có thể diện chúng một mình hoặc khoác ngoài sơ mi, blazer để tạo layer cá tính.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-6.jpg\" alt=\"Mẫu áo kiểu sát nách\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Mẫu áo kiểu sát nách</em></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">4. Áo kiểu tay cánh tiên</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Đúng như tên gọi, áo cánh tiên có phần tay áo được may xòe rộng, rũ nhẹ như cánh tiên, tạo cảm giác bay bổng, mềm mại và cực kỳ nữ tính. Kiểu tay này cũng là “vũ khí” lợi hại giúp che khuyết điểm phần vai và bắp tay thô, mang lại vẻ ngoài dịu dàng, thanh thoát.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-5.jpg\" alt=\"Áo kiểu tay cánh tiên\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu tay cánh tiên</em></p><h3 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu theo thiết kế cổ áo</strong></h3><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">1. Áo kiểu cổ vuông</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo cổ vuông mang vẻ đẹp giao thoa giữa cổ điển và hiện đại. Thiết kế này giúp khoe trọn phần xương quai xanh mảnh mai một cách tinh tế, tạo cảm giác cổ cao và thanh thoát hơn, đồng thời cân đối bờ vai. Đây là kiểu dáng khá dễ mặc, phù hợp với nhiều dáng mặt và phong cách khác nhau.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-7.jpg\" alt=\"Áo kiểu cổ vuông\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu cổ vuông</em></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">2. Áo kiểu cổ V/cổ tim</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu cổ V và cổ tim là lựa chọn kinh điển để tạo hiệu ứng thon gọn cho phần thân trên và “ăn gian” chiều dài cổ. Tùy vào độ sâu của cổ áo mà bạn có thể biến hóa phong cách từ thanh lịch, tinh tế đến quyến rũ, gợi cảm. Chỉ cần lưu ý chọn độ sâu phù hợp với hoàn cảnh là bạn đã có thể tự tin tỏa sáng.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-8.jpg\" alt=\"Áo kiểu cổ chữ V\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu cổ chữ V</em></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">3. Áo kiểu cổ yếm</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Mang đậm cảm hứng từ trang phục truyền thống, áo kiểu cổ yếm với phần dây buộc hoặc cài sau gáy giúp khoe trọn bờ vai trần nuột nà đầy gợi cảm. Đây là lựa chọn hoàn hảo cho những chuyến đi biển, những buổi hẹn hò lãng mạn hay những bữa tiệc tối cần sự nổi bật.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-9.jpg\" alt=\"Áo kiểu cổ yếm\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu cổ yếm</em></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">4. Áo kiểu cổ bèo/cổ nơ</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chi tiết bèo nhún điệu đà hay chiếc nơ xinh xắn thắt ở cổ luôn là điểm nhấn đắt giá, giúp chiếc áo kiểu trở nên mềm mại, nữ tính hơn. Áo kiểu cổ bèo hay áo kiểu phối nơ có thể mang đến vẻ ngoài bánh bèo, ngọt ngào hoặc thanh lịch, sang trọng tùy thuộc vào kích thước, kiểu dáng bèo/nơ và chất liệu vải.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-10.jpg\" alt=\"Áo kiểu cổ bèo\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu cổ bèo</em></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">5. Áo kiểu cổ trụ/cổ tàu</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu cổ tàu với phần cổ đứng ôm gọn lấy cổ mang đến vẻ đẹp kín đáo, thanh lịch và có chút gì đó hoài cổ. Kiểu cổ này thường xuất hiện trên áo sơ mi cách điệu hoặc áo blouse, rất phù hợp với môi trường công sở đòi hỏi sự chỉn chu hoặc những ai theo đuổi phong cách tối giản, tinh tế.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-11.jpg\" alt=\"Áo kiểu cổ trụ/cổ tàu\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu cổ trụ/cổ tàu</em></p><h3 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu theo kiểu dáng thân &amp; chi tiết đặc biệt</strong></h3><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">1. Áo kiểu croptop</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo croptop với độ dài trên eo hoặc ngang eo vẫn luôn là item được yêu thích bởi sự trẻ trung, năng động và khả năng tôn dáng tuyệt vời. Kiểu áo này giúp khoe khéo vòng eo thon gọn, hack chiều cao hiệu quả. Có vô vàn biến tấu của áo kiểu croptop: từ dáng ôm, dáng rộng, sơ mi croptop đến croptop có các chi tiết cut-out, buộc dây…</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-12.jpg\" alt=\"Áo kiểu croptop\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu croptop</em></p><h4><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">2. Áo kiểu Peplum</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo peplum là cứu tinh cho những cô nàng muốn tạo hiệu ứng eo thon và che đi phần bụng dưới chưa hoàn hảo. Đặc điểm nhận dạng là phần eo được chiết lại và có một lớp vải xòe nhẹ bên dưới. Kiểu dáng này mang đến vẻ ngoài thanh lịch, sang trọng, rất phù hợp cho môi trường công sở hoặc những buổi tiệc nhẹ.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-13.jpg\" alt=\"Áo kiểu peplum\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu peplum</em></p><h4><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">3. Áo kiểu đính đá</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Muốn tăng thêm phần lấp lánh và sang trọng cho set đồ, đặc biệt là khi dự tiệc, áo đính đá là một gợi ý không tồi. Các chi tiết đá có thể được đính tập trung ở cổ áo, vai, viền tay hoặc rải rác trên thân áo, tạo điểm nhấn nổi bật và thu hút ánh nhìn.&nbsp;</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-14.jpg\" alt=\"Áo kiểu đính đá\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu đính đá</em></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">4. Áo kiểu phối ren/voan</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Sự kết hợp của chất liệu ren hoặc vải voan luôn mang đến vẻ nữ tính, quyến rũ và bay bổng cho chiếc áo kiểu. Ren/voan có thể được phối ở phần tay áo, vai, lưng hoặc tạo thành các lớp layer mềm mại, tăng thêm sự tinh tế và thu hút cho trang phục.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-15.jpg\" alt=\"Áo kiểu phối ren/voan\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu phối ren/voan</em></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">5. Áo kiểu bất đối xứng</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nếu bạn yêu thích sự phá cách và độc đáo, đừng bỏ qua những chiếc áo kiểu có thiết kế bất đối xứng. Đó có thể là kiểu vạt high-low, vạt chéo, lệch vai… Những đường cắt cúp táo bạo này sẽ giúp set đồ của bạn trở nên ấn tượng và thể hiện cá tính thời trang riêng biệt.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-16.jpg\" alt=\"Áo kiểu bất đối xứng\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu bất đối xứng</em></p><p><br></p><p><span style=\"background-color: rgb(241, 241, 241); color: rgb(0, 0, 0);\">Tham khảo thêm&nbsp;</span><a href=\"https://www.coolmate.me/collection/quan-nu\" rel=\"noopener noreferrer\" target=\"_blank\" style=\"background-color: transparent; color: rgb(47, 90, 207);\">BST quần nữ</a><span style=\"background-color: rgb(241, 241, 241); color: rgb(0, 0, 0);\">&nbsp;chất lượng từ Coolmate</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://mcdn.coolmate.me/image/March2025/biker-shorts-nu-yoga-8inch-coolflex-light-support-25-den-1_26.jpg\" alt=\"Quần Biker Shorts nữ 8 Inch CoolFlex Light Support\"></span></p><p><br></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Chọn áo kiểu đẹp theo vóc dáng và độ tuổi</strong></h2><h3 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chọn áo kiểu tôn dáng</strong></h3><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Người gầy</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nên chọn áo có chi tiết tạo độ phồng cùng form áo suông nhẹ và họa tiết sáng màu, họa tiết ngang hoặc họa tiết lớn. Tránh chọn áo quá mỏng, quá ôm sát cơ thể, màu tối trơn.</span></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Người đầy đặn/mũm mĩm</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Bạn nên chọn áo kiểu cổ V, áo peplum có tay lỡ hoặc tay dài. Ngoài ra, chú ý chọn chất liệu mềm mại cùng màu tối hoặc họa tiết sọc dọc nhỏ. Đừng nên chọn áo quá bó sát, áo quá rộng hay chất liệu vải bóng, cứng với chi tiết bèo nhún, túi hộp tập trung ở vùng eo, bụng, ngực nhé!</span></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Người cân đối</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Kiểu người này khá dễ mặc đồ, bạn chỉ cần tập trung chọn những chiếc áo giúp tôn lên ưu điểm của bạn. Quan trọng nhất là sự hài hòa tổng thể và phù hợp với phong cách cá nhân.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-17.jpg\" alt=\"Những mẫu áo kiểu đẹp theo dáng người\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Những mẫu áo kiểu đẹp theo dáng người</em></p><h3 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chọn áo kiểu phù hợp độ tuổi</strong></h3><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Tuổi teen/U25</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Hãy ưu tiên các kiểu như croptop, áo 2 dây, tay phồng nhỏ với phong cách trẻ trung, năng động. Đặc biệt ưu tiên màu sắc tươi sáng và sự thoải mái phù hợp lứa tuổi nhé!</span></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">U25 – U35</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Hãy chọn những chiếc áo có sự thanh lịch, hiện đại, chuyên nghiệp nhưng vẫn giữ được nét trẻ trung. Đây là độ tuổi cần sự linh hoạt giữa trang phục công sở và đi chơi. Áo sơ mi kiểu, áo blouse cách điệu, áo peplum, các kiểu cổ V, cổ vuông tinh tế với những gam màu trang nhã cho môi trường làm việc.</span></p><h4 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">U35+</strong></h4><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Ưu tiên: Sự sang trọng, quý phái, tinh tế. Tập trung vào chất liệu cao cấp và kiểu dáng không quá cầu kỳ nhưng có điểm nhấn, giúp che khuyết điểm tuổi tác một cách khéo léo. Màu sắc nên đằm thắm, trang nhã. Tránh các kiểu quá ngắn, quá hở hang nhé!</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-18.jpg\" alt=\"Áo kiểu theo độ tuổi cũng có kiểu dáng khác nhau\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu theo độ tuổi cũng có kiểu dáng khác nhau</em></p><h3 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chọn áo kiểu theo chất liệu yêu thích và phù hợp</strong></h3><ol><li data-list=\"ordered\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Vải voan, lụa</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Mỏng, nhẹ, mềm mại, có độ rũ và thường có độ bóng nhẹ. Mang đến vẻ nữ tính, bay bổng, sang trọng. Thích hợp cho những dịp cần sự điệu đà, lãng mạn như hẹn hò, dự tiệc nhẹ. Tuy nhiên, cần lưu ý giặt tay và dễ nhăn.</span></li><li data-list=\"ordered\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Vải cotton, thun</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Thấm hút mồ hôi tốt, co giãn, thoáng mát, mang lại cảm giác thoải mái, dễ chịu tối đa. Đây là lựa chọn tuyệt vời cho trang phục hàng ngày, đi chơi, vận động. Dễ giặt, dễ bảo quản.</span></li><li data-list=\"ordered\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Vải ren</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Tạo vẻ quyến rũ, tinh tế với các họa tiết đục lỗ đặc trưng. Thường được dùng làm điểm nhấn hoặc toàn bộ áo cho những dịp đặc biệt, tiệc tùng. Cần chọn loại ren mềm mại, có lớp lót để tránh gây khó chịu cho da.</span></li><li data-list=\"ordered\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Vải đũi, linen</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Có nguồn gốc tự nhiên, bề mặt vải thường hơi thô, xốp, đặc biệt thoáng mát và thấm hút tốt. Mang đến phong cách tự nhiên, mộc mạc, hơi hướng vintage. Rất hợp cho mùa hè, đi du lịch. Nhược điểm là khá dễ nhăn.</span></li></ol><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-19.jpg\" alt=\"Chất liệu khác nhau mang đến tính năng khác nhau cho áo kiểu\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chất liệu khác nhau mang đến tính năng khác nhau cho áo kiểu</em></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Phối đồ sáng tạo với áo kiểu đẹp</strong></h2><h3 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Phối đồ cho từng hoàn cảnh</strong></h3><ol><li data-list=\"ordered\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Thanh lịch chốn công sở:</strong></li></ol><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Hãy chọn áo sơ mi kiểu/áo blouse cổ V/cổ đức/cổ trụ cách điệu + quần tây ống đứng/ống suông/ống rộng hoặc chân váy bút chì/chữ A. Đặc biệt chọn màu sắc trang nhã, chất liệu lịch sự. Phụ kiện tối giản, giày cao gót hoặc giày bệt mũi nhọn.</span></p><ol><li data-list=\"ordered\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Năng động, thoải mái khi đi chơi/dạo phố</strong></li></ol><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Ưu tiên sự thoải mái, dễ vận động cùng áo kiểu croptop/áo 2 dây/áo thun kiểu/áo tay phồng + quần jeans/quần short/chân váy ngắn với họa tiết và màu sắc tùy sở thích. Bạn có thể chọn phụ kiện năng động như sneakers, mũ lưỡi trai, túi tote…</span></p><ol><li data-list=\"ordered\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Sang trọng, cuốn hút khi dự tiệc</strong></li></ol><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Đây là nơi cần đến sự xuất hiện của những mẫu áo kiểu đính đá, phối ren hoặc chất liệu cao cấp như lụa, satin kết hợp cùng chân váy điệu đà như midi, maxi lụa hay xếp ly. Bổ sung thêm phụ kiện lấp lánh như clutch, trang sức, giày cao gót là không thể thiếu.</span></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Gợi ý phối áo kiểu với các items cơ bản</strong></h2><h3 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Với quần jeans/quần ống rộng</strong></h3><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Hãy chọn áo kiểu cùng quần jeans tạo vẻ thanh lịch nhưng vẫn cá tính. Hoặc áo kiểu với quần ống rộng mang đến sự thoải mái, phóng khoáng và thời thượng. Nên chọn áo có độ ôm vừa phải hoặc sơ vin để cân đối tỉ lệ.&nbsp;</span></p><h3 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Với chân váy</strong></h3><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Tùy theo phong cách của bạn để chọn sự kết hợp áo kiểu với chân váy bút chì, midi hay váy xòe. Mỗi kiểu chân váy sẽ mang đến hình ảnh của sự thanh lịch, nữ tính hay sự trẻ trung. Nếu áo đã cầu kỳ thì nên chọn chân váy đơn giản và ngược lại.</span></p><h3 class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Với quần short/quần culottes</strong></h3><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Bạn có thể chọn áo kiểu cùng quần short cho mùa hè nóng nực hoặc những chuyến du lịch biển. Còn nếu muốn tạo dáng vẻ ngoài hiện đại, thoải mái và thanh lịch thì kết hợp cùng quần culottes sẽ là lựa chọn tối ưu.</span></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-20.jpg\" alt=\"Vài item cơ bản với áo kiểu đẹp\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Vài item cơ bản với áo kiểu đẹp</em></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Bảo quản áo kiểu để luôn bền đẹp</strong></h2><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Để chiếc áo kiểu yêu thích luôn như mới, bạn cần chú ý một chút khâu bảo quản:</span></p><ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Giặt</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Luôn đọc kỹ tag hướng dẫn giặt. Phân loại áo theo màu sắc và chất liệu. Ưu tiên giặt tay hoặc cho vào túi giặt khi giặt máy đối với các loại vải mỏng manh như voan, lụa, ren. Dùng nước lạnh hoặc ấm nhẹ.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Phơi</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Tránh phơi trực tiếp dưới ánh nắng gắt, nhất là với áo màu. Nên phơi trong bóng râm, nơi thoáng gió. Dùng móc treo phù hợp để giữ phom áo, tránh làm giãn vai.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Ủi:</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;Ủi ở nhiệt độ thấp phù hợp với từng loại vải. Nên ủi mặt trái hoặc lót một lớp vải mỏng lên trên đối với lụa, voan, ren hoặc áo có chi tiết đính kết.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cất giữ</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Treo áo bằng móc có đệm vai hoặc gấp gọn gàng trong tủ khô ráo, thoáng mát.</span></li></ol><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-21.jpg\" alt=\"Đọc kỹ hướng dẫn từ nhà sản xuất để giữ áo kiểu bền lâu\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Đọc kỹ hướng dẫn từ nhà sản xuất để giữ áo kiểu bền lâu</em></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Mua sắm áo kiểu đẹp và những items basic phối đồ chất lượng ở đâu?</strong></h2><ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Các thương hiệu thời trang</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Có cửa hàng vật lý và website riêng, đảm bảo chất lượng và mẫu mã cập nhật.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Trung tâm thương mại/Cửa hàng Department Store</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Tập trung nhiều thương hiệu, dễ dàng so sánh và lựa chọn.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Sàn thương mại điện tử</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Đa dạng mẫu mã, giá cả cạnh tranh, nhiều ưu đãi. Cần xem kỹ đánh giá shop và sản phẩm.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Các cửa hàng thời trang thiết kế/shop online uy tín</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">: Thường có phong cách riêng, độc đáo.</span></li></ol><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\"><img src=\"https://n7media.coolmate.me/image/April2025/nhung-mau-ao-kieu-dep-22.jpg\" alt=\"Bạn có thể mua áo kiểu trên các cửa hàng online hoặc offline\" height=\"900\" width=\"1200\"></span></p><p class=\"ql-align-center\"><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Bạn có thể mua áo kiểu trên các cửa hàng online hoặc offline</em></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Các câu hỏi thường gặp</strong></h2><p class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu có thể mặc được trong những dịp nào ngoài đi làm/đi chơi?</strong></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu rất linh hoạt! Ngoài đi làm, đi chơi, bạn có thể mặc áo kiểu đi hẹn hò, dự tiệc nhẹ, đi du lịch, thậm chí một số kiểu đơn giản, thoải mái có thể mặc ở nhà.&nbsp;</span></p><p class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Người thấp/cao nên ưu tiên kiểu áo kiểu nào để tôn dáng</strong></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Người thấp nên chọn áo croptop, áo có độ dài vừa phải. Còn người cao có thể thoải mái thử nhiều kiểu dáng</span></p><p class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Chất liệu nào của áo kiểu giúp thoáng mát, ít nhăn khi di chuyển nhiều?</strong></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Để thoáng mát và hạn chế nhăn, bạn nên ưu tiên các chất liệu như: cotton, modal, tencel, polyester pha.</span></p><p class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu basic là gì, khác áo kiểu cách điệu như thế nào?</strong></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Thông thường, “áo basic” dùng để chỉ những kiểu áo cực kỳ đơn giản, không có chi tiết cách điệu. Còn “áo kiểu” là thuật ngữ chung cho những chiếc áo có thiết kế đặc biệt hơn, có điểm nhấn ở cổ, tay, dáng áo như đã đề cập trong bài.</span></p><p class=\"ql-align-justify\"><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu cho tuổi trung niên có những đặc điểm gì?</strong></p><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Áo kiểu nữ đẹp cho tuổi trung niên thường có kiểu dáng thanh lịch, không quá hở hang, chất liệu ưu tiên loại cao cấp cùng màu sắc trang nhã, đằm thắm.</span></p><h2 class=\"ql-align-justify\"><strong style=\"color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Kết luận</strong></h2><p class=\"ql-align-justify\"><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Vậy là chúng mình đã cùng nhau khám phá từ A-Z thế giới đầy màu sắc của những mẫu áo kiểu đẹp. Rõ ràng, đây là một item cực kỳ linh hoạt, có khả năng biến hóa khôn lường, giúp mọi cô gái dễ dàng làm mới phong cách và thể hiện cá tính riêng. Bạn có thể sắm ngay vài mẫu cho tủ đồ và cùng học thêm nhiều cách phối hơn với những thông tin hay tại&nbsp;</span><span style=\"color: rgb(47, 90, 207); background-color: transparent;\">Coolblog</span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;nhé!</span></p>', 4, NULL, 'published', 58, '2025-12-24 19:38:49', '2025-12-24 19:38:49', '2026-01-07 03:19:39', '2026-01-07 03:19:39'),
(6, 'Cách Pha Cà Phê Phin Ngon Chuẩn Vị – Bí Quyết Từ A–Z', 'cach-pha-ca-phe-phin-ngon-chuan-vi-bi-quyet-tu-az', 'posts/2026/01/07/12-768x1152.jpg', 'Cà phê phin không chỉ là một thức uống, mà còn là một nét văn hóa đặc trưng của người Việt. Giữa muôn vàn cách thưởng thức cà phê hiện đại,&nbsp;pha c...', '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Cà phê phin không chỉ là một thức uống, mà còn là một nét văn hóa đặc trưng của người Việt. Giữa muôn vàn cách thưởng thức cà phê hiện đại,&nbsp;</span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">pha cà phê phin</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;vẫn giữ trọn sức hút bởi sự chậm rãi, đậm đà và hương thơm khó quên. Vậy cách pha cà phê phin ngon chuẩn vị ngay tại nhà như thế nào? Hãy cùng tìm hiểu chi tiết qua bài viết của Quốc Lộc Cà Phê.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Vì sao pha cà phê phin vẫn được ưa chuộng?</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Cà phê ngày càng phổ biến và được yêu thích rộng rãi, với nhiều cách pha và thưởng thức độc đáo khác nhau. Tuy nhiên, cách pha cà phê phin vẫn được nhiều anh em tìm kiếm và lựa chọn, bởi những lý do sau:</span></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đậm đà, nguyên chất:</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;Khác với cà phê pha máy hay cà phê hòa tan, cà phê phin giữ nguyên hương vị rang xay, đậm và dày vị hơn.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Trải nghiệm chậm rãi:</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;Mỗi giọt cà phê rơi xuống phin là một khoảng lặng để bắt đầu ngày mới.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Dễ thực hiện:</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;Chỉ cần phin, cà phê và nước nóng, bạn đã có thể tự tay pha một ly cà phê chuẩn gu.</span></li></ol><p class=\"ql-align-center\"><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\"><img src=\"https://quocloccoffee.com/wp-content/uploads/2025/08/cach_pha_ca_phe_phin.jpg\" alt=\"Cách pha cà phê phin truyền thống\" height=\"960\" width=\"960\"></span><em style=\"background-color: rgba(0, 0, 0, 0.05); color: rgb(0, 0, 0);\">Cách pha cà phê phin truyền thống</em></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Hướng dẫn cách pha cà phê phin ngon chuẩn vị</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Sau đây, Quốc Lộc sẽ hướng dẫn bạn cách pha cà phê phin ngon chuẩn vị, dễ dàng làm được tại nhà.</span></p><h3><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">1. Chuẩn bị nguyên liệu và dụng cụ</strong></h3><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">25g cà phê bột</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;(tương đương 3–4 muỗng cà phê)</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Phin nhôm</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;loại nhỏ (120ml)</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Nước nóng 92–95°C</strong></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Ly, muỗng</strong></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Sữa đặc hoặc đường</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;(tùy khẩu vị)</span></li></ol><h3><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">2. Các bước pha cà phê phin ngon&nbsp;</strong></h3><h4><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bước 1: Tráng phin</strong></h4><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Tráng phin bằng nước sôi để khử mùi lạ, đồng thời giúp giữ nhiệt tốt trong quá trình pha.</span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;</strong></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bước 2: Cho cà phê vào phin</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Cho khoảng 25g bột cà phê vào phin. Gõ nhẹ thành phin để bột rơi đều, giúp chiết xuất đồng đều hơn.</span></p><h4><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bước 3: Ủ cà phê</strong></h4><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Rót chậm khoảng 30ml nước sôi lên mặt cà phê, chờ 1–2 phút để bột nở đều và tỏa hương thơm.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;Bước 4: Rót nước lần hai</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Sau khi cà phê đã nở, châm thêm 50ml nước sôi. Đậy nắp và chờ cà phê nhỏ giọt từ từ. Quá trình này mất khoảng 5–7 phút.</span></p><h4><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bước 5: Thưởng thức</strong></h4><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bạn sẽ thu được khoảng 40ml cà phê phin nguyên chất, đậm đặc. Có thể thêm sữa đặc, đường hoặc đá tùy sở thích.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Mẹo để pha cà phê phin ngon hơn</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bên cạnh hướng dẫn trên, để có cách pha cà phê phin ngon nhất, bạn nên lưu ý những mẹo sau:</span></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Chọn cà phê chất lượng:</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;Hạt rang vừa hoặc rang đậm, xay vừa phải.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Nhiệt độ nước chuẩn:</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;92–95°C để tránh cháy cà phê, giữ hương vị trọn vẹn.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Rót nước chậm:</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;Giúp cà phê ngấm đều, không bị chua hoặc đắng gắt.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Vệ sinh phin sạch:</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;Đảm bảo không có dầu cà phê cũ bám lại.</span></li></ol><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Kết luận</strong></h2><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Cách pha cà phê phin</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;không khó, nhưng đòi hỏi sự tỉ mỉ và kiên nhẫn. Chỉ với vài thao tác đơn giản, bạn đã có thể tự tay làm ra ly cà phê phin thơm ngon, đậm vị ngay tại nhà.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Nếu bạn đang tìm kiếm&nbsp;</span><a href=\"https://www.facebook.com/quocloccoffee\" rel=\"noopener noreferrer\" target=\"_blank\" style=\"background-color: transparent; color: rgb(0, 0, 0);\">cà phê rang xay nguyên chất</a><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;để pha phin, hay một sản phẩm phin pha chất lượng để cho ra những giọt cà phê đậm đà. Hãy tham khảo các sản phẩm tại&nbsp;</span><strong style=\"background-color: transparent; color: rgb(0, 0, 0);\"><a href=\"http://quocloccoffee.com/\" rel=\"noopener noreferrer\" target=\"_blank\">Quốc Lộc Cà Phê</a></strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– nơi mang đến cho bạn gu cà phê nguyên chất chuẩn vị và chất lượng.</span></p><p><br></p>', 4, 12, 'published', 1, '2026-01-07 03:21:07', '2026-01-07 03:21:07', '2026-01-07 08:25:10', NULL);
INSERT INTO `posts` (`id`, `title`, `slug`, `thumbnail`, `excerpt`, `content`, `author_id`, `category_id`, `status`, `views`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7, 'Hướng dẫn pha cà phê máy', 'huong-dan-pha-ca-phe-may', 'posts/2026/01/07/448843540-989379869854968-8632098768852613019-n-768x768.jpg', 'Hướng dẫn pha chế cà phê bằng máyNguyên liệu chuẩn bị:Cà phê rang xay min, nguyên chấtĐường/sữa,đá viên loại lớnNước lọc, nước khoáng tinh khiết.Dụng...', '<h2><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Hướng dẫn pha chế cà phê bằng máy</span></h2><p><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nguyên liệu chuẩn bị:</em></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cà phê rang xay min, nguyên chất</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Đường/sữa,đá viên loại lớn</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nước lọc, nước khoáng tinh khiết.</span></li></ol><p><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Dụng cụ:</em></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Máy pha cà phê được vệ sinh, lau chùi sạch sẽ.</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cốc, ly, tách,.. sứ để có thể chịu nhiệt cao.</span></li></ol><p><em style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Các bước pha chế cafe:</em></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Bước 1: Khởi động máy khoảng từ 20 – 30 phút để làm nóng máy và tách dùng trong pha chế. Máy được làm nóng khi ở nhiệt độ 92 độ C, áp suất khoảng 9 bar.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Bước 2: Làm sạch tay pha cà phê, cho khoảng 18 gr cà phê vào group. Dùng ngón tay ấn đều, làm phẳng bề mặt cà phê, không để khe hở trên bề mặt vì có thể khiến cà phê chiết xuất không đều.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Bước 3: Nén cà phê nhẹ lần thứ nhất để cố định mặt phẳng cà phê. Lần thứ hai tăng thêm lực vừa đủ. Thao tác lấy cà phê và nén phải nhanh chóng để tránh tay cầm mất nhiệt quá nhiều khi lấy ra.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Bước 4: Xả nước khoảng 3-5s trước khi lắp tay pha vào máy để làm sạch group head và ổn định nhiệt độ nước.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Bước 5: Chiết xuất ngay khi cho tay pha vào máy để tránh cà phê bị cháy. Sau khoảng 5-8s, cà phê ra những giọt đầu tiên và mất khoảng 25 – 30s để cho ra 35 – 40 ml cà phê pha bằng máy.</span></p>', 4, 12, 'published', 4, '2026-01-07 03:21:46', '2026-01-07 03:21:46', '2026-01-07 17:25:47', NULL),
(8, 'CÁCH PHA CÀ PHÊ PHIN NGUYÊN CHẤT CHUẨN', 'cach-pha-ca-phe-phin-nguyen-chat-chuan', 'posts/2026/01/07/phin.jpg', '1. Cách pha như sau:1.1. Sử dụng nước sạch, đun sôi 92- 96 độ.Lưu ý:Nước đóng chai (tinh khiết)Nước mưa sẽ tốt nhất cho việc pha cà phêNếu sử dụng nướ...', '<p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">1. Cách pha như sau:</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">1.1. Sử dụng nước sạch, đun sôi 92- 96 độ.</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Lưu ý:</strong></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nước đóng chai (tinh khiết)</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nước mưa sẽ tốt nhất cho việc pha cà phê</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nếu sử dụng nước máy: thường nước máy có chất xử lý nhẹ chlorin. Vì vậy chúng ta phải chứa nước máy vào bồn hoặc vật dụng khác từ 3 – 5 ngày mới dùng để nấu pha cà phê được.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nếu là nước giếng: không được nhiễm phèn, nhiễm mặn hoặc các mùi lạ khác.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">1.2. Tráng phin cà phê (tốt nhất là phin nhôm) bằng nước nóng, lau khô và cho khoảng 17-20 gram cà phê vào, lắc nhẹ phin cho phẳng, dùng nắp gài ép nhẹ tay.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Bước 1 (ủ cà phê): Đổ từ từ nước sôi vào trực tiếp khoảng 25-30 ml nước sôi, chủ yếu là để cho Café đủ ướt, đậy nắp lại, không cho hơi nóng thoát ra khỏi phin và để trong vòng 1-3 phút.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Bước 2 (pha cà phê): Chế nước sôi từ từ vào, khoảng 40-45ml nước và đậy nắp lại đợi nước chảy xuống hết là có thể dùng ngay.</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">a. Lưu ý:</strong></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Với cà phê nguyên chất, khi cho nước sôi vào thì sẽ hút nước và nở ra kèm sủi bọt. Chúng ta rót nhẹ tay từ từ cho nước vào vừa đủ, cho đến khi lượng cà phê hút no nước (hay còn gọi là ủ chín cà phê).</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Tuỳ thời gian thưởng thức cà phê mà cho lượng nước nhiều hay ít, thường buổi sáng cho ít nước hơn buổi tối.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Đợi 1-3 phút, sau đó cho lượng nước vừa đủ vào phin, cách này áp dụng cho pha phin lớn, nhỏ đều được.</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">b. Kết quả:</strong></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cà phê xuống đều, lấy được gần hết mùi và vị của cà phê (khi pha phin chắc chắn một điều là mùi và vị cà phê không chiết xuất hết được như pha Espresso của Ý).</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cà phê không bị cặn.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">c. Khắc phục sự cố có thể xảy ra:</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cà phê không chảy:</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Do ép nắp chặn, cách xử lý đừng ép miếng chặn.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Do cà phê xay nhuyễn, cách xử lý dùng miếng chặn hãm bằng cách cho 1/3 dưới miếng chặn và 2/3 trên miếng chặn, pha bình thường.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cà phê chảy nhanh: bạn có thể điều chỉnh bằng cách ấn nắp chèn xuống và ngược lại.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cà phê nở nhiều: nên ủ lâu hơn.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Một ly cà phê ngon sẽ phục vụ bạn sau 5-7 phút. Pha một ít đường hoặc sữa, uống nóng hay đá đều ngon, nhưng theo chúng tôi thì uống nóng cho hương vị đậm đà hơn, hy vọng bạn sẽ có một cảm giác thật tuyệt vời bên ly cà phê của mình.</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cách Pha Phin Lớn</strong></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Cách pha: Cho khoảng 150g bột cà phê vào phin sau đó lắc nhẹ phin để bột cà phê nằm ngang, sau đó dùng 300ml nước sôi đổ vào trong phin và đáy phin. Để khoảng 10 phút cho bột cà phê nở đều, sau đó đổ tiếp khoảng 330ml vào phin. Cà phê rang mộc là loại cà phê rất khô nên háo nước do vậy cần nhiều, khi đổ nước sôi vào phin sẽ xuất hiện bọt sủi rất nhiều, đây chính là cách phân biệt loại cà phê rang mộc với các loại cà phê nguyên chất có hương liệu khác. Khi đổ nước lần 2 vào phin cà phê xong, sau đó chờ cho cà phê nhỏ giọt từ từ, sau khi pha xong bạn sẽ có 250-300ml nước cà phê thơm ngon đến từng giọt. Với phin pha cafe cỡ lớn bạn nên xay cà phê ở cỡ tương đối mịn để có thể cho ra thành phẩm ngon nhất.</span></p>', 4, 12, 'published', 17, '2026-01-07 03:22:36', '2026-01-07 03:22:36', '2026-01-07 17:26:02', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_categories`
--

CREATE TABLE `post_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post_categories`
--

INSERT INTO `post_categories` (`id`, `name`, `slug`, `parent_id`, `description`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(12, 'Mẹo hay', 'meo-hay', NULL, NULL, 0, 1, NULL, NULL, '2026-01-07 03:19:25', '2026-01-07 03:19:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_comments`
--

CREATE TABLE `post_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','spam','trash') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post_comments`
--

INSERT INTO `post_comments` (`id`, `post_id`, `parent_id`, `user_id`, `name`, `email`, `content`, `status`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 8, NULL, 4, 'Văn Minh Đỗ', 'vanminh.do0788@gmail.com', '111111111', 'approved', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-07 03:58:06', '2026-01-07 03:59:12'),
(2, 8, 1, 4, 'Văn Minh Đỗ', 'vanminh.do0788@gmail.com', '1', 'approved', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-07 03:59:12', '2026-01-07 03:59:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_post_tag`
--

CREATE TABLE `post_post_tag` (
  `id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL,
  `post_tag_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post_post_tag`
--

INSERT INTO `post_post_tag` (`id`, `post_id`, `post_tag_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 2, 3, NULL, NULL),
(5, 2, 4, NULL, NULL),
(6, 2, 5, NULL, NULL),
(7, 3, 6, NULL, NULL),
(8, 3, 7, NULL, NULL),
(9, 3, 8, NULL, NULL),
(10, 3, 9, NULL, NULL),
(11, 4, 10, NULL, NULL),
(12, 4, 11, NULL, NULL),
(13, 8, 12, NULL, NULL),
(14, 8, 13, NULL, NULL),
(15, 8, 14, NULL, NULL),
(16, 7, 15, NULL, NULL),
(17, 7, 16, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_tags`
--

CREATE TABLE `post_tags` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post_tags`
--

INSERT INTO `post_tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'thời trang', 'thoi-trang', '2025-12-19 18:42:37', '2025-12-19 18:42:37'),
(2, 'công sở', 'cong-so', '2025-12-19 18:42:37', '2025-12-19 18:42:37'),
(3, 'thời trang nữ', 'thoi-trang-nu', '2025-12-19 18:42:37', '2025-12-19 18:42:37'),
(4, 'áo gile', 'ao-gile', '2025-12-24 19:22:43', '2025-12-24 19:22:43'),
(5, 'coat dáng dài', 'coat-dang-dai', '2025-12-24 19:22:43', '2025-12-24 19:22:43'),
(6, 'đồ bơi nữ', 'do-boi-nu', '2025-12-24 19:30:32', '2025-12-24 19:30:32'),
(7, 'phong cách mặc đẹp', 'phong-cach-mac-dep', '2025-12-24 19:30:32', '2025-12-24 19:30:32'),
(8, 'bikini', 'bikini', '2025-12-24 19:30:32', '2025-12-24 19:30:32'),
(9, 'che khuyết điểm', 'che-khuyet-diem', '2025-12-24 19:30:32', '2025-12-24 19:30:32'),
(10, 'Quần legging', 'quan-legging', '2025-12-24 19:33:51', '2025-12-24 19:33:51'),
(11, 'quần legging công sở', 'quan-legging-cong-so', '2025-12-24 19:33:51', '2025-12-24 19:33:51'),
(12, 'cà phê', 'ca-phe', '2026-01-07 03:24:26', '2026-01-07 03:24:26'),
(13, 'cách sử dụng', 'cach-su-dung', '2026-01-07 03:24:26', '2026-01-07 03:24:26'),
(14, 'cà phê phin', 'ca-phe-phin', '2026-01-07 03:24:26', '2026-01-07 03:24:26'),
(15, 'cà phê rang', 'ca-phe-rang', '2026-01-07 03:26:40', '2026-01-07 03:26:40'),
(16, 'cà phê xay', 'ca-phe-xay', '2026-01-07 03:26:40', '2026-01-07 03:26:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `material` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `article` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `view_count` bigint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `tax` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(12,2) DEFAULT NULL,
  `discounted_price` decimal(12,2) DEFAULT NULL,
  `in_stock` tinyint(1) NOT NULL DEFAULT '1',
  `quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `shipping_weight` decimal(10,2) DEFAULT NULL,
  `shipping_dimensions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_mode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'company',
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_fragile` tinyint(1) NOT NULL DEFAULT '0',
  `is_biodegradable` tinyint(1) NOT NULL DEFAULT '0',
  `is_frozen` tinyint(1) NOT NULL DEFAULT '0',
  `max_temp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `product_sku`, `material`, `description`, `video_url`, `article`, `is_active`, `is_featured`, `view_count`, `created_at`, `updated_at`, `vendor`, `collection`, `category_id`, `status`, `tax`, `price`, `discounted_price`, `in_stock`, `quantity`, `shipping_weight`, `shipping_dimensions`, `shipping_mode`, `payment_method`, `is_fragile`, `is_biodegradable`, `is_frozen`, `max_temp`, `expiry_date`) VALUES
(164, 'INDOCHINE  (100% robusta )', 'indochine-100-robusta', 'PR-R942V', NULL, 'đắng đặm, thơm caramel, cacao, socola', NULL, '<p>🎁 ƯU ĐÃI CHO CHỦ QUÁN</p><p>- Miễn phí 200g cà phê test gu cho khách hàng tỉnh ngoài</p><p>- Đơn từ 12kg: miễn phí giao hàng nội thành Thanh Hóa</p><p>- Đơn từ 25kg: miễn phí giao hàng toàn quốc</p><p>- Đơn từ 25kg: tặng thêm 1kg cà phê (áp dụng cho chủ quán)</p><p><br></p><p>📦 ĐÓNG GÓI &amp; GIA CÔNG</p><p>- Túi 1kg: không tính thêm phí</p><p>- Túi 500g (van 1 chiều): +5.000đ/kg</p><p>- Hỗ trợ xay phin / xay máy miễn phí theo yêu cầu</p><p><br></p><p>💳 CHÍNH SÁCH THANH TOÁN &amp; GIAO HÀNG</p><p>- Khách nội tỉnh Thanh Hóa: thanh toán khi nhận hàng</p><p>- Khách tỉnh ngoài: chuyển khoản trước – giao hàng sau</p><p>- Giao hàng toàn quốc qua GHTK, ViettelPost hoặc đơn vị phù hợp</p><p><br></p><p>📞 LIÊN HỆ MUA HÀNG</p><p>Hoàng Đỗ Roaster</p><p>Fanpage: https://www.facebook.com/hoangdoroaster</p><p>🌐 Website: www.hoangdo.com.vn</p><p>📞 Hotline/Zalo: 0855.541.987</p><p>📦 Địa chỉ Vp: 239 Lạc Long Quân, P. Đông Vệ, TP Thanh Hóa</p>', 1, 1, 1, '2026-01-07 01:27:10', '2026-01-07 02:13:19', NULL, NULL, 19, 'active', 0, 330000.00, NULL, 1, 0, NULL, NULL, 'company', 'all', 0, 0, 0, NULL, NULL),
(165, 'TROPICAL (100% arabica)', 'tropical-100-arabica', 'CA-RB1RR', NULL, 'chua thanh, đắng nhẹ ngọt hậu, thơm trái cây, hoa quả.', NULL, '<p>🎁 ƯU ĐÃI CHO CHỦ QUÁN</p><p>- Miễn phí 200g cà phê test gu cho khách hàng tỉnh ngoài</p><p>- Đơn từ 12kg: miễn phí giao hàng nội thành Thanh Hóa</p><p>- Đơn từ 25kg: miễn phí giao hàng toàn quốc</p><p>- Đơn từ 25kg: tặng thêm 1kg cà phê (áp dụng cho chủ quán)</p><p><br></p><p>📦 ĐÓNG GÓI &amp; GIA CÔNG</p><p>- Túi 1kg: không tính thêm phí</p><p>- Túi 500g (van 1 chiều): +5.000đ/kg</p><p>- Hỗ trợ xay phin / xay máy miễn phí theo yêu cầu</p><p><br></p><p>💳 CHÍNH SÁCH THANH TOÁN &amp; GIAO HÀNG</p><p>- Khách nội tỉnh Thanh Hóa: thanh toán khi nhận hàng</p><p>- Khách tỉnh ngoài: chuyển khoản trước – giao hàng sau</p><p>- Giao hàng toàn quốc qua GHTK, ViettelPost hoặc đơn vị phù hợp</p><p><br></p><p>📞 LIÊN HỆ MUA HÀNG</p><p>Hoàng Đỗ Roaster</p><p>Fanpage: https://www.facebook.com/hoangdoroaster</p><p>🌐 Website: www.hoangdo.com.vn</p><p>📞 Hotline/Zalo: 0855.541.987</p><p>📦 Địa chỉ Vp: 239 Lạc Long Quân, P. Đông Vệ, TP Thanh Hóa</p>', 1, 1, 1, '2026-01-07 01:28:40', '2026-01-07 08:33:12', NULL, NULL, 19, 'active', 0, 500000.00, 49900.00, 1, 0, NULL, NULL, 'company', 'all', 0, 0, 0, NULL, NULL),
(166, 'GOTHIC (90% robusta-10%arabica)', 'gothic-90-robusta-10arabica', 'CA-RCF3S', NULL, 'Thơm nhẹ hoa quả, đậm thanh sự kết hợp đắng đâm, nhẹ nhàng phủ hợp với gu nhẹ nhàng ít đắng.', NULL, '<p>🎁 ƯU ĐÃI CHO CHỦ QUÁN</p><p>- Miễn phí 200g cà phê test gu cho khách hàng tỉnh ngoài</p><p>- Đơn từ 12kg: miễn phí giao hàng nội thành Thanh Hóa</p><p>- Đơn từ 25kg: miễn phí giao hàng toàn quốc</p><p>- Đơn từ 25kg: tặng thêm 1kg cà phê (áp dụng cho chủ quán)</p><p><br></p><p>📦 ĐÓNG GÓI &amp; GIA CÔNG</p><p>- Túi 1kg: không tính thêm phí</p><p>- Túi 500g (van 1 chiều): +5.000đ/kg</p><p>- Hỗ trợ xay phin / xay máy miễn phí theo yêu cầu</p><p><br></p><p>💳 CHÍNH SÁCH THANH TOÁN &amp; GIAO HÀNG</p><p>- Khách nội tỉnh Thanh Hóa: thanh toán khi nhận hàng</p><p>- Khách tỉnh ngoài: chuyển khoản trước – giao hàng sau</p><p>- Giao hàng toàn quốc qua GHTK, ViettelPost hoặc đơn vị phù hợp</p><p><br></p><p>📞 LIÊN HỆ MUA HÀNG</p><p>Hoàng Đỗ Roaster</p><p>Fanpage: https://www.facebook.com/hoangdoroaster</p><p>🌐 Website: www.hoangdo.com.vn</p><p>📞 Hotline/Zalo: 0855.541.987</p><p>📦 Địa chỉ Vp: 239 Lạc Long Quân, P. Đông Vệ, TP Thanh Hóa</p>', 1, 1, 0, '2026-01-07 01:29:44', '2026-01-07 02:13:13', NULL, NULL, 19, 'active', 0, 347000.00, NULL, 1, 0, NULL, NULL, 'company', 'all', 0, 0, 0, NULL, NULL),
(167, 'Máy xay cà phê Macap MX Manual', 'may-xay-ca-phe-macap-mx-manual', 'MA-RH4HP', NULL, 'Máy xay cà phê Macap MX Manual được thiết kế và sản xuất bởi thương hiệu Macap Ý – 1 trong 3 hãng máy xay cà phê có chất lượng tốt nhất Châu Âu, chuyên sử dụng cho các nhà hàng, khách sạn, quán cafe vừa và nhỏ.', NULL, '<h2><strong style=\"color: rgb(75, 16, 9); background-color: rgb(255, 255, 255);\">1. Máy xay cà phê MACAP MX MANUAL</strong></h2><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Macap – Chuyên gia máy xay Châu Âu ra đời vào năm 1930, tập trung vào sản xuất máy xay cà phê và thiết bị cho quầy bar. Sự cải thiện về chất lượng của tất cả các sản phẩm chế biến cà phê, ngày càng được yêu cầu nhiều hơn ở nước ngoài do mức tiêu thụ và văn hóa cà phê ở nước ngoài có tốc độ tăng trưởng rất nhanh. Macap hiện đang làm việc với hơn 70 quốc gia ở nước ngoài, cung cấp nhiều lựa chọn sản phẩm. Điều này rất có ý nghĩa đối với năng lực đổi mới của Macap.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Máy xay cà phê bán tự động Macap MX Manual được thiết kế chuyên nghiệp, phù hợp với không gian quầy bar, tối ưu hóa hiệu suất hoạt động, giảm chi phí cho việc kinh doanh.</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Thông số kỹ thuật:</strong></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Kích thước: 230 x 370 x 600 mm</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Công suất: 340W</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Nguồn điện: 220-240V</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Motor: 14.000 rpm</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Trọng lượng: 11,8 kg</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Lưỡi dao: 65 mm</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Xuất xứ: Italy</span></p><h2><strong style=\"color: rgb(75, 16, 9); background-color: rgb(255, 255, 255);\">2. Ưu điểm vượt trội của MACAP MX MANUAL</strong></h2><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Máy xay cà phê bán tự động Macap M42 Manual được đính kèm nhiều tiện ích thông minh, mang lại sự chuyên nghiệp cho quầy bar.</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">2.1. Macap MX Manual cho chất lượng bột cà phê tốt nhất</strong></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Macap MCX còn có nhiều chế độ điều chỉnh độ mịn cà phê khác nhau, đạt chuẩn về hương vị cho từng loại cà phê giúp cho người sử dụng pha chế đơn giản hơn.</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">2.2 Macap MX Manual thiết kế thông minh</strong></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Phễu nhựa trong suốt đựng hạt cà phê không chỉ để đựng mà còn trưng bày được hạt cà phê vừa đẹp mắt vừa dễ theo dõi chất lượng hạt.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Máy được cấu tạo từ chất liệu thép không gỉ cao cấp, bên ngoài có sơn tĩnh điện mang đến vẻ đẹp độc đáo, sang trọng cho quầy bar nhưng cũng có thể tháo lắp một cách dễ dàng nên rất thuận tiện cho việc vệ sinh máy và bảo trì bảo dưỡng sau quá trình sử dụng.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Đặc biệt, máy xay cà phê Macap MX Manual còn được đính kèm hộp đựng cà phê dự trữ giúp bảo quản lượng bột cà phê chưa dùng đến. Cần gạt trên hộp cũng giúp dễ dàng lấy ra lượng cà phê cần thiết.</span></p><h2><strong style=\"color: rgb(75, 16, 9); background-color: rgb(255, 255, 255);\">3.Vì sao nên chọn mua MACAP MX MANUAL tại Việt Cafe?</strong></h2><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Việt Cafe được thành lập 2013, chuyên cung cấp giải pháp tổng thể về cafe, đồng thời cũng là đơn vị tiên phong mang cà phê máy về Việt Nam. Tự hào là thương hiệu cà phê Việt với những con người “dùng cả trái tim” để làm phương tiện trong hành trình làm café.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Lựa chọn mua MACAP MX MANUAL tại Việt Cafe, bạn sẽ nhận được những ưu đãi không ở đâu có:</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Miễn chi phí lắp đặt, tư vấn, hướng dẫn cách sử dụng máy và công thức pha cà phê.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Thời gian bảo hành lên đến 12 tháng.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Dịch vụ bảo trì định kỳ 3 tháng/ lần</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Địa điểm lắp đặt máy được các kỹ thuật viên của Việt Cafe tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng.</span></p>', 1, 0, 8, '2026-01-07 01:33:23', '2026-01-07 01:50:20', NULL, NULL, 14, 'active', 0, 20000000.00, NULL, 1, 0, NULL, NULL, 'company', 'all', 0, 0, 0, NULL, NULL),
(168, 'Máy xay cà phê Macap MI20', 'may-xay-ca-phe-macap-mi20', 'MA-RNZH1', NULL, 'Đường kính lưỡi dao : 58mm\r\nCông suất :                  300W\r\nMotor :                          1400 rpm/ phút (50Hz)\r\n1680 rpm/ phút (60Hz)\r\nKích thước :                  194 x307xh560mm\r\nKhối lượng :                   7,5kg\r\nTốc độ xay :                   2 – 2,6g/giây\r\nXuất xứ : ITALY', NULL, '<h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. Máy xay cà phê Macap MI20</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Macap – Chuyên gia máy xay Châu Âu ra đời vào năm 1930, tập trung vào sản xuất máy xay cà phê và thiết bị cho quầy bar. Sự cải thiện về chất lượng của tất cả các sản phẩm chế biến cà phê, ngày càng được yêu cầu nhiều hơn ở nước ngoài do mức tiêu thụ và văn hóa cà phê ở nước ngoài có tốc độ tăng trưởng rất nhanh. Macap hiện đang làm việc với hơn 70 quốc gia ở nước ngoài, cung cấp nhiều lựa chọn sản phẩm. Điều này rất có ý nghĩa đối với năng lực đổi mới của Macap.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy xay cà phê Macap MI20 hiện đang rất được ưu chuộng nhờ sử dụng động cơ chuyên dụng, độ ồn thấp, tốc độ vòng quay nhanh.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đường kính lưỡi dao :</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;58mm</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Công suất :&nbsp;&nbsp;</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;300W</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Motor :&nbsp;&nbsp;</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1400 rpm/ phút (50Hz)</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">1680 rpm/ phút (60Hz)</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Kích thước :&nbsp;&nbsp;</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;194 x307xh560mm</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Khối lượng :&nbsp;</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7,5kg</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Tốc độ xay :&nbsp;</strong><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2 – 2,6g/giây</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Xuất xứ : ITALY</strong></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2.Ưu điểm vượt trội của MACAP MI20</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy xay cà phê Macap MI20 với giá thành hợp lý nhưng có rất nhiều ưu điểm vượt trội.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">2.1. Máy xay cà phê tự động Macap MI20</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy xay cà phê Macap MI20 hoạt động hoàn toàn tự động, lấy cà phê chỉ bằng một nút ấn, được thiết kế chuyên nghiệp, phù hợp với không gian quầy bar, tối ưu hóa hiệu suất hoạt động, giảm chi phí cho việc kinh doanh.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">2.2. Macap MI20 cho chất lượng bột cà phê tốt nhất</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Lưỡi dao có đường kính 58mm giúp tốc độ xay nhanh, bột cà phê thành phẩm mịn đồng đều và không bị cháy khét khi máy hoạt động liên tục.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">2.3. Macap MI20 cho phép cài đặt định lượng chuẩn</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy xay cà phê Macap MI20 cho phép cài đặt sẵn định lượng cà phê mỗi lần lấy, hỗ trợ Barista lấy được lương cà phê vừa đủ, vừa tiết kiệm, sạch sẽ, vừa giúp hoạt động pha chế trở nên nhanh gọn, đúng tiêu chuẩn. Máy sử dụng bộ đếm thời gian để cho ra lượng cà phê theo điều chỉnh ban đầu.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">2.4. Macap MI40 sử dụng dễ dàng</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy được thiết kế tối giản, tinh tế, dễ sử dụng và bảo quản. Phễu nhựa trong suốt đựng hạt cà phê không chỉ để đựng mà còn trưng bày được hạt cà phê vừa đẹp mắt vừa dễ theo dõi chất lượng hạt.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy xay cà phê Macap MI20 được cấu tạo từ chất liệu thép không gỉ có độ bền cao, sáng đẹp, có thể tháo lắp một cách dễ dàng nên rất thuận tiện cho việc vệ sinh máy và bảo trì bảo dưỡng sau quá trình sử dụng.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">3.Vì sao nên chọn mua MACAP MI20 tại Việt Cafe?</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Việt Cafe được thành lập 2013, chuyên cung cấp giải pháp tổng thể về cafe, đồng thời cũng là đơn vị tiên phong mang cà phê máy về Việt Nam. Tự hào là thương hiệu cà phê Việt với những con người “dùng cả trái tim” để làm phương tiện trong hành trình làm café.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Lựa chọn mua MACAP MI20 tại Việt Cafe, bạn sẽ nhận được những ưu đãi không ở đâu có:</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Miễn chi phí lắp đặt, tư vấn, hướng dẫn cách sử dụng máy và công thức pha cà phê.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thời gian bảo hành lên đến 12 tháng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dịch vụ bảo trì định kỳ 3 tháng/ lần</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Địa điểm lắp đặt máy được các kỹ thuật viên của Việt Cafe tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng.</span></p><p><br></p>', 1, 0, 0, '2026-01-07 01:38:44', '2026-01-07 01:38:44', NULL, NULL, 14, 'active', 0, 18000000.00, NULL, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(169, 'Máy nén cà phê Macap CPS', 'may-nen-ca-phe-macap-cps', 'MA-RRCXP', NULL, '– Kích thước: 165 x 210 x 405 mm\r\n– Trọng lượng: 4 kg\r\n– Lực nén: 25 kg\r\n– Đường kính Tamper: 57.5 mm', NULL, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy nén cà phê Macap CPS được thiết kế và sản xuất bởi thương hiệu Macap Ým- 1 trong 3 hãng máy xay cà phê có chất lượng tốt nhất Châu Âu.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. MÁY NÉN CÀ PHÊ MACAP CPS</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Macap – Chuyên gia máy xay Châu Âu ra đời vào năm 1930, tập trung vào sản xuất máy xay cà phê và thiết bị cho quầy bar. Sự cải thiện về chất lượng của tất cả các sản phẩm chế biến cà phê ngày càng được yêu cầu nhiều hơn ở nước ngoài do mức tiêu thụ và văn hóa cà phê ở nước ngoài có tốc độ tăng trưởng rất nhanh. Macap hiện đang làm việc với hơn 70 quốc gia ở nước ngoài, cung cấp nhiều lựa chọn sản phẩm. Điều này rất có ý nghĩa đối với năng lực đổi mới của Macap.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy nén cà phê Macap CPS cho lực nén đều, chuẩn, tiết kiệm thời gian và công sức cho công đoạn pha chế. Máy chuyên sử dụng cho các nhà hàng, khách sạn, quán cafe quy mô lớn.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thông số kỹ thuật:</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Kích thước: 165 x 210 x 405 mm</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Trọng lượng: 4 kg</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Lực nén: 25 kg</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Đường kính Tamper: 57.5 mm</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2. Ưu điểm vượt trội của MACAP CPS</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Kích thước đường kính Tamper có thể thay đổi theo yêu cầu để phù hợp với nhu cầu sử dụng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Macap CPS cho lực nén không thay đổi, giúp chất lượng nén cà phê đồng đều, cho ra tách cà phê ngon, chuẩn.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">3. Vì sao nên chọn mua MACAP MC42 tại Việt Cafe?</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Việt Cafe được thành lập 2013, chuyên cung cấp giải pháp tổng thể về cafe, đồng thời cũng là đơn vị tiên phong mang cà phê máy về Việt Nam. Tự hào là thương hiệu cà phê Việt với những con người “dùng cả trái tim” để làm phương tiện trong hành trình làm café.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Lựa chọn mua MACAP CPS tại Việt Cafe, bạn sẽ nhận được những ưu đãi không ở đâu có:</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Miễn chi phí lắp đặt, tư vấn, hướng dẫn cách sử dụng máy và công thức pha cà phê.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thời gian bảo hành lên đến 12 tháng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dịch vụ bảo trì định kỳ 3 tháng/ lần</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Địa điểm lắp đặt máy được các kỹ thuật viên của Việt Cafe tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng.</span></p><p><br></p>', 1, 0, 0, '2026-01-07 01:41:21', '2026-01-07 01:41:21', NULL, NULL, 14, 'active', 0, 10000000.00, 8500000.00, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(170, 'Máy đánh sữa Macap F6', 'may-danh-sua-macap-f6', 'MA-RYUS8', NULL, '– Kích thước: 200 x 220 x 500 mm\r\n– Công suất: 150 W\r\n– Nguồn điện: 220 – 240V\r\n– Motor: 15000 rpm\r\n– Trọng lượng: 2.8 Kg\r\n– Dung tích khay chứa: 0.8 Lt', NULL, '<p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Máy đánh sữa Macap F4 được thiết kế và sản xuất bởi thương hiệu Macap Ý – 1 trong 3 hãng máy xay cà phê có chất lượng tốt nhất Châu Âu.</span></p><h2><strong style=\"color: rgb(75, 16, 9); background-color: rgb(255, 255, 255);\">1. MÁY ĐÁNH SỮA MACAP F4</strong></h2><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Macap – Chuyên gia máy xay Châu Âu ra đời vào năm 1930, tập trung vào sản xuất máy xay cà phê và thiết bị cho quầy bar. Sự cải thiện về chất lượng của tất cả các sản phẩm chế biến cà phê ngày càng được yêu cầu nhiều hơn ở nước ngoài do mức tiêu thụ và văn hóa cà phê ở nước ngoài có tốc độ tăng trưởng rất nhanh. Macap hiện đang làm việc với hơn 70 quốc gia ở nước ngoài, cung cấp nhiều lựa chọn sản phẩm. Điều này rất có ý nghĩa đối với năng lực đổi mới của Macap.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Máy đánh sữa Macap F4 với sức đánh lớn giúp sữa bông đều, mượt mà, là dụng cụ vô cùng cần thiết cho pha chế cappucino. Máy chuyên sử dụng cho các nhà hàng, khách sạn, quán cafe.</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Thông số kỹ thuật:</strong></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Kích thước: 200 x 220 x 500 mm</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Công suất: 150 W</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Nguồn điện: 220 – 240V</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Motor: 15000 rpm</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Trọng lượng: 2.8 Kg</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Dung tích khay chứa: 0.8 Lt</span></p><h2><strong style=\"color: rgb(75, 16, 9); background-color: rgb(255, 255, 255);\">2. Ưu điểm vượt trội của MACAP F6</strong></h2><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Máy đánh sữa Macap F4 với rất nhiều ưu điểm vượt trội, là dụng vô cùng cần thiết cho quầy bar quán cafe.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">2.1. Máy đánh sữa tự động Macap F4</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Máy đánh sữa Macap F4 có chế độ đánh và tự động tắt giúp Barista không mất thời gian căn giờ mà vẫn có ly sữa bông chuẩn để pha chế.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">2.2. Macap F4 cho chất lượng nguyên liệu tốt nhất</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Lưỡi dao Macap F4 được làm từ thép không gỉ cao cấp siêu bền, đảm bảo chất lượng nguyên liệu và bảo vệ sức khỏe người sử dụng.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Máy có công suất 150W và motor bơm 15000 vòng/phút tạo nên bọt sánh mịn, béo ngậy cho món cappucino ngon đúng điệu.</span></p><h2><strong style=\"color: rgb(75, 16, 9); background-color: rgb(255, 255, 255);\">3. Vì sao nên chọn mua MACAP F4 tại Việt Cafe?</strong></h2><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Việt Cafe được thành lập 2013, chuyên cung cấp giải pháp tổng thể về cafe, đồng thời cũng là đơn vị tiên phong mang cà phê máy về Việt Nam. Tự hào là thương hiệu cà phê Việt với những con người “dùng cả trái tim” để làm phương tiện trong hành trình làm café.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Lựa chọn mua MACAP F4 tại Việt Cafe, bạn sẽ nhận được những ưu đãi không ở đâu có:</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Miễn chi phí lắp đặt, tư vấn, hướng dẫn cách sử dụng máy và công thức pha cà phê.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Thời gian bảo hành lên đến 12 tháng.</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Dịch vụ bảo trì định kỳ 3 tháng/ lần</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Địa điểm lắp đặt máy được các kỹ thuật viên của Việt Cafe tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng.</span></p>', 1, 0, 0, '2026-01-07 01:47:11', '2026-01-07 01:47:22', NULL, NULL, 14, 'active', 0, 7000000.00, NULL, 1, 0, NULL, NULL, 'company', 'all', 0, 0, 0, NULL, NULL),
(171, 'Bình cà phê Bravilor Decanters', 'binh-ca-phe-bravilor-decanters', 'BR-S1U2A', NULL, '– Dung tích: 1,7 Lt\r\n– Chất liệu: Thủy tinh\r\n– Xuất xứ: Hà Lan', NULL, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bình cà phê Bravilor Decanters được thiết kế và sản xuất bởi hãng Bravilor Bonamat Hà Lan, là nhà sản xuất hàng đầu về máy pha cà phê với hơn 70 năm kinh nghiệm, thích hợp sử dụng cho gia đình, văn phòng hoặc nhà hàng, khách sạn có quầy tự phục vụ.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. BÌNH CÀ PHÊ BRAVILOR DECANTERS</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bravilor Bonamat là nhà sản xuất hàng đầu về máy pha cà phê với hơn 70 năm kinh nghiệm, là một trong những thương hiệu hàng đầu thế giới về máy pha cà phê tự động. Tất cả các thiết bị Bravilor Bonamat đều được làm bằng vật liệu chất lượng cao, tiêu thụ ít năng lượng và có tuổi thọ cao, được kiểm tra kỹ lưỡng trước khi rời khỏi nhà máy. Sản phẩm của thương hiệu này được coi là đáng tin cậy, chất lượng cao và bền vững, luôn coi tính dễ sử dụng là ưu tiên số một khi phát triển và cập nhật các kỹ thuật, thiết kế mới, hiện đại.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bình cà phê Bravilor Decanters sử dụng cho máy pha cà phê giấy lọc, được đánh giá cao bởi thiết kế nhỏ gọn nhưng có dung tích lớn. Bình sử dụng chất liệu thủy tinh trong suốt sang trọng vừa đẹp mắt vừa giúp dễ dàng theo dõi chất lượng chiết suất cà phê.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thông số kỹ thuật:</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dung tích: 1,7 Lt</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Chất liệu: Thủy tinh</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Xuất xứ: Hà Lan</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2. Ưu điểm vượt trội của BRAVILOR DECANTERS</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bình được thiết kế thuận tiện với dung tích lớn, chất liệu thủy tinh trong suốt và vạch đong, dễ dàng nhìn được cà phê bên trong. Nắp đậy và tay cầm chắc chắn bằng nhựa màu đen sang trọng.</span></p><p><br></p>', 1, 0, 0, '2026-01-07 01:49:30', '2026-01-07 01:49:30', NULL, NULL, 15, 'active', 0, 600000.00, NULL, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(172, 'Máy pha cà phê giấy lọc Bravilor – NOVO', 'may-pha-ca-phe-giay-loc-bravilor-novo', 'BR-S5HUU', NULL, '– Kích thước: 214 x 391 x 424mm\r\n– Nguồn điện: 220 – 240V/ 50 – 60 Hz\r\n– Công suất: 2140 W\r\n– Thời gian làm cafe: 10’/ 1 bình/ 12 cup\r\n– Dung tích bình đun: 1,7 lt\r\n– Công suất 1h: 18 lt\r\n– Xuất xứ: Hà Lan', NULL, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bravilor Novo được thiết kế và sản xuất bởi hãng Bravilor Bonamat Hà Lan, là nhà sản xuất hàng đầu về máy pha cà phê với hơn 70 năm kinh nghiệm. Bravilor Novo phù hợp sử dụng cho các gia đình, văn phòng hoặc nhà hàng, khách sạn có quầy tự phục vụ.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. Máy pha cafe BRAVILOR – NOVO</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bravilor Bonamat là nhà sản xuất hàng đầu về máy pha cà phê với hơn 70 năm kinh nghiệm, là một trong những thương hiệu hàng đầu thế giới về máy pha cà phê tự động. Tất cả các thiết bị Bravilor Bonamat đều được làm bằng vật liệu chất lượng cao, tiêu thụ ít năng lượng và có tuổi thọ cao, được kiểm tra kỹ lưỡng trước khi rời khỏi nhà máy. Sản phẩm của thương hiệu này được coi là đáng tin cậy, chất lượng cao và bền vững, luôn coi tính dễ sử dụng là ưu tiên số một khi phát triển và cập nhật các kỹ thuật, thiết kế mới, hiện đại.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cà phê giấy lọc BRAVILOR – NOVO hoạt động bằng cách sử dụng giấy lọc để chiết xuất ra cà phê, được đánh giá cao bởi thiết kế nhỏ gọn, sang trọng, công suất lọc lớn, thời gian làm cà phê ngắn và hương vị cà phê tuyệt hảo.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thông số kỹ thuật:</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Kích thước: 214 x 391 x 424mm</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Nguồn điện: 220 – 240V/ 50 – 60 Hz</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Công suất: 2140 W</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thời gian làm cafe: 10’/ 1 bình/ 12 cup</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dung tích bình đun: 1,7 lt</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Công suất 1h: 18 lt</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Xuất xứ: Hà Lan</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2. Ưu điểm vượt trội của BRAVILOR – NOVO</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bravilor – Novo là máy pha cà phê giấy lọc có công suất cao, tiêu thụ ít năng lượng và có tuổi thọ cao nhưng mức giá lại rất hợp lý.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">2.1 Bravilor – Novo thiết kế tiện lợi</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bravilor – Novo có thiết kế nhỏ gọn, đơn giản và rất dễ sử dụng, người dùng không cần phải thực hiện nhiều thao tác bấm phím khi sử dụng nên rất thích hợp dùng cho các gia đình, văn phòng hoặc quầy tiệc buffet tự phục vụ.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Chất liệu thép xước không gỉ kết hợp với nhựa ABS khiến Bravilor – Novo nhìn vẫn sang trọng và cứng cáp nhưng trọng lượng không quá nặng. Nhờ thiết kế đơn giản và đèn nhắc nhở vệ sinh định kỳ, việc vệ sinh máy cũng trở nên khá dễ dàng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bình đựng cà phê được thiết kế thuận tiện với dung tích lớn, chất liệu thủy tinh trong suốt và vạch đong, dễ dàng nhìn được cà phê bên trong. Nắp đậy và tay cầm chắc chắn bằng nhựa màu đen sang trọng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Ngoài ra, nhờ khay hâm nóng cà phê trên máy, Bravilor – Novo giúp cà phê thơm nóng suốt cả ngày dài, vô cùng lý tưởng cho môi trường tự phục vụ.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">2.2 Bravilor – Novo công suất lớn</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bravilor – Novo là dòng máy pha cà phê giấy lọc chuyên nghiệp có khả năng lọc được 18 lít cà phê chỉ trong một giờ. Dù vậy, hương vị cà phê vẫn luôn tinh khiết và đậm đà nhất.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">3. Vì sao nên chọn mua BRAVILOR – NOVO Tại việt cafe?</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Việt Cafe được thành lập 2013, chuyên cung cấp giải pháp tổng thể về cafe, đồng thời cũng là đơn vị tiên phong mang cà phê máy về Việt Nam. Tự hào là thương hiệu cà phê Việt với những con người “dùng cả trái tim” để làm phương tiện trong hành trình làm café.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Lựa chọn mua BRAVILOR – NOVO tại Việt Cafe, bạn sẽ nhận được những ưu đãi không ở đâu có:</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Miễn chi phí lắp đặt, tư vấn, hướng dẫn cách sử dụng máy và công thức pha cà phê.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thời gian bảo hành lên đến 18 tháng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dịch vụ bảo trì định kỳ 3 tháng/ lần</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Địa điểm lắp đặt máy được các kỹ thuật viên của Việt Cafe tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng.</span></p><p><br></p>', 1, 0, 1, '2026-01-07 01:52:21', '2026-01-07 08:33:11', NULL, NULL, 15, 'active', 0, 15000000.00, 12000000.00, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(173, 'Máy pha cà phê giấy lọc Bravilor B5', 'may-pha-ca-phe-giay-loc-bravilor-b5', 'BR-SAGJB', NULL, '– Kích thước: 635 x x 440 x 799 mm\r\n– Công suất: 3130W\r\n– Nguồn điện: 220 – 240V/ 50-60Hz\r\n– Thời gian làm cà phê: 10’/5Lt\r\n– Dung tích bình đun: 10 lt\r\n– Màn hình hiển thị: Có\r\n– Xuất xứ: Hà Lan', NULL, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bravilor B5 là thiết bị pha cà phê giấy lọc chuyên nghiệp có công suất lớn, phù hợp sử dụng ở các nhà hàng, khách sạn cần phục vụ số lượng thực khách lớn trong cùng một thời điểm.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. Máy pha giấy lọc BRAVILOR B5</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Bravilor Bonamat là nhà sản xuất hàng đầu về máy pha cà phê với hơn 70 năm kinh nghiệm, là một trong những thương hiệu hàng đầu thế giới về máy pha cà phê tự động. Tất cả các thiết bị Bravilor Bonamat đều được làm bằng vật liệu chất lượng cao, tiêu thụ ít năng lượng và có tuổi thọ cao, được kiểm tra kỹ lưỡng trước khi rời khỏi nhà máy. Sản phẩm của thương hiệu này được coi là đáng tin cậy, chất lượng cao và bền vững, luôn coi tính dễ sử dụng là ưu tiên số một khi phát triển và cập nhật các kỹ thuật, thiết kế mới, hiện đại.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cà phê giấy lọc BRAVILOR B5 hoạt động bằng cách sử dụng giấy lọc để chiết xuất ra cà phê, luôn được đánh giá cao bởi công suất lọc và hương vị cà phê tuyệt hảo.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thông số kỹ thuật:</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Kích thước: 635 x x 440 x 799 mm</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Công suất: 3130W</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Nguồn điện: 220 – 240V/ 50-60Hz</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thời gian làm cà phê: 10’/5Lt</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dung tích bình đun: 10 lt</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Màn hình hiển thị: Có</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Xuất xứ: Hà Lan</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2. Ưu điểm vượt trội của máy pha giấy lọc BRAVILOR B5</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cà phê Bravilor B5 được thiết kế và sản xuất bởi hãng Bravilor Bonamat Hà Lan, là nhà sản xuất hàng đầu về máy pha cà phê với hơn 70 năm kinh nghiệm. Bravilor B5 phù hợp sử dụng cho nhà hàng, khách sạn lớn.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">*Công suất lớn với 2 bình chứa</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cà phê giấy lọc BRAVILOR B5 gồm một hệ thống chiết suất và hai bình chứa có dung tích 5 lít. Máy được kết nối với trực tiếp với nguồn nước và được thiết kế lọc cà phê trong thời gian ngắn nhưng vẫn giữ được hương vị cà phê tinh khiết, đậm đà nhất. Máy chiết xuất cà phê dạng bột, thùng chứa dễ dàng theo dõi chất lượng của cà phê.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">*Màn hình hiển thị thông minh</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">BRAVILOR B5 L/R có màn hình LCD với các chương trình được cài đặt tự động, bộ đếm thời gian tích hợp, hệ thống khử cặn và đèn tín hiệu cà phê đã sẵn sàng hỗ trợ pha chế tiện lợi và dễ dàng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">* Cấu tạo bền chắc, tuổi thọ lớn</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Sử dụng chất liệu thép không gỉ chất lượng cao khiến Bravilor B5 cứng cáp, chịu được áp lực chiết xuất lớn nhưng vẫn vô cùng sang trọng. Sự đơn giản trong thiết kế và chất liệu giúp cho việc vệ sinh máy cũng trở nên dễ dàng hơn.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">3. Vì sao nên chọn mua máy pha giấy lọc BRAVILOR B5 Tại việt cafe?</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Việt Cafe – Đơn vị được thành lập từ năm 2013, cung cấp giải pháp tổng thể ngành F&amp;B. Chúng tôi tự hào là đơn vị cung cấp độc quyền của nhiều hãng máy móc thiết bị pha chế cafe, với mong muốn đem những sản phẩm chất lượng cho người Việt.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Tư vấn, hướng dẫn sử dụng máy và công thức pha cafe</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thời gian bảo hành: 12 tháng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dịch vụ bảo trì định kỳ 6 tháng/ lần</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Ngoài ra, địa điểm lắp đặt máy pha cafe sẽ được các kỹ thuật viên của Việt Cafe tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng.</span></p>', 1, 0, 0, '2026-01-07 01:56:12', '2026-01-07 02:02:54', NULL, NULL, 15, 'active', 0, 110000000.00, NULL, 1, 0, NULL, NULL, 'company', 'all', 0, 0, 0, NULL, NULL),
(176, 'Máy pha cà phê IBERITAL REFERENT 2 GROUPS', 'may-pha-ca-phe-iberital-referent-2-groups', 'BR-SONWT', NULL, 'Kích thước: 785 x 598 x 484 mm\r\nCông suất: 3000 W\r\nNguồn điện: 220 – 240V ~ 50Hz\r\nTrọng lượng: 61 Kg\r\nDung tích bình đun: 11 Lít\r\nHọng làm cafe: 2\r\nXuất xứ: Tây Ban Nha\r\n(Giá sản phẩm chưa bao gồm VAT)', NULL, NULL, 1, 0, 0, '2026-01-07 02:07:15', '2026-01-07 02:07:15', NULL, NULL, 15, 'active', 0, 96000000.00, NULL, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(177, 'Máy pha cà phê cao cấp Iberital Vista', 'may-pha-ca-phe-cao-cap-iberital-vista', 'IB-SQSZJ', NULL, '– Kích thước:  845 x 672 x 460 mm\r\n– Công suất: 2400W\r\n– Nguồn điện: 220- 240V ~50/60Hz\r\n– Trọng lượng: 60.6 Kg\r\n– Dung tích bình đun: 3.0 Lit – 8.3lit\r\n– Số vòi đánh hơi: 2\r\n– Vòi nước nóng: 1\r\n– Thương hiệu: Iberital\r\n– Xuất xứ: Tây Ban Nha', NULL, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cà phê cao cấp Iberital Vista: Dòng sản phẩm phiên bản cao cấp giới hạn của Iberital với công nghệ khoa học tiên tiến, thiết lập chương trình tự động bằng bảng điều khiển hiển thị cảm ứng, sở hữu công suất khủng mà các quán cafe, nhà hàng, khách sạn không thể bỏ qua.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. Máy pha cà phê Iberital Vista</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thành lập từ năm 1975&nbsp;ở thành phố Barcelona, đến nay IBERITAL đã trở thành thương hiệu hàng đầu thế giới về máy và thiết bị pha cafe với công nghệ hiện đại. Thiết kế mạnh mẽ, công nghệ tiên tiến, Iberital khiến các Barista dù khó tính nhất cũng phải gật đầu thừa nhận chất lượng hàng đầu. Hãng Iberital luôn đặt sự tối ưu lên hàng đầu nên luôn được các Barista trên thế giới ưa chuộng. Hầu hết các sản phẩm của Iberital được thiết kế sử dụng thép không gỉ chất lượng cao, bề mặt bóng và kiểu nút backlit.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cà phê cao cấp Iberital Vista – Kiểm soát tất cả các khâu pha chế chỉ bằng 1 chiếc máy tính bảng đi kèm, giúp tiết kiệm điện và năng lượng; tùy chỉnh mọi thứ từ nước cấp đến định lượng cafe và nước pha.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thông số kỹ thuật</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Kích thước:&nbsp;845 x 672 x 460 mm</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Công suất: 2400W</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Nguồn điện: 220- 240V ~50/60Hz</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Trọng lượng: 60.6 Kg</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dung tích bình đun: 3.0 Lit – 8.3lit</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Số vòi đánh hơi: 2</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Vòi nước nóng: 1</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thương hiệu: Iberital</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Xuất xứ: Tây Ban Nha</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2. Vì sao nên chọn mua máy pha cà phê cao cấp Iberital Vista Tại VIỆT CAFE?</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Việt Cafe – Đơn vị được thành lập từ năm 2013, cung cấp giải pháp tổng thể ngành F&amp;B. Chúng tôi tự hào là đơn vị cung cấp độc quyền của nhiều hãng máy móc thiết bị pha chế cafe, với mong muốn đem những sản phẩm chất lượng cho người Việt.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Tư vấn, hướng dẫn sử dụng máy và công thức pha cafe</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thời gian bảo hành: 18 tháng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dịch vụ bảo trì định kỳ 3 tháng/ lần</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Tặng kèm 1 Khóa Cafe Máy trị giá 5.000.000000 đồng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Ngoài ra, địa điểm lắp đặt máy pha cafe sẽ được các kỹ thuật viên của Việt Cafe tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng</span></p><p><br></p>', 1, 0, 0, '2026-01-07 02:08:55', '2026-01-07 02:08:55', NULL, NULL, 17, 'active', 0, 240000000.00, NULL, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL);
INSERT INTO `products` (`id`, `name`, `slug`, `product_sku`, `material`, `description`, `video_url`, `article`, `is_active`, `is_featured`, `view_count`, `created_at`, `updated_at`, `vendor`, `collection`, `category_id`, `status`, `tax`, `price`, `discounted_price`, `in_stock`, `quantity`, `shipping_weight`, `shipping_dimensions`, `shipping_mode`, `payment_method`, `is_fragile`, `is_biodegradable`, `is_frozen`, `max_temp`, `expiry_date`) VALUES
(178, 'Máy pha cà phê Iberital IB7 2 Groups', 'may-pha-ca-phe-iberital-ib7-2-groups', 'IB-SRYSV', NULL, '– Kích thước: 695 x 508 x 460 mm\r\n– Công suất: 220 – 240V/ 3000W – 50Hz\r\n– Trọng lượng: 61 Kg\r\n– Dung tích bình đun: 10.0 Lt\r\n– Họng làm cafe: 2\r\n– Số vòi đánh hơi: 2\r\n– Vòi nước nóng: 1\r\n– Thương hiệu: Iberital\r\n– Xuất xứ: Tây Ban Nha', NULL, '<h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. Máy pha cafe Iberital IB7 2 Groups</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thành lập từ năm 1975 ở thành phố Barcelona, đến nay IBERITAL đã trở thành thương hiệu hàng đầu thế giới về máy và thiết bị pha cafe với công nghệ hiện đại. Thiết kế mạnh mẽ, công nghệ tiên tiến, Iberital khiến các Barista dù khó tính nhất cũng phải gật đầu thừa nhận chất lượng hàng đầu. Hãng Iberita luôn đặt sự tối ưu lên hàng đầu nên luôn được các Barista trên thế giới ưu chuộng. Hầu hết các sản phẩm của Iberital được thiết kế sử dụng thép không gỉ chất lượng cao, bề mặt bóng và kiểu nút back-lit.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">IB7 – 2 Groups với cấu tạo 2 họng làm cafe nên công suất của máy có thể đạt hơn 120 – 160 ly cafe trong 1 giờ nhờ dung tích bình đun lớn và sự tối ưu trong thiết kế.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thông số kỹ thuật</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Kích thước: 695 x 508 x 460 mm</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Công suất: 220 – 240V/ 3000W – 50Hz</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Trọng lượng: 61 Kg</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dung tích bình đun: 10.0 Lt</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Họng làm cafe: 2</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Số vòi đánh hơi: 2</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Vòi nước nóng: 1</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thương hiệu: Iberital</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Xuất xứ: Tây Ban Nha</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2. Ưu điểm vượt trội của máy pha cafe Iberital IB7 2 Groups</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cafe IB7 – 2 Groups được coi là sự lựa chọn hàng đầu cho Quán Cafe quy mô vừa và nhỏ. Hãng Iberita luôn đặt sự tối ưu lên hàng đầu nên luôn được các Barista trên thế giới ưu chuộng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">*Công nghệ hiện đại – Bảng điều khiển tự động</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy Pha Cafe IB7 – 2 Groups sở hữu công nghệ hiện đại cho phép:</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Có thể lập trình sẵn lượng cafe, thời gian cần chiết xuất là 1 shot hoặc 2 shot Espresso nhờ 4 nút chế độ ngay trên mỗi group</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Quản lý kiểm soát áp suất, thời gian, lượng nước pha chế để cho ra ly Espresso đúng tiêu chuẩn, chất lượng đồng đều, Chỉ với thao tác nhấn nút đơn giản, barista dễ dàng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Sự lựa chọn hoàn hảo cho các quán café có lượng khách đông tại 1 thời điểm.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">*Núm điều chỉnh vòi hơi dễ dàng, chú trọng trải nghiệm Barista</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Nhằm mang lại cho Barista sự thoải mái tối đa trong quá trình pha chế, giúp thao tác pha chế nhanh chóng, đơn giản và dễ dàng hơn. Cũng như nhiều dòng máy khác, phía trên của máy pha cafe chuyên nghiệp Iberital IB7 có hệ thống máy sấy cốc cực kỳ hiệu quả. Chính là những chi tiết nhỏ nhưng lại thể hiện sự tinh tế của thương hiệu khi quan tâm đến trải nghiệm nhỏ nhất của người dùng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">*Máy pha cafe dễ sử dụng, dễ vệ sinh</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">IB7 2 họng rất dễ sử dụng. Chỉ với những thao tác đơn giản như bật máy, kiểm tra đồng hồ báo áp suất đúng tiêu chuẩn là barista có thể thực hiện pha chế Espresso chỉ với thao tác bấm nút. Kể cả những người không chuyên cũng sẽ dễ dàng sử dụng dưới sự hướng dẫn cơ bản.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">IB7 2 họng rất dễ vệ sinh. Việc vệ sinh máy thường xuyên sau khi sử dụng là rất cần thiết để tránh ảnh hưởng đến hương vị của ly cafe được chiết xuất ra. với IB7, barista dễ dàng vệ sinh máy cuối buổi và bảo trì hàng tuần để đảm bảo các tiêu chuẩn, công suất của máy pha cafe.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">3. Vì sao nên chọn mua Iberital IB7 2 Groups tại Việt Cafe?</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Việt Cafe – Đơn vị được thành lập từ năm 2013, cung cấp giải pháp tổng thể ngành F&amp;B. Chúng tôi tự hào là đơn vị cung cấp độc quyền của nhiều hãng máy móc thiết bị pha chế cafe, với mong muốn đem những sản phẩm chất lượng cho người Việt.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Tư vấn, đào tạo cách sử dụng máy và công thức pha cà phê</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thời gian bảo hành: 18 tháng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dịch vụ bảo trì định kỳ 3 – 6 tháng/ lần</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Ngoài ra, địa điểm lắp đặt máy pha cafe sẽ được các kỹ thuật viên của Việt Cafe tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng.</span></p><p><br></p>', 1, 0, 0, '2026-01-07 02:09:49', '2026-01-07 02:09:49', NULL, NULL, 17, 'active', 0, 79000000.00, NULL, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(179, 'Máy pha cà phê Iberital Intenz Luxury 1 Group', 'may-pha-ca-phe-iberital-intenz-luxury-1-group', 'IB-STZP8', NULL, 'Kích thước: 476 x 585 x 451 mm\r\n Công suất: 1800 W ~ 2400 W\r\nNguồn điện : 220-240V ~50Hz \r\nTrọng lượng: 42 Kg\r\nDung tích bình đun: 6.5 Lt\r\nHọng làm cafe: 1\r\nSố vòi đánh hơi: 1\r\nVòi nước nóng: 1\r\nThương hiệu: Iberital \r\nXuất xứ: Tây Ban Nha', NULL, '<h2><strong style=\"color: rgb(75, 16, 9); background-color: rgb(255, 255, 255);\">1. Máy pha cafe nhỏ gọn iberital 1 group</strong></h2><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Thành lập từ năm 1975&nbsp;ở thành phố Barcelona, đến nay IBERITAL đã trở thành thương hiệu hàng đầu thế giới về máy và thiết bị pha cafe với công nghệ hiện đại. Thiết kế mạnh mẽ, công nghệ tiên tiến, Iberital khiến các Barista dù khó tính nhất cũng phải gật đầu thừa nhận chất lượng hàng đầu.&nbsp;</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Hãng Iberital luôn đặt sự tối ưu lên hàng đầu nên luôn được các Barista trên thế giới ưa chuộng. Hầu hết các sản phẩm của Iberital được thiết kế sử dụng thép không gỉ chất lượng cao, bề mặt bóng và kiểu nút backlit.&nbsp;</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Máy pha cafe nhỏ gọn Iberital Intenz Luxury 1 Group có thiết kế hệ thống kiểm soát nước nóng tự động giúp cho việc pha chế trở nên đơn giản hơn.</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;&nbsp;Thông số kỹ thuật</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Kích thước: 476 x 585 x 451 mm</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;Công suất: 1800 W ~ 2400 W</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Nguồn điện : 220-240V ~50Hz&nbsp;</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Trọng lượng: 42 Kg</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Dung tích bình đun: 6.5 Lt</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Họng làm cafe: 1</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Số vòi đánh hơi: 1</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Vòi nước nóng: 1</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Thương hiệu: Iberital&nbsp;</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Xuất xứ: Tây Ban Nha&nbsp;</span></li></ol><h2><strong style=\"color: rgb(75, 16, 9); background-color: rgb(255, 255, 255);\">2.Ưu điểm vượt trội Iberital Intenz Luxury 1 Group</strong></h2><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Máy pha cà phê Iberital Intenz Luxury 1 Group là dòng máy pha cà phê bán tự động thông minh với kiểu dáng đẹp, hiện đại, hợp với nhiều kiểu thiết kế quán cafe, quầy bar khác nhau. Không chỉ có thiết kế chuẩn mực mà dòng máy này còn là một dòng máy hiệu quả cho năng suất sử dụng cao.</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">*Thiết kế nhỏ gọn,</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;</span><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">dễ sử dụng</strong></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;&nbsp;+ Thiết kế hệ thống kiểm soát nước nóng tự động giúp cho việc pha chế trở nên đơn giản hơn.</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;&nbsp;</strong><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">+ Kiểu nút backlit, nút xoay đánh sữa được cài sẵn 1 góc 90 độ giúp Barista không gặp khó khăn trong vấn đề chỉnh tốc độ vòi đánh sữa&nbsp;</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;+&nbsp;Sử dụng thép không gỉ chất lượng cao, bề mặt bóng, hợp với nhiều kiểu thiết kế quán cafe, quầy bar khác nhau nhờ thiết kế thông minh với kiểu dáng đẹp, hiện đại</span></p><p><strong style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">*Tối ưu nhờ Công nghệ tiên tiến&nbsp;</strong></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Dung tích bình đun lớn so với những dòng máy cùng phân khúc:&nbsp;</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">&nbsp;&nbsp;+ So với Bình đun của các dòng máy 1 group thì Bình đun của Iberital Intenz Luxury 1 Group lớn hơn (6.5 Lt), công suất lớn (1800W- 2400W) sẽ cho phép barista có thể pha chế nhanh hơn, không bị gián đoạn do không phải chờ máy đun nước nóng.&nbsp;</span></p><h2><strong style=\"color: rgb(75, 16, 9); background-color: rgb(255, 255, 255);\">3. Vì sao nên chọn mua Iberital Intenz Luxury 1 Group ?&nbsp;</strong></h2><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">Đơn vị được thành lập từ năm 2013, cung cấp giải pháp tổng thể ngành F&amp;B. Chúng tôi tự hào là đơn vị cung cấp độc quyền của nhiều hãng máy móc thiết bị pha chế cafe, với mong muốn đem những sản phẩm chất lượng cho người Việt.&nbsp;</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Tư vấn, hướng dẫn sử dụng máy và công thức pha cafe&nbsp;</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Thời gian bảo hành: 18 tháng</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Dịch vụ bảo trì định kỳ 3 tháng/ lần</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Tặng kèm 1 Khóa Cafe Máy trị giá 5.000.000000 đồng&nbsp;</span></p><p><span style=\"color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">– Ngoài ra, địa điểm lắp đặt máy pha cafe sẽ được các kỹ thuật viên tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng</span></p>', 1, 0, 1, '2026-01-07 02:11:23', '2026-01-07 08:30:08', NULL, NULL, 17, 'active', 0, 68000000.00, 63000000.00, 1, 0, NULL, NULL, 'company', 'all', 0, 0, 0, NULL, NULL),
(180, 'Máy pha cà phê Royal Synchro T2 – 3 Groups', 'may-pha-ca-phe-royal-synchro-t2-3-groups', 'RO-SW2EE', NULL, '– Kích thước:976 x 602 x 586 mm\r\n– Công suất: 4.880W ~ 220-240V ~50Hz\r\n– Trọng lượng: 85Kg\r\n– Dung tích bình đun: 11.0 Lt\r\n– Vòi nước nóng: 1\r\n– Thương hiệu: ROYAL\r\n– Model: Electronic\r\n– Xuất xứ: Italia', NULL, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cà phê Royal SYNCHRO T2 – 3 Group với thiết kế ấn tượng hiện đại sẽ làm bừng sáng quầy bar của quý khách.Chất lượng cafe được tạo ra bằng máy Synchro T2 luôn đạt tiêu chuẩn, đồng đều chất lượng, cần thiết với quán lớn, lượng khách đông vào 1 thời điểm.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. Máy pha cafe ROYAL SYNCHRO T2 – 3 GROUPS</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Royal là hãng sản xuất máy pha cafe hàng đầu thế giới. Dòng máy pha cà phê mang thương hiệu ROYAL (Ý) được sử dụng tại rất nhiều nước trên thế giới. Thương hiệu thuộc sở hữu của công ty TNHH CBC Royal First với những đôi tay thợ máy cafe lành nghề nhất thế giới.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cafe Royal SYNCHRO T2 – 3 Group với cấu tạo 1 họng làm cafe nên công suất của máy có thể đạt hơn 60 – 80 ly cafe trong 1 giờ nhờ dung tích bình đun lớn và sự tối ưu trong thiết kế.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thông số kỹ thuật</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Kích thước:976 x 602 x 586 mm</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Công suất: 4.880W ~ 220-240V ~50Hz</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Trọng lượng: 85Kg</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dung tích bình đun: 11.0 Lt</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Vòi nước nóng: 1</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thương hiệu: ROYAL</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Model: Electronic</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Xuất xứ: Italia</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2. Ưu điểm vượt trội của ROYAL SYNCHRO T2 – 3 GROUPS</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Mô tả: Máy pha cà phê Royal SYNCHRO T2 – 3 Group Thuộc phân khúc máy pha cafe bán tự động, áp dụng nguyên lý chân không với áp suất hơi nước lớn giúp quá trình pha chế dễ dàng, nhanh chóng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">*Thiết kế độc đáo, ấn tượng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Hệ thống đèn led: tinh tế, nổi bật, có đặc trưng riêng của thương hiệu, tạo điểm nhấn cho quầy bar.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Sự kết hợp hài hòa giữa 3 màu sắc chủ đạo: đen, đỏ và xanh tạo nên 1 thiết kế ẩn tượng ngay khi bắt gặp.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">*Công suất lớn – Bền bỉ với thời gian</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Bình đun dung tích lớn (11lt) được làm bằng đồng có khả năng chịu được áp lực cao, và bền. Công suất bình đun đủ để cung cấp lượng nước pha chế nhiều ly cafe một lúc mà không bị giảm nhiệt độ của nước.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Với bảng điều khiển công nghệ hiện đại hiển thị đầy đủ các thông số kỹ thuật gồm: nhiệt độ, áp suất, thời gian giúp bạn dễ dàng kiểm soát quá trình vận hành. Đặc biệt, barista có thể tự điều chỉnh lượng nước để pha chế được ly café theo những yêu cầu khác nhau. Barista cũng có thể cài đặt việc chiết càng đơn hay càng đôi, độ đậm nhạt của cafe và lượng ml Espresso như mong muốn để đảm bảo chất lượng cafe luôn đồng đều.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Máy pha cafe Synchro T2 có chế độ đun chuẩn và đun nhanh để luôn đảm bảo được nhiệt độ nước tiêu chuẩn trong trường hợp cần thiết.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đảm bảo an toàn khi sử dụng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Ngoài ra, máy pha cafe Royal Synchro T2 còn có 2 nút công tắc dự phòng được dùng trong trường hợp các nút điều khiển gặp sự cố.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Thân máy được làm bằng chất liệu cách nhiệt, an toàn khi pha chế đối với các Barista</span></p><p><br></p>', 1, 0, 0, '2026-01-07 02:13:00', '2026-01-07 02:13:00', NULL, NULL, 16, 'active', 0, NULL, NULL, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(181, 'Máy pha cà phê chuyên nghiệp Royal Generation-X 2', 'may-pha-ca-phe-chuyen-nghiep-royal-generation-x-2', 'RO-T164P', NULL, '– Kích thước:783× 607× 776mm\r\n– Công suất: 3500W ~ 220-240V ~50Hz\r\n– Trọng lượng: Kg\r\n– Dung tích bình đun: 14.0 Lt\r\n– Họng làm cafe: 2\r\n– Số vòi đánh hơi:\r\n– Vòi nước nóng: 1\r\n– Thương hiệu: ROYAL\r\n– Xuất xứ: Italia', NULL, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cafe chuyên nghiệp Royal GENERATION X 2 Group được xem là phiên bản đột phá của Royal với thiết kế ấn tượng, hiện đại sẽ làm cho quầy bar của bạn nổi bật… Nếu bạn đang cần một máy pha cafe phân khúc cao cấp thì Generation X của Royal sẽ đáp ứng được hết các tiêu chí khiến bạn hài lòng.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. Máy pha cafe ROYAL GENERATION-X 2 GROUPS</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Royal là hãng sản xuất máy pha cafe hàng đầu thế giới. Dòng máy pha cà phê mang thương hiệu ROYAL (Ý) được sử dụng tại rất nhiều nước trên thế giới. Thương hiệu thuộc sở hữu của công ty TNHH CBC Royal First với những đôi tay thợ máy cafe lành nghề nhất thế giới.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cà phê Royal AVIATOR Electronic – 2 Group được Việt Cafe nhập khẩu chính hãng từ hãng Royal, chúng tôi cam kết về chất lượng mỗi sản phẩm mà mình cung cấp. Uy tín của chúng tôi đã được chứng nhận trong suốt 6 năm phục vụ thị trường. Các sản phẩm máy đều có đầy đủ chứng từ nhập khẩu, chứng nhận xuất xứ sản phẩm và được bảo hành 18 tháng, bảo trì thường xuyên bởi Việt Cafe.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thông số kỹ thuật</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Kích thước:783× 607× 776mm</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Công suất: 3500W ~ 220-240V ~50Hz</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Trọng lượng: Kg</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dung tích bình đun: 14.0 Lt</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Họng làm cafe: 2</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Số vòi đánh hơi:</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Vòi nước nóng: 1</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thương hiệu: ROYAL</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Xuất xứ: Italia</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2. Ưu điểm vượt trội của ROYAL GENERATION-X 2 GROUPS</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Siêu máy pha cafe Royal AVIATOR Electronic – 2 Group – Lựa chọn hàng đầu của các quán cafe, nhà hàng lớn là dòng máy pha cafe cơ, đột phá từ thiết kế ấn tượng đến công nghệ mà GENERATION sở hữu.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Công nghệ dẫn đầu trong giới Máy pha cafe</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Công nghệ TCI( kiểm soát nhiệt độc lập) giúp các Barista có thể tùy chỉnh cài đặt nhiệt độ của từng group với độ chính xác tối đa. Với phương châm đơn giản và trực giác, mỗi group có một màn hình led ấn tượng, hiển thị đầy đủ các thông số kỹ thuật cần thiết: thời gian, nhiệt độ…. tùy theo yêu cầu pha chế của từng Barista. Đặc biệt với thiết kế mạch giữ nhiệt trước khi pha chế giúp nhiệt độ nước luôn luôn ổn định, ngay cả khi thực hiện việc pha chế liên tục.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ PPI – Hệ thống kiểm soát áp suất mới: Barista có thể kiểm soát được áp suất khác nhau cho từng group, điều này cho phép chiết xuất café hoàn hảo, đúng chuẩn.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Hệ thống Công tắc dự phòng: Máy pha cà phê chuyên nghiệp cao cấp Aviator còn có công tắc dự phòng hỗ trợ hoạt động pha chế trong trường hợp lỗi group mà chưa xử lý được ngay.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Sự chuyên nghiệp đến từ những chi tiết nhỏ nhất:</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Thiết kế tinh tế ở từng chi tiết nhỏ nhất: tay cầm mềm mại, độ nặng vừa phải mang lại cảm giác thoải mái, đèn chiếu sáng được trang bị ở mỗi group cho phép Barista cảm nhận rõ hơn chất lượng dòng chảy cà phê để có sự điều chỉnh phù hợp.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ GENERATION an toàn với người sử dụng với các nút bấm đơn giản, thiết kế các bộ phận hợp lý.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Công suất khủng – Tính năng tuyệt vời:</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Dung tích bình đun lớn (14Lt) với mạch ổn nhiệt đảm bảo sự ổn định giúp barista pha chế nhanh, liên tục mà vẫn đảm bảo chất lượng đồ uống đồng đều.</span></p>', 1, 0, 1, '2026-01-07 02:16:58', '2026-01-07 02:21:10', NULL, NULL, 16, 'active', 0, NULL, NULL, 0, 0, NULL, NULL, 'company', 'all', 0, 0, 0, NULL, NULL),
(182, 'Máy pha cà phê Royal Aviator Electronic – 2 Groups', 'may-pha-ca-phe-royal-aviator-electronic-2-groups', 'RO-TC9PZ', NULL, '– Kích thước:783× 607× 776mm\r\n– Công suất: 3500W ~ 220-240V ~50Hz\r\n– Trọng lượng: Kg\r\n– Dung tích bình đun: 14.0 Lt\r\n– Họng làm cafe: 2\r\n– Số vòi đánh hơi:\r\n– Vòi nước nóng: 1\r\n– Thương hiệu: ROYAL\r\n– Xuất xứ: Italia', NULL, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Nếu bạn đang cần một máy pha cafe phân khúc cao cấp thì Máy pha cafe Royal AVIATOR Electronic – 2 Group sẽ là một lựa chọn hoàn hảo xét về mọi mặt: chất lượng, thiết kế, thương hiệu, tính năng…</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. Máy pha cafe ROYAL AVIATOR ELECTRONIC 2 GROUP</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Royal là hãng sản xuất máy pha cafe hàng đầu thế giới. Dòng máy pha cà phê mang thương hiệu ROYAL (Ý) được sử dụng tại rất nhiều nước trên thế giới. Thương hiệu thuộc sở hữu của công ty TNHH CBC Royal First với những đôi tay thợ máy cafe lành nghề nhất thế giới.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cà phê Royal AVIATOR Electronic – 2 Group được Việt Cafe nhập khẩu chính hãng từ hãng Royal, chúng tôi cam kết về chất lượng mỗi sản phẩm mà mình cung cấp. Uy tín của chúng tôi đã được chứng nhận trong suốt 6 năm phục vụ thị trường. Các sản phẩm máy đều có đầy đủ chứng từ nhập khẩu, chứng nhận xuất xứ sản phẩm và được bảo hành 18 tháng, bảo trì thường xuyên bởi Việt Cafe.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thông số kỹ thuật</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Kích thước:783× 607× 776mm</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Công suất: 3500W ~ 220-240V ~50Hz</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Trọng lượng: Kg</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dung tích bình đun: 14.0 Lt</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Họng làm cafe: 2</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Số vòi đánh hơi:</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Vòi nước nóng: 1</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thương hiệu: ROYAL</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Xuất xứ: Italia</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2. Ưu điểm vượt trội của ROYAL AVIATOR ELECTRONIC 2 GROUP</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Siêu máy pha cafe Royal AVIATOR Electronic – 2 Group – Lựa chọn hàng đầu của các quán cafe, nhà hàng lớn là phiên bản đột phá của Royal với thiết kế hiện đại, siêu ấn tượng, làm quầy bar trở nên đẳng cấp.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Công nghệ dẫn đầu trong giới Máy pha cafe</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Công nghệ TCI( kiểm soát nhiệt độc lập) giúp các Barista có thể tùy chỉnh cài đặt nhiệt độ của từng group với độ chính xác tối đa. Với phương châm đơn giản và trực giác, mỗi group có một màn hình led ấn tượng, hiển thị đầy đủ các thông số kỹ thuật cần thiết: thời gian, nhiệt độ…. tùy theo yêu cầu pha chế của từng Barista. Đặc biệt với thiết kế mạch giữ nhiệt trước khi pha chế giúp nhiệt độ nước luôn luôn ổn định, ngay cả khi thực hiện việc pha chế liên tục.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ PPI – Hệ thống kiểm soát áp suất mới: Barista có thể kiểm soát được áp suất khác nhau cho từng group, điều này cho phép chiết xuất café hoàn hảo, đúng chuẩn.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Hệ thống Công tắc dự phòng: Máy pha cà phê chuyên nghiệp cao cấp Aviator còn có công tắc dự phòng hỗ trợ hoạt động pha chế trong trường hợp lỗi group mà chưa xử lý được ngay.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">*Công suất khủng – Tính năng tuyệt vời:</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Dung tích bình đun lớn (14Lt) với mạch ổn nhiệt đảm bảo sự ổn định giúp barista pha chế nhanh, liên tục mà vẫn đảm bảo chất lượng đồ uống đồng đều.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Thiết kế tinh tế ở từng chi tiết nhỏ nhất: tay cầm mềm mại, độ nặng vừa phải mang lại cảm giác thoải mái, đèn chiếu sáng được trang bị ở mỗi group cho phép Barista cảm nhận rõ hơn chất lượng dòng chảy cà phê để có sự điều chỉnh phù hợp. Aviator an toàn với người sử dụng với các nút bấm đơn giản, thiết kế các bộ phận hợp lý</span></p><p><br></p>', 1, 0, 0, '2026-01-07 02:25:36', '2026-01-07 02:25:36', NULL, NULL, 16, 'active', 0, NULL, NULL, 0, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(183, 'Máy pha cà phê Dogaressa Electronic 2 groups', 'may-pha-ca-phe-dogaressa-electronic-2-groups', 'RO-TDUBH', NULL, '– Kích thước: 794 x 601 x 517 mm\r\n– Công suất: 3500W ~ 220-240V ~50Hz\r\n– Trọng lượng: 63 Kg\r\n– Dung tích bình đun: 11.0 Lt\r\n– Họng làm cafe: 2\r\n– Số vòi đánh hơi: 2\r\n– Vòi nước nóng: 1\r\n– Thương hiệu: ROYAL\r\n– Model: Electronic\r\n– Xuất xứ: Italia', NULL, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cafe Dogaressa 2 groups – Với thiết kế ấn tượng, tính năng ưu việt, chất lượng châu u, máy pha cafe chuyên nghiệp Dogaressa 2 họng là lựa chọn hàng đầu của các quán cafe, nhà hàng có lượng khách đông.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. Máy pha cafe DOGARESSA ELECTRONIC 2 GROUPS</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Royal là hãng sản xuất máy pha cafe hàng đầu thế giới. Dòng máy pha cà phê mang thương hiệu ROYAL (Ý) được sử dụng tại rất nhiều nước trên thế giới. Thương hiệu thuộc sở hữu của công ty TNHH CBC Royal First với những đôi tay thợ máy cafe lành nghề nhất thế giới.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Cảm nhận đầu tiên về máy pha cafe Royal Dogaressa của người tiêu dùng đó là vẻ ngoài tinh tế, thu hút. Thiết kế đơn giản, màu sắc nổi bật, sắc nét tạo điểm nhấn ấn tượng cho quầy bar.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thông số kỹ thuật</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Kích thước: 794 x 601 x 517 mm</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Công suất: 3500W ~ 220-240V ~50Hz</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Trọng lượng: 63 Kg</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dung tích bình đun: 11.0 Lt</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Họng làm cafe: 2</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Số vòi đánh hơi: 2</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Vòi nước nóng: 1</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thương hiệu: ROYAL</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Model: Electronic</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Xuất xứ: Italia</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2. Ưu điểm vượt trội của CỦA DOGARESSA ELECTRONIC 2 GROUPS</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Với công suất hoạt động 3500W, máy Royal Dogaressa có thể pha chế tới 150 ly Espresso chất lượng tuyệt hảo, cùng 1 hương vị. Với công suất và thiết kế như trên, đây là điều dễ hiểu khi máy Dogaressa có mặt tại nhiều quán cafe cao cấp trên toàn quốc. Đây cũng là dòng máy bán chạy nhất tại Việt Cafe.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thiết kế ấn tượng, thỏa mãn người dùng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Cảm nhận đầu tiên về máy pha cafe Royal Dogaressa của người tiêu dùng đó là vẻ ngoài tinh tế, thu hút. Thiết kế đơn giản, màu sắc nổi bật, sắc nét tạo điểm nhấn ấn tượng cho quầy bar.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Với công suất hoạt động 3500W, máy Royal Dogaressa có thể pha chế tới 150 ly Espresso chất lượng tuyệt hảo, cùng 1 hương vị. Với công suất và thiết kế như trên, đây là điều dễ hiểu khi máy Dogaressa có mặt tại nhiều quán cafe cao cấp trên toàn quốc. Đây cũng là dòng máy bán chạy nhất tại Việt Cafe.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Tuổi thọ lớn, công suất cao:</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Thiết kế bình đun dung tích 11Lt hoàn toàn bằng đồng để đảm bảo có thể chiết xuất cafe số lượng lớn, chất lượng đồng đều trong thời gian dài.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Tối ưu hóa từng chi tiết nhằm mang lại sản phẩm tốt chất, cho chất lượng tốt nhất hỗ trợ thành công của các đơn vị kinh doanh.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Điểm khác biệt với những máy cùng phân khúc:</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Dễ dàng di chuyển: Tuy cùng kích thước với các loại máy 2 họng khác nhưng DOGARESSA lại có trọng lượng nhẹ hơn nhiều so với các máy khác, nhờ đó mà việc vận chuyển cũng dễ dàng và nhanh chóng, tiết kiệm được chi phí và thời gian setup quán cafe của bạn.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Nhiều màu sắc để lựa chọn sao cho phù hợp nhất với không gian và phong cách của quán</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Royal là thương hiệu máy pha cafe hàng đầu thế giới nên rất khắt khe trong việc lựa chọn đơn vị phân phối sản phẩm ra thị trường. Không chỉ phải đáp ứng yếu tố về tay nghề kỹ thuật, việc bảo hành, bảo trì sản phẩm. Royal còn chọn đơn vị độc quyền theo tiêu chí: chất lượng đồ uống tốt, có bộ phận đào tạo training sử dụng máy, tạo ra những món đồ uống đúng tiêu chuẩn. Sau thời gian tìm hiểu chi tiết, Royal tin tưởng chọn Việt Cafe là đơn vị độc quyền phân phối duy nhất các sản phẩm máy xay, máy pha cafe Espresso trên thị trường Việt Nam.</span></p><p><br></p>', 1, 0, 0, '2026-01-07 02:26:50', '2026-01-07 02:26:50', NULL, NULL, 16, 'active', 0, 90000000.00, NULL, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(184, 'Máy pha cà phê Royal Synchro Electronic 1 Group', 'may-pha-ca-phe-royal-synchro-electronic-1-group', 'RO-TMSQZ', NULL, '– Kích thước:468 x 593 x 557 mm\r\n– Công suất: 2000W ~ 220-240V ~50Hz\r\n– Trọng lượng: 43Kg\r\n– Dung tích bình đun: 4.0 Lt\r\n– Họng làm cafe: 1\r\n– Số vòi đánh hơi: 1\r\n– Vòi nước nóng: 1\r\n– Thương hiệu: ROYAL\r\n– Model: Electronic\r\n– Xuất xứ: Italia', NULL, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Dòng máy SYNCHRO với thiết kế cá tính, hiện đại sẽ mang lại cho bạn những tách cà phê espresso với lớp creama dày và bọt sữa sánh mịn. và Synchro 1 group sẽ là lựa chọn hoàn hảo cho quán nhỏ.</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">1. Máy pha cafe TECNICA 1 GROUP</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Royal là hãng sản xuất máy pha cafe hàng đầu thế giới. Dòng máy pha cà phê mang thương hiệu ROYAL (Ý) được sử dụng tại rất nhiều nước trên thế giới. Thương hiệu thuộc sở hữu của công ty TNHH CBC Royal First với những đôi tay thợ máy cafe lành nghề nhất thế giới.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Máy pha cafe ROYAL SYNCHRO ELECTRONIC 1 GROUP với cấu tạo 1 họng làm cafe nên công suất của máy có thể đạt hơn 60 – 80 ly cafe trong 1 giờ nhờ dung tích bình đun lớn và sự tối ưu trong thiết kế.</span></p><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Thông số kỹ thuật</strong></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Kích thước:468 x 593 x 557 mm</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Công suất: 2000W ~ 220-240V ~50Hz</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Trọng lượng: 43Kg</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dung tích bình đun: 4.0 Lt</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Họng làm cafe: 1</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Số vòi đánh hơi: 1</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Vòi nước nóng: 1</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thương hiệu: ROYAL</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Model: Electronic</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Xuất xứ: Italia</span></p><h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">2. Ưu điểm vượt trội của TECNICA 1 GROUP</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">*Dễ dàng sử dụng, thân thiện người dùng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Sản phẩm an toàn, dễ dàng với người sử dụng với thao tác bấm đơn giản. Các đồ uống pha chế từ bộ máy cũng rất phong phú: cà phê pha máy như espresso (single/double), cappuccino, latte, americano</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Barista dễ dàng hơn khi thao tác khi pha chế nhờ vào thiết kế, bố trí các bộ phận hợp lý của máy. Khoang úp và sấy cốc sâu lòng, có thể đựng nhiều loại cốc khác nhau. Vòi hỏi núm vặn xoáy, quay 360 độ giúp thao tác đánh sữa hay sục các đồ nóng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">*Cấu tạo chắc chắn</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Máy pha cafe Royal Tecnica siêu bền, có khả năng chịu lực, chịu nhiệt, dễ dàng vệ sinh, an toàn cho người sử dụng và rất phù hợp với quán nhỏ.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Bình đun bằng đồng dung tích 4 lít giúp làm nóng nhanh khi pha chế và an toàn cho người sử dụng.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Thân máy pha cafe Tenica được làm bằng chất liệu thép không gỉ thân thiện, phù hợp với nhiều không gian.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Công tắc 1 nút khi bật lên, nếu nước chưa đủ nóng thì máy sẽ không cho chiết xuất cà phê.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">+ Đồng hồ tích hợp 2 trạng thái của áp suất bơm và áp suất hơi.</span></p><p><br></p>', 1, 0, 0, '2026-01-07 02:33:47', '2026-01-07 02:33:47', NULL, NULL, 16, 'active', 0, 58000000.00, NULL, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(185, 'Máy pha cà phê tự động Melitta Passione OT', 'may-pha-ca-phe-tu-dong-melitta-passione-ot', 'TD-UDTER', NULL, 'Pha cappuccino với một nút nhấn\r\nKích thước (WxHxD): 25.3 x 40 x 38 cm\r\nMàu: Bạc hoặc đen\r\nNgăn chứa nước: 1.2 L\r\nHộc chứa hạt: 125g\r\nĐiện áp/ Công suất: 220-240V/ 1,450W\r\nKhối lượng: 8.5 Kg\r\nCông suất: 35 – 40 tách espresso/ngày, 25 -30 tách cappuccino/ngày\r\nÁp suất bơm: Static, tối đa 15 bar\r\nDung tích thức uống: 25 – 220 ml\r\nThương hiệu Đức, xuất xứ Bồ Đào Nha', NULL, '<h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">Vì sao nên chọn mua Máy pha cà phê tự động Melitta Passione OT?</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đơn vị được thành lập từ năm 2013, cung cấp giải pháp tổng thể ngành F&amp;B. Chúng tôi tự hào là đơn vị cung cấp độc quyền của nhiều hãng máy móc thiết bị pha chế cafe, với mong muốn đem những sản phẩm chất lượng cho người Việt.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Tư vấn, hướng dẫn sử dụng máy và công thức pha cafe</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thời gian bảo hành: 18 tháng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dịch vụ bảo trì định kỳ 6 tháng/ lần</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Tặng kèm 1 Khóa Cafe Máy trị giá 5.000.000000 đồng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Ngoài ra, địa điểm lắp đặt máy pha cafe sẽ được các kỹ thuật viên của Việt Cafe tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng.</span></p><p><br></p>', 1, 0, 0, '2026-01-07 02:54:48', '2026-01-07 02:54:48', NULL, NULL, 18, 'active', 0, 30240000.00, NULL, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(186, 'Máy pha cà phê tự động Melitta Caffeo Solo', 'may-pha-ca-phe-tu-dong-melitta-caffeo-solo', 'TD-UNMZL', NULL, 'Máy pha cà phê espresso nhỏ gọn nhất\r\nKích thước (WxHxD): 20 x 32.5 x 45.5 cm\r\nMàu: Đen/ Bạc/Đỏ\r\nNgăn chứa nước: 1.2 L\r\nHộc chứa hạt: 125g\r\nĐiện áp/ Công suất: 220-240V/ 1,400W\r\nKhối lượng: 8.3 Kg\r\nHiển thị: Biểu tượng LED\r\nCông suất: 35 – 40 tách espresso/ngày\r\nĐiều chỉnh vòi cà phê: 135 mm\r\nĐiều chỉnh lượng cà phê phù hợp với tách\r\nThương hiệu: Đức\r\nXuất xứ: Bồ Đào Nha', NULL, '<h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">Vì sao nên chọn mua máy pha cà phê tự động Melitta Caffeo Solo?</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Cafe – Đơn vị được thành lập từ năm 2013, cung cấp giải pháp tổng thể ngành F&amp;B. Chúng tôi tự hào là đơn vị cung cấp độc quyền của nhiều hãng máy móc thiết bị pha chế cafe, với mong muốn đem những sản phẩm chất lượng cho người Việt.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Tư vấn, hướng dẫn sử dụng máy và công thức pha cafe</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thời gian bảo hành: 18 tháng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dịch vụ bảo trì định kỳ 6 tháng/ lần</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Tặng kèm 1 Khóa Cafe Máy trị giá 5.000.000000 đồng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Ngoài ra, địa điểm lắp đặt máy pha cafe sẽ được các kỹ thuật viên tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng.</span></p><p><br></p>', 1, 0, 0, '2026-01-07 03:02:26', '2026-01-07 03:02:26', NULL, NULL, 18, 'active', 0, 16200000.00, NULL, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL),
(187, 'Máy pha cà phê tự động Melitta Avanza Titan', 'may-pha-ca-phe-tu-dong-melitta-avanza-titan', 'TD-UOVKJ', NULL, '– Kích thước (WxHxD): 20 x 35.3 x 45.5 cm\r\n– Khối lượng: 8.3Kg\r\n– Điện áp/ Công suất: 220-240V/ 1,450W\r\n– Bình chứa nước: 1.5 L\r\n– Hộc chứa hạt: 250g', NULL, '<h2><strong style=\"background-color: rgb(255, 255, 255); color: rgb(75, 16, 9);\">Vì sao nên chọn mua máy pha cà phê tự động Melitta Avanza Titan?</strong></h2><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">Đơn vị được thành lập từ năm 2013, cung cấp giải pháp tổng thể ngành F&amp;B. Chúng tôi tự hào là đơn vị cung cấp độc quyền của nhiều hãng máy móc thiết bị pha chế cafe, với mong muốn đem những sản phẩm chất lượng cho người Việt.</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Tư vấn, hướng dẫn sử dụng máy và công thức pha cafe</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Thời gian bảo hành: 18 tháng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Dịch vụ bảo trì định kỳ 6 tháng/ lần</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Tặng kèm 1 Khóa Cafe Máy trị giá 5.000.000000 đồng</span></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);\">– Ngoài ra, địa điểm lắp đặt máy pha cafe sẽ được các kỹ thuật viên tư vấn và khảo sát trước khi tiến hành lắp đặt, để đảm bảo công việc được nhanh chóng, chuẩn xác và tiết kiệm thời gian cho khách hàng.</span></p><p><br></p>', 1, 0, 1, '2026-01-07 03:03:24', '2026-01-07 09:07:19', NULL, NULL, 18, 'active', 0, 21600000.00, NULL, 1, 0, NULL, NULL, 'company', 'credit,cod,bank_transfer', 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_attributes`
--

INSERT INTO `product_attributes` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(9, 'Trong lượng', 'text', '2025-12-31 20:11:12', '2025-12-31 20:11:12'),
(10, 'Kiểu Xay', 'text', '2025-12-31 20:14:01', '2026-01-06 15:34:36'),
(12, 'Màu sắc', 'text', '2026-01-07 02:55:33', '2026-01-07 02:55:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_attribute_values`
--

INSERT INTO `product_attribute_values` (`id`, `attribute_id`, `value`, `slug`, `created_at`, `updated_at`) VALUES
(47, 9, '50 g', '50-g', '2025-12-31 20:12:45', '2025-12-31 20:12:45'),
(48, 9, '100 g', '100-g', '2025-12-31 20:12:57', '2025-12-31 20:12:57'),
(49, 9, '150 g', '150-g', '2025-12-31 20:13:04', '2025-12-31 20:13:04'),
(50, 9, '200 g', '200-g', '2025-12-31 20:13:15', '2025-12-31 20:13:15'),
(51, 9, '250 g', '250-g', '2025-12-31 20:13:23', '2025-12-31 20:13:23'),
(52, 9, '300 g', '300-g', '2025-12-31 20:13:31', '2025-12-31 20:13:31'),
(53, 9, '350 g', '350-g', '2025-12-31 20:13:41', '2025-12-31 20:13:41'),
(54, 10, 'Pha phin', 'pha-phin', '2025-12-31 20:14:35', '2025-12-31 20:14:35'),
(55, 10, 'Pha máy Espresso', 'pha-may-espresso', '2025-12-31 20:14:57', '2025-12-31 20:14:57'),
(56, 10, 'Nguyên hạt', 'nguyen-hat', '2025-12-31 20:15:06', '2025-12-31 20:15:06'),
(58, 12, 'Bạc', 'bac', '2026-01-07 02:55:43', '2026-01-07 02:55:43'),
(59, 12, 'Bạc Sọc', 'bac-soc', '2026-01-07 02:55:51', '2026-01-07 02:55:51'),
(60, 12, 'Đen', 'den', '2026-01-07 02:55:57', '2026-01-07 02:55:57'),
(61, 12, 'Đỏ', 'do', '2026-01-07 02:56:02', '2026-01-07 02:56:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `url`, `is_primary`, `position`, `created_at`, `updated_at`, `product_variant_id`) VALUES
(4560, 167, '/storage/products/2026/01/07/MACAP-MX-MANUAL-600x599.png', 1, 1, '2026-01-07 01:36:57', '2026-01-07 01:36:57', NULL),
(4561, 168, '/storage/products/2026/01/07/Macap-MI20.png', 1, 1, '2026-01-07 01:38:44', '2026-01-07 01:38:44', NULL),
(4562, 169, '/storage/products/2026/01/07/may-nen-600x600.png', 1, 1, '2026-01-07 01:41:21', '2026-01-07 01:41:21', NULL),
(4564, 170, '/storage/products/2026/01/07/MACAP-F6-VIETCAFE-600x600.png', 1, 1, '2026-01-07 01:47:22', '2026-01-07 01:47:22', NULL),
(4565, 171, '/storage/products/2026/01/07/BINHCAFE-VIETCAFE-600x600.png', 1, 1, '2026-01-07 01:49:30', '2026-01-07 01:49:30', NULL),
(4566, 172, '/storage/products/2026/01/07/novo-600x600.webp', 1, 1, '2026-01-07 01:52:21', '2026-01-07 01:52:21', NULL),
(4567, 172, '/storage/products/2026/01/07/bravilor-cafetera-novo-03-600x540.jpg', 0, 2, '2026-01-07 01:52:21', '2026-01-07 01:52:21', NULL),
(4568, 172, '/storage/products/2026/01/07/bravilor-novo-Vietcafe-600x600.png', 0, 3, '2026-01-07 01:52:21', '2026-01-07 01:52:21', NULL),
(4574, 173, '/storage/products/2026/01/07/BRAVILOR-B5-vietcafe-600x600.png', 1, 1, '2026-01-07 02:02:54', '2026-01-07 02:02:54', NULL),
(4575, 176, '/storage/products/2026/01/07/may-pha-ca-phe-iberital-referent-2-groups-den2-600x599.jpg', 1, 1, '2026-01-07 02:07:15', '2026-01-07 02:07:15', NULL),
(4576, 176, '/storage/products/2026/01/07/may-pha-ca-phe-iberital-referent-2-groups-trang2-600x600.jpg', 0, 2, '2026-01-07 02:07:15', '2026-01-07 02:07:15', NULL),
(4577, 176, '/storage/products/2026/01/07/may-pha-ca-phe-iberital-referent-2-groups-do-bia-600x600-1.png', 0, 3, '2026-01-07 02:07:15', '2026-01-07 02:07:15', NULL),
(4578, 176, '/storage/products/2026/01/07/may-pha-ca-phe-iberital-referent-2-groups-do1-600x600-1.jpg', 0, 4, '2026-01-07 02:07:15', '2026-01-07 02:07:15', NULL),
(4579, 176, '/storage/products/2026/01/07/may-pha-ca-phe-iberital-referent-2-groups-den1-600x599.jpg', 0, 5, '2026-01-07 02:07:15', '2026-01-07 02:07:15', NULL),
(4580, 176, '/storage/products/2026/01/07/may-pha-ca-phe-iberital-referent-2-groups-trang1-600x600.jpg', 0, 6, '2026-01-07 02:07:15', '2026-01-07 02:07:15', NULL),
(4581, 176, '/storage/products/2026/01/07/may-pha-ca-phe-iberital-referent-2-groups-xanh1-600x600.jpg', 0, 7, '2026-01-07 02:07:15', '2026-01-07 02:07:15', NULL),
(4582, 176, '/storage/products/2026/01/07/may-pha-ca-phe-iberital-referent-2-groups-xanh2-600x599.jpg', 0, 8, '2026-01-07 02:07:15', '2026-01-07 02:07:15', NULL),
(4583, 177, '/storage/products/2026/01/07/IBERITAL-VISTA-600x600.png', 1, 1, '2026-01-07 02:08:55', '2026-01-07 02:08:55', NULL),
(4584, 177, '/storage/products/2026/01/07/z5258055043259_58b12073bd379c3562edfd35bca33d11-600x600.jpg', 0, 2, '2026-01-07 02:08:55', '2026-01-07 02:08:55', NULL),
(4585, 177, '/storage/products/2026/01/07/z5258055076063_1910084577d4dda075f178a87bf76a02-600x600.jpg', 0, 3, '2026-01-07 02:08:55', '2026-01-07 02:08:55', NULL),
(4586, 177, '/storage/products/2026/01/07/z5258055076500_faa3fa950ca67b90d559e2a96d7ecbee-600x600.jpg', 0, 4, '2026-01-07 02:08:55', '2026-01-07 02:08:55', NULL),
(4587, 178, '/storage/products/2026/01/07/IBERITAL-IB7-Elec-2-Group-600x600.png', 1, 1, '2026-01-07 02:09:49', '2026-01-07 02:09:49', NULL),
(4588, 179, '/storage/products/2026/01/07/IBERITAL-INTENZ-LUXURY-1-GROUP.png', 1, 1, '2026-01-07 02:11:42', '2026-01-07 02:11:42', NULL),
(4589, 180, '/storage/products/2026/01/07/may-pha-ca-phe-royal-synchro-t2-3-group-1-600x600.png', 1, 1, '2026-01-07 02:13:00', '2026-01-07 02:13:00', NULL),
(4590, 180, '/storage/products/2026/01/07/may-pha-ca-phe-royal-synchro-t2-3-group-1-600x600.jpg', 0, 2, '2026-01-07 02:13:00', '2026-01-07 02:13:00', NULL),
(4591, 180, '/storage/products/2026/01/07/may-pha-ca-phe-royal-synchro-t2-3-group-2-600x601.jpg', 0, 3, '2026-01-07 02:13:00', '2026-01-07 02:13:00', NULL),
(4592, 181, '/storage/products/2026/01/07/Generation-X-2-600x601.png', 1, 1, '2026-01-07 02:21:05', '2026-01-07 02:21:05', NULL),
(4593, 181, '/storage/products/2026/01/07/Generation-X-1-600x600.png', 0, 2, '2026-01-07 02:21:05', '2026-01-07 02:21:05', NULL),
(4594, 181, '/storage/products/2026/01/07/Generation-X-1-600x600.jpg', 0, 3, '2026-01-07 02:21:05', '2026-01-07 02:21:05', NULL),
(4595, 181, '/storage/products/2026/01/07/Generation-X-2.jpg', 0, 4, '2026-01-07 02:21:05', '2026-01-07 02:21:05', NULL),
(4596, 181, '/storage/products/2026/01/07/Generation-X-3.jpg', 0, 5, '2026-01-07 02:21:05', '2026-01-07 02:21:05', NULL),
(4597, 181, '/storage/products/2026/01/07/Generation-X-4.jpg', 0, 6, '2026-01-07 02:21:05', '2026-01-07 02:21:05', NULL),
(4598, 182, '/storage/products/2026/01/07/Royal-AVIATOR-3-600x600.png', 1, 1, '2026-01-07 02:25:36', '2026-01-07 02:25:36', NULL),
(4599, 182, '/storage/products/2026/01/07/Royal-AVIATOR-2-600x600.png', 0, 2, '2026-01-07 02:25:36', '2026-01-07 02:25:36', NULL),
(4600, 182, '/storage/products/2026/01/07/Royal-AVIATOR-1.jpg', 0, 3, '2026-01-07 02:25:36', '2026-01-07 02:25:36', NULL),
(4601, 182, '/storage/products/2026/01/07/Royal-AVIATOR-3.jpg', 0, 4, '2026-01-07 02:25:36', '2026-01-07 02:25:36', NULL),
(4602, 182, '/storage/products/2026/01/07/Royal-AVIATOR-4.jpg', 0, 5, '2026-01-07 02:25:36', '2026-01-07 02:25:36', NULL),
(4603, 182, '/storage/products/2026/01/07/Royal-AVIATOR-5-600x600.jpg', 0, 6, '2026-01-07 02:25:36', '2026-01-07 02:25:36', NULL),
(4604, 183, '/storage/products/2026/01/07/dogaressa-2gr-elect-black-r-600x600.png', 1, 1, '2026-01-07 02:26:50', '2026-01-07 02:26:50', NULL),
(4605, 183, '/storage/products/2026/01/07/dogaressa-2gr-electnovaro-white-r-600x599.png', 0, 2, '2026-01-07 02:26:50', '2026-01-07 02:26:50', NULL),
(4606, 183, '/storage/products/2026/01/07/dogaressa-2gr-back-green-600x599.jpg', 0, 3, '2026-01-07 02:26:50', '2026-01-07 02:26:50', NULL),
(4607, 183, '/storage/products/2026/01/07/dogaressa-2gr-switch-red-r.jpg', 0, 4, '2026-01-07 02:26:50', '2026-01-07 02:26:50', NULL),
(4608, 183, '/storage/products/2026/01/07/DOGARESSA-2-GROUP-2-Vietcafe.jpg', 0, 5, '2026-01-07 02:26:50', '2026-01-07 02:26:50', NULL),
(4609, 183, '/storage/products/2026/01/07/dogaressa-3gr-back-white.jpg', 0, 6, '2026-01-07 02:26:50', '2026-01-07 02:26:50', NULL),
(4610, 184, '/storage/products/2026/01/07/3b42d530-6a0a-4e01-bf78-8faa13553c55-600x600.png', 1, 1, '2026-01-07 02:33:47', '2026-01-07 02:33:47', NULL),
(4611, 184, '/storage/products/2026/01/07/3b42d530-6a0a-4e01-bf78-8faa13553c55-600x600-1.png', 0, 2, '2026-01-07 02:33:47', '2026-01-07 02:33:47', NULL),
(4612, 185, '/storage/products/2026/01/07/mmepasotb-s-202211301341-600x600-1.png', 1, 1, '2026-01-07 02:54:48', '2026-01-07 02:54:48', NULL),
(4613, 185, '/storage/products/2026/01/07/may-pha-ca-phe-caffeo-passione-ot-1551842409-600x600.png', 0, 2, '2026-01-07 02:54:48', '2026-01-07 02:54:48', NULL),
(4614, 186, '/storage/products/2026/01/07/mmesolo0b-s-r-d-2023120218071-600x600.png', 1, 1, '2026-01-07 03:02:26', '2026-01-07 03:02:26', NULL),
(4615, 186, '/storage/products/2026/01/07/mmesolo0b-s-r-d-2023120218071-600x600-1.png', 0, 3, '2026-01-07 03:02:26', '2026-01-07 03:02:26', 897),
(4616, 186, '/storage/products/2026/01/07/mmesolo0b-s-r-d-2023120218072.png', 0, 4, '2026-01-07 03:02:26', '2026-01-07 03:02:26', 898),
(4617, 187, '/storage/products/2026/01/07/mmeavanza-202211281351-600x600.png', 1, 1, '2026-01-07 03:03:24', '2026-01-07 03:03:24', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int UNSIGNED NOT NULL DEFAULT '0',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `compare_at_price` decimal(12,2) DEFAULT NULL,
  `cost` decimal(12,2) DEFAULT NULL,
  `barcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `inventory_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `stock`, `price`, `compare_at_price`, `cost`, `barcode`, `weight`, `inventory_quantity`, `is_default`, `is_active`, `created_at`, `updated_at`) VALUES
(872, 167, 'MA-RH4HP-DF', 0, 20000000.00, NULL, NULL, NULL, NULL, 1000, 1, 1, '2026-01-07 01:36:57', '2026-01-07 01:36:57'),
(873, 168, 'MA-RNZH1-DF', 0, 18000000.00, NULL, NULL, NULL, NULL, 1000, 1, 1, '2026-01-07 01:38:44', '2026-01-07 01:38:44'),
(874, 169, 'MA-RRCXP-DF', 0, 8500000.00, 10000000.00, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 01:41:21', '2026-01-07 01:41:21'),
(876, 170, 'MA-RYUS8-DF', 0, 7000000.00, NULL, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 01:47:22', '2026-01-07 01:47:22'),
(877, 171, 'BR-S1U2A-DF', 0, 600000.00, NULL, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 01:49:30', '2026-01-07 01:49:30'),
(878, 172, 'BR-S5HUU-DF', 0, 12000000.00, 15000000.00, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 01:52:21', '2026-01-07 01:52:21'),
(880, 173, 'BR-SAGJB-DF', 0, 110000000.00, NULL, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 02:02:54', '2026-01-07 02:02:54'),
(881, 176, 'BR-SONWT-DF', 0, 96000000.00, NULL, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 02:07:15', '2026-01-07 02:07:15'),
(882, 177, 'IB-SQSZJ-DF', 0, 240000000.00, NULL, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 02:08:55', '2026-01-07 02:08:55'),
(883, 178, 'IB-SRYSV-DF', 0, 79000000.00, NULL, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 02:09:49', '2026-01-07 02:09:49'),
(885, 179, 'IB-STZP8-DF', 0, 63000000.00, 68000000.00, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 02:11:42', '2026-01-07 02:11:42'),
(886, 180, 'RO-SW2EE-DF', 0, 0.00, NULL, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 02:13:00', '2026-01-07 02:13:00'),
(887, 166, 'CA-RCF3S-DF', 0, 347000.00, NULL, NULL, NULL, NULL, 1000, 1, 1, '2026-01-07 02:13:13', '2026-01-07 02:13:13'),
(889, 164, 'PR-R942V-DF', 0, 330000.00, NULL, NULL, NULL, NULL, 100, 1, 1, '2026-01-07 02:13:19', '2026-01-07 02:13:19'),
(892, 181, 'RO-T164P-DF', 0, 0.00, NULL, NULL, NULL, NULL, 0, 1, 1, '2026-01-07 02:21:05', '2026-01-07 02:21:05'),
(893, 182, 'RO-TC9PZ-DF', 0, 0.00, NULL, NULL, NULL, NULL, 0, 1, 1, '2026-01-07 02:25:36', '2026-01-07 02:25:36'),
(894, 183, 'RO-TDUBH-DF', 0, 90000000.00, NULL, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 02:26:50', '2026-01-07 02:26:50'),
(895, 184, 'RO-TMSQZ-DF', 0, 58000000.00, NULL, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 02:33:47', '2026-01-07 02:33:47'),
(896, 185, 'TD-UDTER-DF', 0, 30240000.00, NULL, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 02:54:48', '2026-01-07 02:54:48'),
(897, 186, 'TD-UNMZL-B-UNN02', 0, 16200000.00, NULL, NULL, NULL, NULL, 100, 0, 1, '2026-01-07 03:02:26', '2026-01-07 03:02:26'),
(898, 186, 'TD-UNMZL-E-UNN0P', 0, 16200000.00, NULL, NULL, NULL, NULL, 100, 0, 1, '2026-01-07 03:02:26', '2026-01-07 03:02:26'),
(899, 187, 'TD-UOVKJ-DF', 0, 21600000.00, NULL, NULL, NULL, NULL, 10, 1, 1, '2026-01-07 03:03:24', '2026-01-07 03:03:24'),
(900, 165, 'CA-RB1RR-DF', 0, 49900.00, 500000.00, NULL, NULL, NULL, 1000, 1, 1, '2026-01-07 03:05:19', '2026-01-07 03:05:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variant_options`
--

CREATE TABLE `product_variant_options` (
  `id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `attribute_value_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variant_options`
--

INSERT INTO `product_variant_options` (`id`, `variant_id`, `attribute_id`, `attribute_value_id`, `created_at`, `updated_at`) VALUES
(957, 897, 12, 58, '2026-01-07 03:02:26', '2026-01-07 03:02:26'),
(958, 898, 12, 60, '2026-01-07 03:02:26', '2026-01-07 03:02:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotion_rules`
--

CREATE TABLE `promotion_rules` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mix_match',
  `condition_json` text COLLATE utf8mb4_unicode_ci,
  `min_total_qty` int UNSIGNED NOT NULL DEFAULT '0',
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percent',
  `discount_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `requires_code` tinyint(1) NOT NULL DEFAULT '0',
  `promo_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `free_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `promotion_rules`
--

INSERT INTO `promotion_rules` (`id`, `name`, `type`, `condition_json`, `min_total_qty`, `discount_type`, `discount_value`, `requires_code`, `promo_code`, `starts_at`, `ends_at`, `is_active`, `free_shipping`, `created_at`, `updated_at`) VALUES
(2, 'test mã giảm giá', 'mix_match', '[887]', 3, 'amount', 30000.00, 1, 'testcode', NULL, NULL, 1, 0, '2026-01-05 22:53:13', '2026-01-07 17:28:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `reviewer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reviewer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','published','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `parent_id`, `product_id`, `user_id`, `reviewer_name`, `reviewer_email`, `rating`, `title`, `content`, `status`, `is_verified_purchase`, `created_at`, `updated_at`) VALUES
(25, NULL, 167, 4, 'Văn Minh Đỗ', 'vanminh.do0788@gmail.com', 4, NULL, 'sản phẩm tốt', 'published', 0, '2026-01-07 01:42:00', '2026-01-07 01:42:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('CiGmZi2vrJx9zOqcRQIpSyCxX8GNNDtM6NqP0kwg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSlNTUlRXdUpTMDR5aURoYTY1RnJFREt3RzJEdm5tTVk5UWZENXFkeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1767832420),
('x3G5jc1bQc0nnHcqxGkVfOUnkifMlFrLnMsJyb6P', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoidE5nOGtidlFBRjFHTHg2VjVoN2FxMGcyeXZZbTB6blhhajlGVjcxMCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjQ6ImNhcnQiO2E6MTp7czo1OiJpdGVtcyI7YTowOnt9fXM6MTU6InByb21vdGlvbl9jb2RlcyI7YToxOntpOjA7czo4OiJ0ZXN0Y29kZSI7fXM6MTQ6ImNoZWNrb3V0X2l0ZW1zIjthOjE6e2k6MDthOjk6e3M6MTA6InZhcmlhbnRfaWQiO2k6ODg3O3M6Mzoic2t1IjtzOjExOiJDQS1SQ0YzUy1ERiI7czo0OiJuYW1lIjtzOjMxOiJHT1RISUMgKDkwJSByb2J1c3RhLTEwJWFyYWJpY2EpIjtzOjU6InByaWNlIjtkOjM0NzAwMDtzOjg6InF1YW50aXR5IjtpOjU7czo3OiJvcHRpb25zIjthOjA6e31zOjU6ImltYWdlIjtOO3M6MzoibWF4IjtpOjEwMDA7czoxMDoicHJvZHVjdF9pZCI7aToxNjY7fX1zOjE1OiJjaGVja291dF9zb3VyY2UiO3M6NDoiY2FydCI7fQ==', 1767832460);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `tracking_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `carrier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `size_charts`
--

CREATE TABLE `size_charts` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height_min` int UNSIGNED DEFAULT NULL,
  `height_max` int UNSIGNED DEFAULT NULL,
  `weight_min` int UNSIGNED DEFAULT NULL,
  `weight_max` int UNSIGNED DEFAULT NULL,
  `recommended_size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint UNSIGNED NOT NULL,
  `movement_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` bigint UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `ref_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_id` bigint UNSIGNED DEFAULT NULL,
  `from_warehouse_id` bigint UNSIGNED DEFAULT NULL,
  `to_warehouse_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `movement_type`, `warehouse_id`, `product_variant_id`, `quantity`, `ref_type`, `ref_id`, `from_warehouse_id`, `to_warehouse_id`, `notes`, `created_at`, `updated_at`) VALUES
(351, 'adjustment', 1, 872, 1000, 'product_update_default', 167, NULL, NULL, 'Sync default variant stock', '2026-01-07 01:36:57', '2026-01-07 01:36:57'),
(352, 'adjustment', 1, 873, 1000, 'product_store_default', 168, NULL, NULL, 'Sync default variant stock', '2026-01-07 01:38:44', '2026-01-07 01:38:44'),
(353, 'adjustment', 1, 874, 10, 'product_store_default', 169, NULL, NULL, 'Sync default variant stock', '2026-01-07 01:41:21', '2026-01-07 01:41:21'),
(354, 'adjustment', 1, 876, 10, 'product_update_default', 170, NULL, NULL, 'Sync default variant stock', '2026-01-07 01:47:22', '2026-01-07 01:47:22'),
(355, 'adjustment', 1, 877, 10, 'product_store_default', 171, NULL, NULL, 'Sync default variant stock', '2026-01-07 01:49:30', '2026-01-07 01:49:30'),
(356, 'adjustment', 1, 878, 10, 'product_store_default', 172, NULL, NULL, 'Sync default variant stock', '2026-01-07 01:52:21', '2026-01-07 01:52:21'),
(357, 'adjustment', 1, 880, 10, 'product_update_default', 173, NULL, NULL, 'Sync default variant stock', '2026-01-07 02:02:54', '2026-01-07 02:02:54'),
(358, 'adjustment', 1, 881, 10, 'product_store_default', 176, NULL, NULL, 'Sync default variant stock', '2026-01-07 02:07:15', '2026-01-07 02:07:15'),
(359, 'adjustment', 1, 882, 10, 'product_store_default', 177, NULL, NULL, 'Sync default variant stock', '2026-01-07 02:08:55', '2026-01-07 02:08:55'),
(360, 'adjustment', 1, 883, 10, 'product_store_default', 178, NULL, NULL, 'Sync default variant stock', '2026-01-07 02:09:49', '2026-01-07 02:09:49'),
(361, 'adjustment', 1, 885, 10, 'product_update_default', 179, NULL, NULL, 'Sync default variant stock', '2026-01-07 02:11:42', '2026-01-07 02:11:42'),
(362, 'adjustment', 1, 886, 10, 'product_store_default', 180, NULL, NULL, 'Sync default variant stock', '2026-01-07 02:13:00', '2026-01-07 02:13:00'),
(363, 'adjustment', 1, 887, 1000, 'product_update_default', 166, NULL, NULL, 'Sync default variant stock', '2026-01-07 02:13:13', '2026-01-07 02:13:13'),
(365, 'adjustment', 1, 889, 100, 'product_update_default', 164, NULL, NULL, 'Sync default variant stock', '2026-01-07 02:13:19', '2026-01-07 02:13:19'),
(368, 'adjustment', 1, 894, 10, 'product_store_default', 183, NULL, NULL, 'Sync default variant stock', '2026-01-07 02:26:50', '2026-01-07 02:26:50'),
(369, 'adjustment', 1, 895, 10, 'product_store_default', 184, NULL, NULL, 'Sync default variant stock', '2026-01-07 02:33:47', '2026-01-07 02:33:47'),
(370, 'adjustment', 1, 896, 10, 'product_store_default', 185, NULL, NULL, 'Sync default variant stock', '2026-01-07 02:54:48', '2026-01-07 02:54:48'),
(371, 'adjustment', 1, 899, 10, 'product_store_default', 187, NULL, NULL, 'Sync default variant stock', '2026-01-07 03:03:24', '2026-01-07 03:03:24'),
(372, 'adjustment', 1, 900, 1000, 'product_update_default', 165, NULL, NULL, 'Sync default variant stock', '2026-01-07 03:05:19', '2026-01-07 03:05:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('customer','seller','manager','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `is_guest` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `avatar`, `bio`, `two_factor_enabled`, `email_verified_at`, `password`, `remember_token`, `role`, `is_guest`, `created_at`, `updated_at`) VALUES
(4, 'Văn Minh Đỗ', 'vanminh.do0788@gmail.com', '0375564838', 'avatars/XslOMwph37NObHtbAT63htMxk9eNhnvQVLBuZvf2.jpg', '123', 0, NULL, '$2y$12$yVvs6CY57nyZx3UlMo1CwuetOXhDN/8BPmpIViEJ.e95Vij45Zf86', 'gh1sY00RXXJ7Dy709kENzXKhaucKac0EvQXyxWHW897RkkPkYVFll4pHBitW', 'admin', 0, '2025-12-04 07:06:02', '2026-01-01 00:34:30'),
(5, 'Minh Béo', 'gamethu.vm@gmail.com', NULL, '/storage/avatars/K0ypVOxZqnvXUbO8IBlhSinn2YNXBi2KG7KqpBPG.png', NULL, 0, NULL, '$2y$12$o5T0Uh7ydyRLJpK4um7TZ.Ntt6wRaZsNJmhpTrvWodTkWHfF9p9du', NULL, 'customer', 0, '2025-12-16 16:56:45', '2025-12-25 05:57:22'),
(8, 'Hoàng Thị Huyền', 'huyenkhunglong1992@gmail.com', NULL, NULL, NULL, 0, NULL, '$2y$12$icHN2LnTs/NjmoJRCEVh6OAS6RulVN.jU9PTlMRCeK/51E3OZKHhS', NULL, 'customer', 0, '2025-12-16 22:13:52', '2025-12-16 22:13:52');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `volume_pricings`
--

CREATE TABLE `volume_pricings` (
  `id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `min_qty` int UNSIGNED NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `free_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `volume_pricings`
--

INSERT INTO `volume_pricings` (`id`, `product_variant_id`, `min_qty`, `price`, `is_active`, `free_shipping`, `created_at`, `updated_at`) VALUES
(6, 838, 5, 55000.00, 1, 0, '2026-01-05 22:35:20', '2026-01-05 22:35:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `code`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Main Warehouse', 'MAIN', '', 1, '2025-12-15 01:06:25', '2025-12-15 01:06:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `warehouse_inventories`
--

CREATE TABLE `warehouse_inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `on_hand` int UNSIGNED NOT NULL DEFAULT '0',
  `reserved` int UNSIGNED NOT NULL DEFAULT '0',
  `incoming` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `warehouse_inventories`
--

INSERT INTO `warehouse_inventories` (`id`, `warehouse_id`, `product_variant_id`, `on_hand`, `reserved`, `incoming`, `created_at`, `updated_at`) VALUES
(291, 1, 872, 1000, 0, 0, '2026-01-07 01:36:57', '2026-01-07 01:36:57'),
(292, 1, 873, 1000, 0, 0, '2026-01-07 01:38:44', '2026-01-07 01:38:44'),
(293, 1, 874, 10, 0, 0, '2026-01-07 01:41:21', '2026-01-07 01:41:21'),
(294, 1, 876, 10, 0, 0, '2026-01-07 01:47:22', '2026-01-07 01:47:22'),
(295, 1, 877, 10, 0, 0, '2026-01-07 01:49:30', '2026-01-07 01:49:30'),
(296, 1, 878, 10, 0, 0, '2026-01-07 01:52:21', '2026-01-07 01:52:21'),
(297, 1, 880, 10, 0, 0, '2026-01-07 02:02:54', '2026-01-07 02:02:54'),
(298, 1, 881, 10, 0, 0, '2026-01-07 02:07:15', '2026-01-07 02:07:15'),
(299, 1, 882, 10, 0, 0, '2026-01-07 02:08:55', '2026-01-07 02:08:55'),
(300, 1, 883, 10, 0, 0, '2026-01-07 02:09:49', '2026-01-07 02:09:49'),
(301, 1, 885, 10, 0, 0, '2026-01-07 02:11:42', '2026-01-07 02:11:42'),
(302, 1, 886, 10, 0, 0, '2026-01-07 02:13:00', '2026-01-07 02:13:00'),
(303, 1, 887, 1000, 0, 0, '2026-01-07 02:13:13', '2026-01-07 02:13:13'),
(305, 1, 889, 100, 0, 0, '2026-01-07 02:13:19', '2026-01-07 02:13:19'),
(308, 1, 894, 10, 0, 0, '2026-01-07 02:26:50', '2026-01-07 02:26:50'),
(309, 1, 895, 10, 0, 0, '2026-01-07 02:33:47', '2026-01-07 02:33:47'),
(310, 1, 896, 10, 0, 0, '2026-01-07 02:54:48', '2026-01-07 02:54:48'),
(311, 1, 899, 10, 0, 0, '2026-01-07 03:03:24', '2026-01-07 03:03:24'),
(312, 1, 900, 1000, 0, 0, '2026-01-07 03:05:19', '2026-01-07 03:05:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `product_variant_id`, `created_at`, `updated_at`) VALUES
(7, 5, 146, NULL, '2025-12-25 08:26:36', '2025-12-25 08:26:36'),
(50, 4, 153, NULL, '2026-01-06 19:25:41', '2026-01-06 19:25:41');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `bundles`
--
ALTER TABLE `bundles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bundles_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `bundle_items`
--
ALTER TABLE `bundle_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bundle_items_bundle_id_variant_id_unique` (`bundle_id`),
  ADD UNIQUE KEY `bundle_item_unique_bundle_product_variant` (`bundle_id`,`product_variant_id`),
  ADD KEY `bundle_items_product_variant_id_foreign` (`product_variant_id`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Chỉ mục cho bảng `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `collections_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `collection_images`
--
ALTER TABLE `collection_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `collection_images_collection_id_foreign` (`collection_id`);

--
-- Chỉ mục cho bảng `combos`
--
ALTER TABLE `combos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `combos_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `combo_lines`
--
ALTER TABLE `combo_lines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `combo_lines_combo_id_product_variant_id_unique` (`combo_id`,`product_variant_id`),
  ADD KEY `combo_lines_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `combo_lines_combo_id_index` (`combo_id`);

--
-- Chỉ mục cho bảng `customer_measurements`
--
ALTER TABLE `customer_measurements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_measurements_user_id_index` (`user_id`);

--
-- Chỉ mục cho bảng `customer_profiles`
--
ALTER TABLE `customer_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_profiles_user_id_unique` (`user_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_variant_id_foreign` (`product_variant_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `posts_author_id_foreign` (`author_id`),
  ADD KEY `posts_category_id_foreign` (`category_id`);

--
-- Chỉ mục cho bảng `post_categories`
--
ALTER TABLE `post_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_categories_slug_unique` (`slug`),
  ADD KEY `post_categories_parent_id_foreign` (`parent_id`);

--
-- Chỉ mục cho bảng `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_comments_post_id_foreign` (`post_id`),
  ADD KEY `post_comments_parent_id_foreign` (`parent_id`),
  ADD KEY `post_comments_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `post_post_tag`
--
ALTER TABLE `post_post_tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_post_tag_post_id_post_tag_id_unique` (`post_id`,`post_tag_id`),
  ADD KEY `post_post_tag_post_tag_id_foreign` (`post_tag_id`);

--
-- Chỉ mục cho bảng `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_tags_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_attributes_name_unique` (`name`);

--
-- Chỉ mục cho bảng `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_attribute_values_attribute_id_value_unique` (`attribute_id`,`value`),
  ADD KEY `product_attribute_values_attribute_id_index` (`attribute_id`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`),
  ADD KEY `product_images_product_variant_id_foreign` (`product_variant_id`);

--
-- Chỉ mục cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skus_sku_unique` (`sku`),
  ADD KEY `skus_product_id_sku_index` (`product_id`,`sku`);

--
-- Chỉ mục cho bảng `product_variant_options`
--
ALTER TABLE `product_variant_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variant_options_variant_id_attribute_id_unique` (`variant_id`,`attribute_id`),
  ADD KEY `product_variant_options_attribute_id_foreign` (`attribute_id`),
  ADD KEY `product_variant_options_attribute_value_id_foreign` (`attribute_value_id`);

--
-- Chỉ mục cho bảng `promotion_rules`
--
ALTER TABLE `promotion_rules`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_parent_id_foreign` (`parent_id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipments_tracking_number_unique` (`tracking_number`),
  ADD KEY `shipments_order_id_foreign` (`order_id`);

--
-- Chỉ mục cho bảng `size_charts`
--
ALTER TABLE `size_charts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `size_charts_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `stock_movements_product_variant_id_foreign` (`product_variant_id`);

--
-- Chỉ mục cho bảng `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Chỉ mục cho bảng `volume_pricings`
--
ALTER TABLE `volume_pricings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `volume_pricings_product_variant_id_min_qty_unique` (`product_variant_id`,`min_qty`);

--
-- Chỉ mục cho bảng `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouses_code_unique` (`code`);

--
-- Chỉ mục cho bảng `warehouse_inventories`
--
ALTER TABLE `warehouse_inventories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouse_inventories_warehouse_id_product_variant_id_unique` (`warehouse_id`,`product_variant_id`),
  ADD KEY `warehouse_inventories_product_variant_id_foreign` (`product_variant_id`);

--
-- Chỉ mục cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_id_product_id_product_variant_id_unique` (`user_id`,`product_id`,`product_variant_id`),
  ADD KEY `wishlists_user_id_index` (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `bundles`
--
ALTER TABLE `bundles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `bundle_items`
--
ALTER TABLE `bundle_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `collections`
--
ALTER TABLE `collections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `collection_images`
--
ALTER TABLE `collection_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `combos`
--
ALTER TABLE `combos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `combo_lines`
--
ALTER TABLE `combo_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `customer_measurements`
--
ALTER TABLE `customer_measurements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `customer_profiles`
--
ALTER TABLE `customer_profiles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `post_categories`
--
ALTER TABLE `post_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `post_post_tag`
--
ALTER TABLE `post_post_tag`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `post_tags`
--
ALTER TABLE `post_tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT cho bảng `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4618;

--
-- AUTO_INCREMENT cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=901;

--
-- AUTO_INCREMENT cho bảng `product_variant_options`
--
ALTER TABLE `product_variant_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=959;

--
-- AUTO_INCREMENT cho bảng `promotion_rules`
--
ALTER TABLE `promotion_rules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `size_charts`
--
ALTER TABLE `size_charts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=373;

--
-- AUTO_INCREMENT cho bảng `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `volume_pricings`
--
ALTER TABLE `volume_pricings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `warehouse_inventories`
--
ALTER TABLE `warehouse_inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;

--
-- AUTO_INCREMENT cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `bundle_items`
--
ALTER TABLE `bundle_items`
  ADD CONSTRAINT `bundle_items_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bundle_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `collection_images`
--
ALTER TABLE `collection_images`
  ADD CONSTRAINT `collection_images_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `combo_lines`
--
ALTER TABLE `combo_lines`
  ADD CONSTRAINT `combo_lines_combo_id_foreign` FOREIGN KEY (`combo_id`) REFERENCES `combos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `combo_lines_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `customer_profiles`
--
ALTER TABLE `customer_profiles`
  ADD CONSTRAINT `customer_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `post_categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `post_categories`
--
ALTER TABLE `post_categories`
  ADD CONSTRAINT `post_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `post_categories` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `post_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `post_comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `post_post_tag`
--
ALTER TABLE `post_post_tag`
  ADD CONSTRAINT `post_post_tag_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_post_tag_post_tag_id_foreign` FOREIGN KEY (`post_tag_id`) REFERENCES `post_tags` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_images_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `skus_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_variant_options`
--
ALTER TABLE `product_variant_options`
  ADD CONSTRAINT `product_variant_options_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_options_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `product_attribute_values` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_options_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `size_charts`
--
ALTER TABLE `size_charts`
  ADD CONSTRAINT `size_charts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `warehouse_inventories`
--
ALTER TABLE `warehouse_inventories`
  ADD CONSTRAINT `warehouse_inventories_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `warehouse_inventories_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
