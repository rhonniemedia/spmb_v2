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

            // 1. Tambahkan 'peringkat' ke dalam pilihan enum jenis prestasi
            $table->enum('achievement_type', ['kejuaraan', 'tahfiz', 'kepemimpinan', 'peringkat']);

            // 2. Detail Tingkat (Hanya untuk Kejuaraan)
            $table->enum('level', ['internasional', 'nasional', 'provinsi', 'kabupaten'])->nullable();

            // [DIHAPUS] Kolom curation_type dihapus karena kurasi sudah ditiadakan di UI
            // $table->enum('curation_type', ['simt_pusprenas', 'dikbudprov'])->nullable();

            // 3. Detail Jabatan (Hanya untuk Kepemimpinan)
            $table->string('leadership_position')->nullable();

            // 4. Tambahkan kolom JSON untuk menyimpan objek peringkat tiap semester
            // Contoh isi data: {"1":"1","2":"","3":"2","4":"","5":"3"}
            $table->json('class_ranks')->nullable();

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
