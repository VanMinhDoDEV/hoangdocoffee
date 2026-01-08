<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sku_values')) {
            Schema::rename('sku_values', 'variant_attributes');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('variant_attributes')) {
            Schema::rename('variant_attributes', 'sku_values');
        }
    }
};

