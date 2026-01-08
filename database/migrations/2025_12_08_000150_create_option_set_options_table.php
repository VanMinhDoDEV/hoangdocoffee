<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('option_set_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_set_id')->constrained('option_sets')->cascadeOnDelete();
            $table->foreignId('option_id')->constrained('options')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['option_set_id', 'option_id']);
            $table->index(['option_set_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_set_options');
    }
};

