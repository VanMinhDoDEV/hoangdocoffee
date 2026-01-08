<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_images')) {
            Schema::table('product_images', function (Blueprint $table) {
                if (Schema::hasColumn('product_images', 'variant_id')) {
                    try { $table->dropForeign(['variant_id']); } catch (\Throwable $e) {}
                    $table->foreign('variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_images')) {
            Schema::table('product_images', function (Blueprint $table) {
                if (Schema::hasColumn('product_images', 'variant_id')) {
                    try { $table->dropForeign(['variant_id']); } catch (\Throwable $e) {}
                    $table->foreign('variant_id')->references('id')->on('variants')->cascadeOnDelete();
                }
            });
        }
    }
};
