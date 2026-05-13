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
        Schema::create('announcements', function (Blueprint $col) {
            $col->uuid('id')->primary();
            $col->string('title');
            $col->string('slug')->unique();
            $col->longText('content');
            $col->string('category');

            // Kolom Tambahan untuk Link Eksternal/Dokumen
            $col->string('action_link')->nullable(); // URL tujuan (misal: link Google Drive atau route internal)
            $col->string('action_label')->nullable(); // Teks tombol (misal: "Download SK Kelulusan")

            $col->boolean('is_urgent')->default(false);
            $col->boolean('is_active')->default(true);
            $col->string('author_id')->default('Admin SPMB')->nullable();
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
