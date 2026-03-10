<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machine_records', function (Blueprint $table) {
            $table->dropForeign(['work_order_id']);
        });
    }

    public function down(): void
    {
        Schema::table('machine_records', function (Blueprint $table) {
            $table->foreign('work_order_id')
                ->references('id')
                ->on('work_orders')
                ->onDelete('set null');
        });
    }
};
