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
        Schema::create('registration_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Menghubungkan ke tabel registrasi pendaftar
            $table->foreignUuid('registration_data_id')
                ->constrained('registration_data')
                ->onDelete('cascade');

            // Menghubungkan ke master syarat dokumen (Ijazah, SKL, dll)
            $table->foreignUuid('requirement_id')
                ->constrained('requirements')
                ->onDelete('cascade');

            // Path file yang diunggah oleh siswa
            $table->string('file_path')->nullable();

            // Status verifikasi per dokumen
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();

            $table->timestamps();

            // Proteksi: 1 pendaftar hanya boleh mengunggah 1 file per jenis requirement
            $table->unique(['registration_data_id', 'requirement_id'], 'reg_docs_req_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_documents');
    }
};
