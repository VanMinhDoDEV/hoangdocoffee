<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('variant_attributes')) {
            Schema::dropIfExists('variant_attributes');
        }
        if (Schema::hasTable('variant_values')) {
            Schema::dropIfExists('variant_values');
        }
        if (Schema::hasTable('variants')) {
            Schema::dropIfExists('variants');
        }
    }

    public function down(): void
    {
        // No-op: các bảng legacy đã bị loại bỏ trong kiến trúc mới
    }
};

