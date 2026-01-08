<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('combo_lines') && !Schema::hasColumn('combo_lines', 'product_variant_id')) {
            Schema::table('combo_lines', function (Blueprint $table) {
                $table->foreignId('product_variant_id')->nullable()->after('combo_id')->constrained('product_variants')->cascadeOnDelete();
                // Keep existing product_id and unique(combo_id, product_id) for backward compatibility
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('combo_lines') && Schema::hasColumn('combo_lines', 'product_variant_id')) {
            Schema::table('combo_lines', function (Blueprint $table) {
                $table->dropForeign(['product_variant_id']);
                $table->dropColumn('product_variant_id');
            });
        }
    }
};

