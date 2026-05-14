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
            $table->string('name'); // Contoh: Prestasi, Reguler, Afirmasi
            $table->string('code')->unique(); // Contoh: J-PRES, J-REG
            $table->text('description')->nullable();
            $table->integer('quota_percentage')->nullable(); // Opsional: jika kuota per jalur dihitung persen
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
