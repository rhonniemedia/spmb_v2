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
        Schema::create('registration_achievements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('registration_data_id')->constrained('registration_data')->onDelete('cascade');

            // Jenis Prestasi
            $table->enum('achievement_type', ['kejuaraan', 'tahfiz', 'kepemimpinan']);

            // Atribut kondisional pilihan detail
            $table->enum('level', ['internasional', 'nasional', 'provinsi', 'kabupaten'])->nullable();
            $table->enum('curation_type', ['simt_pusprenas', 'dikbudprov'])->nullable();
            $table->string('leadership_position')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_achievements');
    }
};
