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
        Schema::create('re_registration_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('registration_data_id')
                ->unique() // 1 pendaftar = 1 record daftar ulang
                ->constrained('registration_data')
                ->onDelete('cascade');

            // ── Track per-step ─────────────────────────────────────────
            $table->timestamp('announced_at')->nullable();       // Pengumuman dibaca/dikonfirmasi
            $table->timestamp('confirmed_at')->nullable();       // Konfirmasi Kesediaan oleh siswa
            $table->timestamp('re_registered_at')->nullable();   // Berkas diserahkan / daftar ulang
            $table->timestamp('verified_at')->nullable();        // Verifikasi selesai oleh panitia
            $table->timestamp('completed_at')->nullable();       // Proses selesai

            // ── Status verifikasi berkas daftar ulang ──────────────────
            $table->enum('data_status', ['incomplete', 'complete'])->default('incomplete');
            $table->enum('verification_status', ['pending', 'processing', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->foreignUuid('verified_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('re_registration_data');
    }
};
