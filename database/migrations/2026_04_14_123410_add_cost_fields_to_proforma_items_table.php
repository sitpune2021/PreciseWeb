<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proforma_items', function (Blueprint $table) {

            $table->decimal('material_cost', 15, 2)
                  ->default(0)
                  ->after('material_rate');

            $table->decimal('total_cost', 15, 2)
                  ->default(0)
                  ->after('material_cost');
        });
    }

    public function down()
    {
        Schema::table('proforma_items', function (Blueprint $table) {

            $table->dropColumn(['material_cost', 'total_cost']);
        });
    }
};