<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('material_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('material_req_id')->nullable()->after('id');

            // Optional (recommended)
            $table->foreign('material_req_id')
                ->references('id')
                ->on('material_reqs')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('material_orders', function (Blueprint $table) {
            $table->dropForeign(['material_req_id']);
            $table->dropColumn('material_req_id');
        });
    }
};
