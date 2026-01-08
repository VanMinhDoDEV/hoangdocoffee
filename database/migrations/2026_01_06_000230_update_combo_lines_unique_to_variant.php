<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('combo_lines')) return;

        // Drop old unique via raw SQL only if exists to avoid MySQL 1091
        $oldIdx = DB::select("
            SELECT COUNT(*) AS c
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'combo_lines'
              AND INDEX_NAME = 'combo_lines_combo_id_product_id_unique'
        ");
        if (!empty($oldIdx) && intval($oldIdx[0]->c ?? 0) > 0) {
            DB::statement('DROP INDEX combo_lines_combo_id_product_id_unique ON combo_lines');
        }

        // Ensure new unique exists
        if (Schema::hasColumn('combo_lines', 'product_variant_id')) {
            $newIdx = DB::select("
                SELECT COUNT(*) AS c
                FROM information_schema.STATISTICS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'combo_lines'
                  AND INDEX_NAME = 'combo_lines_combo_id_product_variant_id_unique'
            ");
            if (empty($newIdx) || intval($newIdx[0]->c ?? 0) === 0) {
                Schema::table('combo_lines', function (Blueprint $table) {
                    $table->unique(['combo_id', 'product_variant_id'], 'combo_lines_combo_id_product_variant_id_unique');
                });
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('combo_lines')) return;

        // Drop new unique if exists
        $newIdx = DB::select("
            SELECT COUNT(*) AS c
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'combo_lines'
              AND INDEX_NAME = 'combo_lines_combo_id_product_variant_id_unique'
        ");
        if (!empty($newIdx) && intval($newIdx[0]->c ?? 0) > 0) {
            DB::statement('DROP INDEX combo_lines_combo_id_product_variant_id_unique ON combo_lines');
        }

        // Restore old unique only if product_id exists
        if (Schema::hasColumn('combo_lines', 'product_id')) {
            $oldIdx = DB::select("
                SELECT COUNT(*) AS c
                FROM information_schema.STATISTICS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'combo_lines'
                  AND INDEX_NAME = 'combo_lines_combo_id_product_id_unique'
            ");
            if (empty($oldIdx) || intval($oldIdx[0]->c ?? 0) === 0) {
                Schema::table('combo_lines', function (Blueprint $table) {
                    $table->unique(['combo_id', 'product_id'], 'combo_lines_combo_id_product_id_unique');
                });
            }
        }
    }
};
