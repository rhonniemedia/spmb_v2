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
        Schema::create('spmb_steps', function (Blueprint $col) {
            $col->uuid('id')->primary();
            $col->string('title');
            $col->string('slug');
            $col->text('description');
            $col->string('period_text');
            $col->dateTime('start_date')->nullable();
            $col->dateTime('end_date')->nullable();
            $col->integer('step_order')->default(1);
            $col->string('icon')->nullable();
            $col->string('color')->nullable();
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spmb_steps');
    }
};
