<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'compare_at_price')) {
                $table->decimal('compare_at_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('product_variants', 'cost')) {
                $table->decimal('cost', 10, 2)->nullable()->after('compare_at_price');
            }
            if (!Schema::hasColumn('product_variants', 'barcode')) {
                $table->string('barcode')->nullable()->after('cost');
            }
            if (!Schema::hasColumn('product_variants', 'weight')) {
                $table->decimal('weight', 10, 2)->nullable()->after('barcode');
            }
            if (!Schema::hasColumn('product_variants', 'inventory_quantity')) {
                $table->unsignedInteger('inventory_quantity')->default(0)->after('weight');
            }
            if (!Schema::hasColumn('product_variants', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('inventory_quantity');
            }
        });

        if (Schema::hasColumn('product_variants', 'stock') && Schema::hasColumn('product_variants', 'inventory_quantity')) {
            DB::statement('UPDATE product_variants SET inventory_quantity = stock');
        }
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'is_default')) {
                $table->dropColumn('is_default');
            }
            if (Schema::hasColumn('product_variants', 'inventory_quantity')) {
                $table->dropColumn('inventory_quantity');
            }
            if (Schema::hasColumn('product_variants', 'weight')) {
                $table->dropColumn('weight');
            }
            if (Schema::hasColumn('product_variants', 'barcode')) {
                $table->dropColumn('barcode');
            }
            if (Schema::hasColumn('product_variants', 'cost')) {
                $table->dropColumn('cost');
            }
            if (Schema::hasColumn('product_variants', 'compare_at_price')) {
                $table->dropColumn('compare_at_price');
            }
        });
    }
};

