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
            $col->text('description');
            $col->string('period_text');
            $col->date('start_date')->nullable();
            $col->date('end_date')->nullable();
            $col->enum('status', ['done', 'active', 'pending'])->default('pending');
            $col->integer('step_order')->default(1);
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
