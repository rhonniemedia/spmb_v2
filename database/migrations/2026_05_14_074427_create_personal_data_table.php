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
        Schema::create('personal_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');

            // --- GROUP 1: CORE IDENTITIES (Strictly Required) ---
            $table->string('full_name');
            $table->string('nick_name');
            $table->enum('gender', ['L', 'P']);

            $table->text('nisn_encrypted');
            $table->string('nisn_hash')->unique();

            $table->text('nik_encrypted');
            $table->string('nik_hash')->index();

            $table->text('pob_encrypted');
            $table->text('dob_encrypted');
            $table->string('dob_hash')->index();

            $table->text('religion_encrypted');
            $table->string('religion_hash')->index();

            $table->integer('child_order');
            $table->integer('number_of_siblings');

            $table->string('blood_type', 3)->nullable();

            // --- GROUP 2: CONTACT & ADDRESS (Nullable) ---
            $table->text('email_encrypted')->nullable();
            $table->string('email_hash')->nullable()->index();

            $table->text('phone_number_encrypted')->nullable();

            $table->text('address_encrypted')->nullable();
            $table->text('rt_encrypted')->nullable();
            $table->text('rw_encrypted')->nullable();
            $table->text('village_encrypted')->nullable();

            $table->text('district_encrypted')->nullable();
            $table->string('district_hash')->nullable()->index();

            $table->text('regency_encrypted')->nullable();
            $table->text('province_encrypted')->nullable();
            $table->text('postal_code_encrypted')->nullable();

            $table->string('residence_type')->nullable();
            $table->string('transportation')->nullable();
            $table->string('distance_to_school')->nullable();

            // --- GROUP 3: PREVIOUS EDUCATION (Nullable) ---
            $table->string('previous_school')->nullable();
            $table->string('previous_school_npsn')->nullable();
            $table->string('previous_school_status')->nullable();
            $table->string('previous_school_city')->nullable();
            $table->string('previous_school_province')->nullable();
            $table->string('graduation_certificate_number')->nullable();
            $table->string('graduation_year', 4)->nullable();

            // --- GROUP 4: SPECIAL CONDITIONS & STATUS (Nullable/Default) ---
            $table->enum('is_special_condition', ['yes', 'no'])->default('no');
            $table->string('special_condition_type')->nullable()->default('none');
            $table->text('condition_description')->nullable();
            $table->string('photo')->nullable();
            $table->enum('profile_status', ['draft', 'final'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_data');
    }
};
