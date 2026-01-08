<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // combos: add free_shipping
        Schema::table('combos', function (Blueprint $table) {
            if (!Schema::hasColumn('combos', 'free_shipping')) {
                $table->boolean('free_shipping')->default(false)->after('is_active');
            }
        });

        // volume_pricings: add free_shipping
        Schema::table('volume_pricings', function (Blueprint $table) {
            if (!Schema::hasColumn('volume_pricings', 'free_shipping')) {
                $table->boolean('free_shipping')->default(false)->after('is_active');
            }
        });

        // promotion_rules: add free_shipping
        Schema::table('promotion_rules', function (Blueprint $table) {
            if (!Schema::hasColumn('promotion_rules', 'free_shipping')) {
                $table->boolean('free_shipping')->default(false)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            if (Schema::hasColumn('combos', 'free_shipping')) {
                $table->dropColumn('free_shipping');
            }
        });

        Schema::table('volume_pricings', function (Blueprint $table) {
            if (Schema::hasColumn('volume_pricings', 'free_shipping')) {
                $table->dropColumn('free_shipping');
            }
        });

        Schema::table('promotion_rules', function (Blueprint $table) {
            if (Schema::hasColumn('promotion_rules', 'free_shipping')) {
                $table->dropColumn('free_shipping');
            }
        });
    }
};

