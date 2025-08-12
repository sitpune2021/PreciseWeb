<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('part_code')->nullable()->after('part'); // 'after' is optional
        });
    }

    public function down(): void {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn('part_code');
        });
    }
};
