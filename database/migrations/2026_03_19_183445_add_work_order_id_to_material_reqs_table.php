<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_reqs', function (Blueprint $table) {
            $table->unsignedBigInteger('work_order_id')->nullable()->after('id');

            $table->foreign('work_order_id')
                ->references('id')
                ->on('work_orders')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('material_reqs', function (Blueprint $table) {
            $table->dropForeign(['work_order_id']);
            $table->dropColumn('work_order_id');
        });
    }
};
