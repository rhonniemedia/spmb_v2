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
        Schema::create('registration_afirmasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('registration_data_id')->constrained('registration_data')->onDelete('cascade');

            // --- DATA SKTM (WAJIB) ---
            $table->string('sktm_number');

            // --- DATA KARTU BANSOS (OPSIONAL) ---
            $table->boolean('has_social_card')->default(false);
            $table->enum('card_type', ['pkh', 'kip', 'kps', 'dtks', 'lain'])->nullable();
            $table->string('card_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_afirmasis');
    }
};
