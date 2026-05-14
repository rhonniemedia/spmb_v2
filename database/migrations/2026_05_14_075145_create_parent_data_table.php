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
        Schema::create('parent_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('personal_data_id')->constrained('personal_data')->onDelete('cascade');

            // --- BASIC INFO ---
            $table->text('name_encrypted');
            $table->enum('relationship', ['father', 'mother', 'guardian']);
            $table->enum('living_status', ['alive', 'deceased'])->default('alive');

            // --- IDENTITY (Encrypted + Hash) ---
            $table->text('nik_encrypted')->nullable();
            $table->string('nik_hash')->nullable()->index();

            // --- ADDITIONAL INFO (Plain for Statistics) ---
            $table->string('birth_year', 4)->nullable();
            $table->string('occupation')->nullable();
            $table->string('education')->nullable(); // SD, SMP, SMA, S1, etc
            $table->string('income_range')->nullable(); // Rentang penghasilan

            // --- CONTACT & ADDRESS ---
            $table->text('phone_number_encrypted')->nullable();
            $table->string('phone_number_hash')->nullable()->index();
            $table->text('address_encrypted')->nullable(); // Jika alamat ortu beda dengan siswa

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_data');
    }
};
