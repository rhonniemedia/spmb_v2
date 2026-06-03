<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Admin yang melakukan aksi (nullable: bisa aksi sistem)
            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Pendaftar yang terkait (nullable: aksi sistem tanpa subjek)
            $table->foreignUuid('registration_data_id')
                ->nullable()
                ->constrained('registration_data')
                ->nullOnDelete();

            // Jenis aksi — sesuaikan dengan alur SPMB
            $table->enum('action', [
                'verified',           // dokumen/registrasi diverifikasi
                'rejected',           // registrasi ditolak
                'document_rejected',  // dokumen spesifik ditolak
                'document_verified',  // dokumen spesifik diverifikasi
                're_registered',      // peserta daftar ulang
                'submitted',          // peserta submit pendaftaran
                'observation_passed', // lolos observasi
                'observation_failed', // tidak lolos observasi
                'accepted',           // diterima di jurusan
                'system',             // aksi otomatis sistem
            ]);

            // Judul singkat yang tampil di feed
            // Contoh: "Dokumen Ahmad Fauzi diverifikasi"
            $table->string('description');

            // Detail tambahan untuk baris kedua di feed
            // Contoh: "Ijazah & rapor dinyatakan valid — RPL"
            $table->string('context')->nullable();

            $table->timestamps(); // created_at = waktu aksi
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
