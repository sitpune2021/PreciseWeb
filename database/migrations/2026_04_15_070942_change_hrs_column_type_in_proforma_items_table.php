<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proforma_items', function (Blueprint $table) {
            $table->string('hrs', 50)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('proforma_items', function (Blueprint $table) {
            $table->decimal('hrs', 10, 2)->nullable()->change(); // old type
        });
    }
};