<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('vendor')->nullable();
            $table->string('collection')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('status')->default('active');
            $table->boolean('tax')->default(false);
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('best_price', 12, 2)->nullable();
            $table->decimal('discounted_price', 12, 2)->nullable();
            $table->boolean('in_stock')->default(true);
            $table->decimal('shipping_weight', 10, 2)->nullable();
            $table->string('shipping_dimensions')->nullable();
            $table->string('payment_method')->nullable();
            $table->boolean('is_fragile')->default(false);
            $table->boolean('is_biodegradable')->default(false);
            $table->boolean('is_frozen')->default(false);
            $table->string('max_temp')->nullable();
            $table->date('expiry_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'vendor', 'collection', 'category_id', 'status', 'tax',
                'price', 'best_price', 'discounted_price', 'in_stock',
                'shipping_weight', 'shipping_dimensions', 'payment_method',
                'is_fragile', 'is_biodegradable', 'is_frozen', 'max_temp', 'expiry_date',
            ]);
        });
    }
};

