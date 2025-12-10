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
        Schema::table('material_reqs', function (Blueprint $table) {
            $table->integer('sr_no')->nullable()->after('customer_id');
        });
    }

    public function down()
    {
        Schema::table('material_reqs', function (Blueprint $table) {
            $table->dropColumn('sr_no');
        });
    }
};
