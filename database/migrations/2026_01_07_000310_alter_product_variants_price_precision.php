<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_variants')) return;
        try {
            DB::statement('ALTER TABLE product_variants MODIFY price DECIMAL(12,2) NOT NULL DEFAULT 0');
        } catch (\Throwable $e) {}
        if (Schema::hasColumn('product_variants', 'compare_at_price')) {
            try {
                DB::statement('ALTER TABLE product_variants MODIFY compare_at_price DECIMAL(12,2) NULL');
            } catch (\Throwable $e) {}
        }
        if (Schema::hasColumn('product_variants', 'cost')) {
            try {
                DB::statement('ALTER TABLE product_variants MODIFY cost DECIMAL(12,2) NULL');
            } catch (\Throwable $e) {}
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('product_variants')) return;
        try {
            DB::statement('ALTER TABLE product_variants MODIFY price DECIMAL(10,2) NOT NULL DEFAULT 0');
        } catch (\Throwable $e) {}
        if (Schema::hasColumn('product_variants', 'compare_at_price')) {
            try {
                DB::statement('ALTER TABLE product_variants MODIFY compare_at_price DECIMAL(10,2) NULL');
            } catch (\Throwable $e) {}
        }
        if (Schema::hasColumn('product_variants', 'cost')) {
            try {
                DB::statement('ALTER TABLE product_variants MODIFY cost DECIMAL(10,2) NULL');
            } catch (\Throwable $e) {}
        }
    }
};
