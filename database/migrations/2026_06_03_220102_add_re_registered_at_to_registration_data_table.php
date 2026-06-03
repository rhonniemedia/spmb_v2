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
        Schema::table('registration_data', function (Blueprint $table) {
            // Assuming it's a timestamp or datetime column. Adjust the type if necessary.
            $table->timestamp('re_registered_at')->nullable()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('registration_data', function (Blueprint $table) {
            $table->dropColumn('re_registered_at');
        });
    }
};
