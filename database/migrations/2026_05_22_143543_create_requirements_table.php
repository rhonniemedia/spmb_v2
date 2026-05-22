<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requirements', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('category');
            $table->string('name');
            $table->string('slug')->unique(); // ── TAMBAHAN JALUR SLUG UNIK ──
            $table->text('description')->nullable();
            $table->boolean('is_mandatory')->default(true);

            $table->string('icon')->nullable();
            $table->string('color_theme')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirements');
    }
};
