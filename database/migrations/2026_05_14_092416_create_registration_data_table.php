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
        Schema::create('registration_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('personal_data_id')->constrained('personal_data')->onDelete('cascade');

            // --- GROUP 1: ADMISSION PATH ---
            $table->foreignUuid('admission_path_id')->constrained('admission_paths');

            // --- GROUP 2: DOCUMENTS ---
            $table->string('diploma_file')->nullable();
            $table->string('graduation_letter_file')->nullable();
            $table->string('report_card_file')->nullable();
            $table->string('birth_certificate_file')->nullable();

            // --- GROUP 3: MAJOR CHOICES ---
            $table->foreignUuid('choice_1')->constrained('concentrations');
            $table->foreignUuid('choice_2')->constrained('concentrations');
            $table->foreignUuid('choice_3')->constrained('concentrations');

            // --- NEW GROUP: ACHIEVEMENTS ---
            $table->enum('achievement_level', [
                'internasional',
                'nasional',
                'provinsi',
                'kabupaten'
            ])->nullable();

            $table->enum('curation_type', [
                'puspresnas', // Refers to SIMT/Puspresnas
                'dikbud' // Refers to Dikbudprov
            ])->nullable();

            // --- GROUP 4: VERIFICATION (GATE 1) ---
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->foreignUuid('verified_by')->nullable()->constrained('users')->onDelete('set null');

            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_data');
    }
};
