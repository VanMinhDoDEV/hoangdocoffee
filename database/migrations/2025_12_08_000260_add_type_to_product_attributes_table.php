<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_attributes') && !Schema::hasColumn('product_attributes', 'type')) {
            Schema::table('product_attributes', function (Blueprint $table) {
                $table->string('type', 20)->default('text')->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_attributes') && Schema::hasColumn('product_attributes', 'type')) {
            Schema::table('product_attributes', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};

