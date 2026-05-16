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
        Schema::create('registration_zonasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('registration_data_id')->constrained('registration_data')->onDelete('cascade');

            // Koordinat Rumah Geospasial
            $table->decimal('house_latitude', 10, 7);
            $table->decimal('house_longitude', 10, 7);

            // Jarak Ukur Udara Garis Lurus (dalam satuan meter)
            $table->double('calculated_distance_meters');

            // Snapshot Alamat Khusus KK Pendaftaran (antisipasi koreksi manual)
            $table->string('address_street')->nullable();
            $table->string('village')->nullable();
            $table->string('subdistrict')->nullable();
            $table->string('city')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_zonasis');
    }
};
