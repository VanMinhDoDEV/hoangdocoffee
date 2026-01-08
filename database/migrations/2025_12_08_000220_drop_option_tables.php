<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('variant_values')) {
            Schema::dropIfExists('variant_values');
        }
        if (Schema::hasTable('option_set_options')) {
            Schema::dropIfExists('option_set_options');
        }
        if (Schema::hasTable('option_values')) {
            Schema::dropIfExists('option_values');
        }
        if (Schema::hasTable('options')) {
            Schema::dropIfExists('options');
        }
        if (Schema::hasTable('option_sets')) {
            Schema::dropIfExists('option_sets');
        }
    }

    public function down(): void
    {
        // No-op: các bảng option* đã bị loại bỏ trong kiến trúc mới
    }
};

