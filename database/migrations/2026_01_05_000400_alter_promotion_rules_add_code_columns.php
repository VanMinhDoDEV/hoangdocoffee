<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('promotion_rules', function (Blueprint $table) {
            if (!Schema::hasColumn('promotion_rules', 'requires_code')) {
                $table->boolean('requires_code')->default(false)->after('discount_value');
            }
            if (!Schema::hasColumn('promotion_rules', 'promo_code')) {
                $table->string('promo_code', 100)->nullable()->after('requires_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('promotion_rules', function (Blueprint $table) {
            if (Schema::hasColumn('promotion_rules', 'promo_code')) {
                $table->dropColumn('promo_code');
            }
            if (Schema::hasColumn('promotion_rules', 'requires_code')) {
                $table->dropColumn('requires_code');
            }
        });
    }
};

