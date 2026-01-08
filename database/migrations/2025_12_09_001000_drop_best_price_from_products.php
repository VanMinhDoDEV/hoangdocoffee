<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('products', 'best_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('best_price');
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'best_price')) {
                $table->decimal('best_price', 12, 2)->nullable()->after('price');
            }
        });
    }
};

