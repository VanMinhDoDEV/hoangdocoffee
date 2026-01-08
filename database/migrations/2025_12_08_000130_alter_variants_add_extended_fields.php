<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->decimal('discounted_price', 10, 2)->nullable()->after('price');
            $table->decimal('weight', 10, 2)->nullable()->after('stock');
            $table->string('dimensions')->nullable()->after('weight');
            $table->string('barcode')->nullable()->after('sku');
            $table->string('status')->default('active')->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->dropColumn(['discounted_price', 'weight', 'dimensions', 'barcode', 'status']);
        });
    }
};

