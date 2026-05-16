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
        Schema::create('admission_paths', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // Contoh: Jalur Reguler
            $table->string('subtitle'); // Contoh: Pendaftaran umum tanpa syarat khusus
            $table->text('description');
            $table->json('tags'); // Menyimpan array tags seperti ["Terbuka umum", "Langsung ke pilih jurusan"]
            $table->integer('quota_percentage'); // Contoh: 10, 50, 25, 15
            $table->string('color_theme')->nullable(); // Opsional: Untuk class Tailwind (red, green, orange, blue)
            $table->string('icon')->default('fa-route'); // Class fontawesome: fa-user-graduate, dll
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_paths');
    }
};
