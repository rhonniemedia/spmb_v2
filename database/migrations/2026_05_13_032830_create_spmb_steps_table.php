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
            $col->integer('step_order')->default(1);
            $col->string('title');
            $col->string('slug')->unique();
            $col->text('description');
            $col->string('icon')->nullable();
            $col->string('color')->nullable();

            // Periode
            $col->string('period_text')->nullable();
            $col->dateTime('start_date')->nullable();
            $col->dateTime('end_date')->nullable();

            // Konten tambahan
            $col->json('tags')->nullable();
            $col->string('note')->nullable();
            $col->string('alert_text')->nullable();

            // UI flags
            $col->boolean('show_statuses')->default(false);
            $col->boolean('show_result_badges')->default(false);
            $col->boolean('is_highlight')->default(false);

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
