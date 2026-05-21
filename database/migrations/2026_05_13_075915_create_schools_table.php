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
        Schema::create('schools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('npsn', 8)->unique();
            $table->string('nis')->nullable();
            $table->string('nss')->nullable();
            $table->string('nds')->nullable();
            $table->text('address');
            $table->string('postal_code', 5);
            $table->string('village');
            $table->string('district');
            $table->string('city');
            $table->string('province');
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('school_logo_path')->nullable();
            $table->string('government_logo_path')->nullable();

            // Tambahkan kolom baru di sini (Menggunakan Pendekatan JSON)
            $table->json('whatsapp_numbers')->nullable();
            $table->json('social_media')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
