<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sku_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sku_id')->constrained('skus')->cascadeOnDelete();
            $table->string('code'); // ví dụ: color, size, material
            $table->string('value'); // ví dụ: #FFFFFF, XL, Cotton
            $table->string('type')->nullable(); // text, color_hex, number, ...
            $table->timestamps();

            $table->unique(['sku_id', 'code']);
            $table->index(['code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sku_values');
    }
};

