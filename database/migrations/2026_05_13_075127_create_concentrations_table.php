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
        Schema::create('concentrations', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID untuk keamanan data sekolah
            $table->string('name'); // Nama Konsentrasi Keahlian
            $table->string('alias')->unique(); // Singkatan (misal: TKJ, AKL)
            $table->string('code')->unique(); // Kode program keahlian
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concentrations');
    }
};
