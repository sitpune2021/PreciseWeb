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
        Schema::table('machine_records', function (Blueprint $table) {
            $table->integer('hrs')->nullable()->after('end_time');
            $table->integer('time_taken')->nullable()->after('hrs');
        });
    }

    public function down(): void
    {
        Schema::table('machine_records', function (Blueprint $table) {
            $table->dropColumn(['hrs', 'time_taken']);
        });
    }
};
