<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('volume_pricings') && !Schema::hasColumn('volume_pricings', 'free_shipping')) {
            Schema::table('volume_pricings', function (Blueprint $table) {
                $table->boolean('free_shipping')->default(false)->after('is_active');
            });
        }
        if (Schema::hasTable('promotion_rules') && !Schema::hasColumn('promotion_rules', 'free_shipping')) {
            Schema::table('promotion_rules', function (Blueprint $table) {
                $table->boolean('free_shipping')->default(false)->after('is_active');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('volume_pricings') && Schema::hasColumn('volume_pricings', 'free_shipping')) {
            Schema::table('volume_pricings', function (Blueprint $table) {
                $table->dropColumn('free_shipping');
            });
        }
        if (Schema::hasTable('promotion_rules') && Schema::hasColumn('promotion_rules', 'free_shipping')) {
            Schema::table('promotion_rules', function (Blueprint $table) {
                $table->dropColumn('free_shipping');
            });
        }
    }
};

