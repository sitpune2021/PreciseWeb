<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_reqs', function (Blueprint $table) {
            $table->unsignedBigInteger('part_no')->nullable()->after('code'); // adjust after column as needed
        });
    }

    public function down(): void
    {
        Schema::table('material_reqs', function (Blueprint $table) {
            $table->dropColumn('part_no');
        });
    }
};
