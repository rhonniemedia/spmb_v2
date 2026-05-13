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
        Schema::create('faqs', function (Blueprint $col) {
            $col->uuid('id')->primary();
            // Relasi foreign key juga harus bertipe UUID
            $col->foreignUuid('faq_category_id')->constrained('faq_categories')->cascadeOnDelete();
            $col->text('question');
            $col->longText('answer');
            $col->boolean('is_published')->default(true);
            $col->integer('view_count')->default(0);
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
