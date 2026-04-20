<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('material_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable()->after('customer_id');
        });
    }

    public function down()
    {
        Schema::table('material_orders', function (Blueprint $table) {
            $table->dropColumn('project_id'); //  important
        });
    }
};
