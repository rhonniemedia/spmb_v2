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
        Schema::create('selection_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('registration_id')->unique()->constrained('registration_data')->onDelete('cascade');

            $table->enum('status', ['process', 'accepted', 'rejected'])->default('process');

            // Kelulusan
            $table->foreignUuid('accepted_concentration_id')->nullable()->constrained('concentrations');
            $table->integer('accepted_in_choice')->nullable();

            $table->text('selection_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selection_results');
    }
};
