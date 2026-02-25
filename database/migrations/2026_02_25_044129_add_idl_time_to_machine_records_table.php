<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machine_records', function (Blueprint $table) {
            $table->string('idl_time')->nullable()->after('hrs');
        });
    }

    public function down(): void
    {
        Schema::table('machine_records', function (Blueprint $table) {
            $table->dropColumn('idl_time');
        });
    }
};
