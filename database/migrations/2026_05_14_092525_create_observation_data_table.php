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
        Schema::create('observation_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('registration_id')->unique()->constrained('registration_data')->onDelete('cascade');

            // --- GATE 2 STATUS ---
            $table->enum('observation_status', ['pending', 'passed', 'failed'])->default('pending');

            // --- PHYSICAL CHECK (Only Yes/No conditions) ---
            $table->enum('hearing_check', ['yes', 'no'])->default('no');
            $table->enum('vision_check', ['yes', 'no'])->default('no');
            $table->enum('color_blind_check', ['yes', 'no'])->default('no');
            $table->enum('physical_activity', ['yes', 'no'])->default('no');

            // --- HARD CONSTRAINTS (Disqualification) ---
            $table->enum('tattoo', ['yes', 'no'])->default('no');
            $table->enum('tattoo_scar', ['yes', 'no'])->default('no');
            $table->enum('piercing', ['yes', 'no'])->default('no');

            // --- ADDITIONAL PHYSICAL INFO ---
            $table->enum('keloid', ['yes', 'no'])->default('no');
            $table->enum('minor_disability', ['yes', 'no'])->default('no');
            $table->enum('aid_tool', ['yes', 'no'])->default('no');

            // --- CATEGORY SCORES (Observer's Judgement) ---
            // Menggunakan unsignedTinyInteger karena nilainya hanya 0-100 (maksimal daya tampung TinyInteger adalah 255)
            $table->unsignedTinyInteger('physical_score')->nullable();
            $table->unsignedTinyInteger('special_trait_score')->nullable();
            $table->unsignedTinyInteger('achievement_score')->nullable();

            // --- SUMMARY ---
            $table->unsignedSmallInteger('total_score')->default(0);
            $table->text('observation_notes')->nullable();

            // --- AUDIT ---
            $table->foreignUuid('observer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignUuid('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observation_data');
    }
};
