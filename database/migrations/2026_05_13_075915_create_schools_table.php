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
            $table->uuid('id')->primary(); // Tetap menggunakan UUID untuk konsistensi
            $table->string('name');
            $table->string('npsn', 8)->unique();
            $table->string('nis')->nullable();
            $table->string('nss')->nullable();
            $table->string('nds')->nullable();
            $table->text('address');
            $table->string('postal_code', 5);
            $table->string('village'); // Kelurahan
            $table->string('district'); // Kecamatan
            $table->string('city'); // Kota/Kabupaten
            $table->string('province');
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('school_logo_path')->nullable();
            $table->string('government_logo_path')->nullable();
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
