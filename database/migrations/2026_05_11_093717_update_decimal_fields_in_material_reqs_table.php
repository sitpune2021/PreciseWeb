<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_reqs', function (Blueprint $table) {

            // SIZE
            $table->decimal('dia', 10, 2)->nullable()->change();
            $table->decimal('length', 10, 2)->nullable()->change();
            $table->decimal('width', 10, 2)->nullable()->change();
            $table->decimal('height', 10, 2)->nullable()->change();

            // MATERIAL
            $table->decimal('material_rate', 10, 2)->nullable()->change();
            $table->decimal('material_gravity', 10, 3)->nullable()->change();

            // QTY + COST
            $table->decimal('qty', 10, 2)->nullable()->change();
            $table->decimal('weight', 10, 3)->nullable()->change();
            $table->decimal('material_cost', 10, 2)->nullable()->change();

            // MACHINING
            $table->decimal('lathe', 10, 2)->nullable()->change();
            $table->decimal('mg4', 10, 2)->nullable()->change();
            $table->decimal('mg2', 10, 2)->nullable()->change();
            $table->decimal('rg2', 10, 2)->nullable()->change();
            $table->decimal('sg4', 10, 2)->nullable()->change();
            $table->decimal('sg2', 10, 2)->nullable()->change();

            // VMC
            $table->decimal('vmc_hrs', 10, 2)->nullable()->change();
            $table->decimal('vmc_cost', 10, 2)->nullable()->change();

            // HRC
            $table->decimal('hrc', 10, 2)->nullable()->change();

            // EDM
            $table->decimal('edm_qty', 10, 2)->nullable()->change();
            $table->decimal('edm_rate', 10, 2)->nullable()->change();

            // WIRECUT
            $table->decimal('cl', 10, 2)->nullable()->change();
            $table->decimal('wirecut_rate', 10, 2)->nullable()->change();

            // EXTRA
            $table->decimal('column1', 10, 2)->nullable()->change();
            $table->decimal('column2', 10, 2)->nullable()->change();

            // TOTAL
            $table->decimal('total_cost', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('material_reqs', function (Blueprint $table) {
            //
        });
    }
};
