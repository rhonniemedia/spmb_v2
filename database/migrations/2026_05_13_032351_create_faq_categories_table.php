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
        Schema::create('faq_categories', function (Blueprint $col) {
            $col->uuid('id')->primary(); // Menggunakan UUID sebagai Primary Key
            $col->string('name');
            $col->string('slug')->unique();
            $col->string('icon')->default('fa-circle-question');
            $col->integer('sort_order')->default(0);
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_categories');
    }
};
