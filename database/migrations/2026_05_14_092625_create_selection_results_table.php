<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('selection_results', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Tanpa ->unique() agar bisa menyimpan histori per batch
            $table->foreignUuid('registration_id')->constrained('registration_data')->onDelete('cascade');

            // Kolom baru untuk melacak jalur seleksi/penempatan aktual
            $table->foreignUuid('path_id')->nullable()->constrained('admission_paths')->nullOnDelete();

            // Nomor sesi penjenjangan (batch ke-1, 2, 3, dst)
            $table->unsignedSmallInteger('batch')->default(1);

            $table->enum('status', ['process', 'accepted', 'rejected'])->default('process');

            // Kelulusan
            $table->foreignUuid('accepted_concentration_id')->nullable()->constrained('concentrations');
            $table->integer('accepted_in_choice')->nullable(); // 1 / 2 / 3

            // --- SKOR BERBOBOT ---
            // Rapor 40% + TKA 20% + Observasi 30% + Prestasi 10%
            $table->decimal('score_rapor', 5, 2)->default(0);
            $table->decimal('score_tka', 5, 2)->default(0);
            $table->decimal('score_observasi', 5, 2)->default(0);
            $table->decimal('score_prestasi', 5, 2)->default(0);
            $table->decimal('final_score', 6, 2)->default(0);

            // --- RANKING ---
            $table->integer('rank_in_path')->nullable();
            $table->integer('rank_in_concentration')->nullable();

            // --- AUDIT PENJENJANGAN ---
            $table->foreignUuid('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();

            $table->text('selection_notes')->nullable();
            $table->timestamps();

            // Index untuk mempercepat query hasil terbaru
            $table->index(['registration_id', 'batch']);
            $table->index('batch');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selection_results');
    }
};
