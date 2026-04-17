<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_items', function (Blueprint $table) {

            //  machine_id INT → JSON
            $table->json('machine_id')->nullable()->change();

            //  hrs numeric → string
            $table->string('hrs', 50)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('invoice_items', function (Blueprint $table) {

            // rollback
            $table->unsignedBigInteger('machine_id')->nullable()->change();
            $table->decimal('hrs', 8, 2)->nullable()->change();
        });
    }
};
