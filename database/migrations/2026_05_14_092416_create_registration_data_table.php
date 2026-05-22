<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('personal_data_id')->constrained('personal_data')->onDelete('cascade');
            $table->foreignUuid('admission_path_id')->nullable()->constrained('admission_paths');

            $table->string('registration_number', 30)->nullable()->unique();

            // --- PILIHAN JURUSAN (PILIHAN 1, 2, 3) ---
            $table->foreignUuid('choice_1')->nullable()->constrained('concentrations');
            $table->foreignUuid('choice_2')->nullable()->constrained('concentrations');
            $table->foreignUuid('choice_3')->nullable()->constrained('concentrations');

            // --- DATA NILAI (DARI STEP 1: NILAI & TKA) ---
            // Nilai Rapor Per Semester (Menggunakan decimal agar presisi)
            $table->decimal('report_sem_1', 5, 2)->nullable();
            $table->decimal('report_sem_2', 5, 2)->nullable();
            $table->decimal('report_sem_3', 5, 2)->nullable();
            $table->decimal('report_sem_4', 5, 2)->nullable();
            $table->decimal('report_sem_5', 5, 2)->nullable();
            $table->decimal('report_average', 5, 2)->nullable();

            // Nilai Tes Kemampuan Akademik (TKA)
            $table->decimal('tka_math', 5, 2)->nullable();
            $table->decimal('tka_indonesian', 5, 2)->nullable();
            $table->decimal('tka_average', 5, 2)->nullable();

            // --- VERIFIKASI PANITIA ---
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->foreignUuid('verified_by')->nullable()->constrained('users')->onDelete('set null');

            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_data');
    }
};
