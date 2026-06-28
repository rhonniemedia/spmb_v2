<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personal_data', function (Blueprint $table) {
            // Data Kesehatan
            $table->integer('height')->nullable()->after('condition_description');
            $table->integer('weight')->nullable()->after('height');
            $table->string('medical_history', 100)->nullable()->after('weight');

            // Minat & Bakat
            $table->string('interest_art', 100)->nullable()->after('medical_history');
            $table->string('interest_sport', 100)->nullable()->after('interest_art');
            $table->string('interest_organization', 100)->nullable()->after('interest_sport');
            $table->string('extracurricular_choice', 100)->nullable()->after('interest_organization');
            $table->string('fl2sn_category', 100)->nullable()->after('extracurricular_choice');
            $table->string('o2sn_category', 100)->nullable()->after('fl2sn_category');
        });
    }

    public function down(): void
    {
        Schema::table('personal_data', function (Blueprint $table) {
            $table->dropColumn([
                'height',
                'weight',
                'medical_history',
                'interest_art',
                'interest_sport',
                'interest_organization',
                'extracurricular_choice',
                'fl2sn_category',
                'o2sn_category'
            ]);
        });
    }
};
