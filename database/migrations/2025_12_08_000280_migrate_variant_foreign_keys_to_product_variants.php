<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // product_images: add product_variant_id and backfill
        Schema::table('product_images', function (Blueprint $table) {
            if (!Schema::hasColumn('product_images', 'product_variant_id')) {
                $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete()->after('product_id');
            }
        });
        // Backfill using join on sku
        DB::statement('UPDATE product_images pi INNER JOIN variants v ON pi.variant_id = v.id INNER JOIN product_variants pv ON pv.sku = v.sku SET pi.product_variant_id = pv.id');
        // Drop old FK and column
        Schema::table('product_images', function (Blueprint $table) {
            if (Schema::hasColumn('product_images', 'variant_id')) {
                $table->dropConstrainedForeignId('variant_id');
            }
        });

        // bundle_items: add product_variant_id and backfill
        Schema::table('bundle_items', function (Blueprint $table) {
            if (!Schema::hasColumn('bundle_items', 'product_variant_id')) {
                $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete()->after('bundle_id');
            }
        });
        DB::statement('UPDATE bundle_items bi INNER JOIN variants v ON bi.variant_id = v.id INNER JOIN product_variants pv ON pv.sku = v.sku SET bi.product_variant_id = pv.id');
        Schema::table('bundle_items', function (Blueprint $table) {
            if (Schema::hasColumn('bundle_items', 'variant_id')) {
                $table->dropConstrainedForeignId('variant_id');
            }
            // Ensure unique constraint on (bundle_id, product_variant_id)
            $table->unique(['bundle_id', 'product_variant_id'], 'bundle_item_unique_bundle_product_variant');
        });

        // order_items: add product_variant_id and backfill
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('order_items', 'product_variant_id')) {
                    $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete()->after('order_id');
                }
            });
            DB::statement('UPDATE order_items oi INNER JOIN variants v ON oi.variant_id = v.id INNER JOIN product_variants pv ON pv.sku = v.sku SET oi.product_variant_id = pv.id');
            Schema::table('order_items', function (Blueprint $table) {
                if (Schema::hasColumn('order_items', 'variant_id')) {
                    $table->dropConstrainedForeignId('variant_id');
                }
            });
        }
    }

    public function down(): void
    {
        // Recreate old variant_id columns nullable without constraints
        Schema::table('product_images', function (Blueprint $table) {
            if (!Schema::hasColumn('product_images', 'variant_id')) {
                $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
            }
            if (Schema::hasColumn('product_images', 'product_variant_id')) {
                $table->dropConstrainedForeignId('product_variant_id');
            }
        });
        Schema::table('bundle_items', function (Blueprint $table) {
            if (!Schema::hasColumn('bundle_items', 'variant_id')) {
                $table->unsignedBigInteger('variant_id')->nullable()->after('bundle_id');
            }
            if (Schema::hasColumn('bundle_items', 'product_variant_id')) {
                $table->dropConstrainedForeignId('product_variant_id');
            }
            $table->dropUnique('bundle_item_unique_bundle_product_variant');
        });
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('order_items', 'variant_id')) {
                    $table->unsignedBigInteger('variant_id')->nullable()->after('order_id');
                }
                if (Schema::hasColumn('order_items', 'product_variant_id')) {
                    $table->dropConstrainedForeignId('product_variant_id');
                }
            });
        }
    }
};
