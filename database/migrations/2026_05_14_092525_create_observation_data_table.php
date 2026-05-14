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

            // --- PHYSICAL CHECK & SCORES ---
            $table->enum('hearing_check', ['yes', 'no'])->default('no');
            $table->unsignedTinyInteger('hearing_score')->default(0);

            $table->enum('vision_check', ['yes', 'no'])->default('no');
            $table->unsignedTinyInteger('vision_score')->default(0);

            $table->enum('color_blind_check', ['yes', 'no'])->default('no');
            $table->unsignedTinyInteger('color_blind_score')->default(0);

            $table->enum('physical_activity', ['yes', 'no'])->default('no');
            $table->unsignedTinyInteger('physical_activity_score')->default(0);

            // --- HARD CONSTRAINTS (DISKUALIFIKASI) ---
            $table->enum('tattoo', ['yes', 'no'])->default('no');
            $table->unsignedTinyInteger('tattoo_score')->default(0);

            $table->enum('tattoo_scar', ['yes', 'no'])->default('no');
            $table->unsignedTinyInteger('tattoo_scar_score')->default(0);

            $table->enum('piercing', ['yes', 'no'])->default('no');
            $table->unsignedTinyInteger('piercing_score')->default(0);

            // --- ADDITIONAL PHYSICAL INFO ---
            $table->enum('keloid', ['yes', 'no'])->default('no');
            $table->unsignedTinyInteger('keloid_score')->default(0);

            $table->enum('minor_disability', ['yes', 'no'])->default('no');
            $table->unsignedTinyInteger('minor_disability_score')->default(0);

            $table->enum('aid_tool', ['yes', 'no'])->default('no');
            $table->unsignedTinyInteger('aid_tool_score')->default(0);

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
