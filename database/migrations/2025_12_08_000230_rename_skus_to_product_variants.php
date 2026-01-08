<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('skus')) {
            Schema::rename('skus', 'product_variants');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_variants')) {
            Schema::rename('product_variants', 'skus');
        }
    }
};

