<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update combo_lines table
        Schema::table('combo_lines', function (Blueprint $table) {
            // Drop product_id FK and column if present
            if (Schema::hasColumn('combo_lines', 'product_id')) {
                $fkExists = DB::select("
                    SELECT COUNT(*) AS c
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = 'combo_lines'
                      AND CONSTRAINT_NAME = 'combo_lines_product_id_foreign'
                ");
                if (!empty($fkExists) && intval($fkExists[0]->c ?? 0) > 0) {
                    DB::statement('ALTER TABLE combo_lines DROP FOREIGN KEY combo_lines_product_id_foreign');
                }
                // Drop combo_id FK temporarily to allow unique drop
                $comboFkExists = DB::select("
                    SELECT COUNT(*) AS c
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = 'combo_lines'
                      AND CONSTRAINT_NAME = 'combo_lines_combo_id_foreign'
                ");
                if (!empty($comboFkExists) && intval($comboFkExists[0]->c ?? 0) > 0) {
                    DB::statement('ALTER TABLE combo_lines DROP FOREIGN KEY combo_lines_combo_id_foreign');
                }
                // Drop unique index combo_id_product_id if exists
                $idxExists = DB::select("
                    SELECT COUNT(*) AS c
                    FROM information_schema.STATISTICS
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = 'combo_lines'
                      AND INDEX_NAME = 'combo_lines_combo_id_product_id_unique'
                ");
                if (!empty($idxExists) && intval($idxExists[0]->c ?? 0) > 0) {
                    DB::statement('DROP INDEX combo_lines_combo_id_product_id_unique ON combo_lines');
                }
                // Ensure a standalone index on combo_id exists for FK before re-adding FK
                $comboIdxExists = DB::select("
                    SELECT COUNT(*) AS c
                    FROM information_schema.STATISTICS
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = 'combo_lines'
                      AND COLUMN_NAME = 'combo_id'
                      AND INDEX_NAME = 'combo_lines_combo_id_index'
                ");
                if (empty($comboIdxExists) || intval($comboIdxExists[0]->c ?? 0) === 0) {
                    $table->index('combo_id');
                }
                // Re-add combo_id FK if it was dropped
                $comboFkExistsAgain = DB::select("
                    SELECT COUNT(*) AS c
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = 'combo_lines'
                      AND CONSTRAINT_NAME = 'combo_lines_combo_id_foreign'
                ");
                if (empty($comboFkExistsAgain) || intval($comboFkExistsAgain[0]->c ?? 0) === 0) {
                    DB::statement('ALTER TABLE combo_lines ADD CONSTRAINT combo_lines_combo_id_foreign FOREIGN KEY (combo_id) REFERENCES combos(id) ON DELETE CASCADE');
                }
                // Finally drop column
                $table->dropColumn('product_id');
            }
            // Ensure product_variant_id exists
            if (!Schema::hasColumn('combo_lines', 'product_variant_id')) {
                $table->unsignedBigInteger('product_variant_id')->after('combo_id');
                $table->index('product_variant_id', 'combo_lines_product_variant_id_index');
            }
            // Ensure unique(combo_id, product_variant_id)
            $newIdxExists = DB::select("
                SELECT COUNT(*) AS c
                FROM information_schema.STATISTICS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'combo_lines'
                  AND INDEX_NAME = 'combo_lines_combo_id_product_variant_id_unique'
            ");
            if (empty($newIdxExists) || intval($newIdxExists[0]->c ?? 0) === 0) {
                $table->unique(['combo_id', 'product_variant_id']);
            }
        });

        // Update volume_pricings table
        Schema::table('volume_pricings', function (Blueprint $table) {
            // Drop product_id FK and column if present
            if (Schema::hasColumn('volume_pricings', 'product_id')) {
                $fkExists = DB::select("
                    SELECT COUNT(*) AS c
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = 'volume_pricings'
                      AND CONSTRAINT_NAME = 'volume_pricings_product_id_foreign'
                ");
                if (!empty($fkExists) && intval($fkExists[0]->c ?? 0) > 0) {
                    DB::statement('ALTER TABLE volume_pricings DROP FOREIGN KEY volume_pricings_product_id_foreign');
                }
                // Drop unique(product_id, min_qty) if exists
                $idxExists = DB::select("
                    SELECT COUNT(*) AS c
                    FROM information_schema.STATISTICS
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = 'volume_pricings'
                      AND INDEX_NAME = 'volume_pricings_product_id_min_qty_unique'
                ");
                if (!empty($idxExists) && intval($idxExists[0]->c ?? 0) > 0) {
                    DB::statement('DROP INDEX volume_pricings_product_id_min_qty_unique ON volume_pricings');
                }
                // Drop column
                $table->dropColumn('product_id');
            }
            // Ensure product_variant_id exists
            if (!Schema::hasColumn('volume_pricings', 'product_variant_id')) {
                $table->unsignedBigInteger('product_variant_id')->after('id');
                $table->index('product_variant_id', 'volume_pricings_product_variant_id_index');
            }
            // Ensure unique(product_variant_id, min_qty)
            $newIdxExists = DB::select("
                SELECT COUNT(*) AS c
                FROM information_schema.STATISTICS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'volume_pricings'
                  AND INDEX_NAME = 'volume_pricings_product_variant_id_min_qty_unique'
            ");
            if (empty($newIdxExists) || intval($newIdxExists[0]->c ?? 0) === 0) {
                $table->unique(['product_variant_id', 'min_qty']);
            }
        });
    }

    public function down()
    {
        // Revert combo_lines table
        Schema::table('combo_lines', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
            $table->dropUnique(['combo_id', 'product_variant_id']);
            $table->dropColumn('product_variant_id');
            
            // We need to handle the combo_id FK and index dance in reverse?
            // Probably not strictly necessary for down() to be perfect if we don't plan to rollback, 
            // but let's try to be correct.
            // We kept combo_id column, just dropped/re-added FK.
            
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unique(['combo_id', 'product_id']);
        });

        // Revert volume_pricings table
        Schema::table('volume_pricings', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
            $table->dropUnique(['product_variant_id', 'min_qty']);
            $table->dropColumn('product_variant_id');

            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unique(['product_id', 'min_qty']);
        });
    }
};
