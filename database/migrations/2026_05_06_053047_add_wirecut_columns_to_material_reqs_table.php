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
            $table->decimal('wirecut_rate', 10, 2)->nullable()->after('cl');
            $table->decimal('column1', 10, 2)->nullable()->after('wirecut_rate');
            $table->decimal('column2', 10, 2)->nullable()->after('column1');
        });
    }

    public function down()
    {
        Schema::table('material_reqs', function (Blueprint $table) {
            $table->dropColumn(['wirecut_rate', 'column1', 'column2']);
        });
    }
};
