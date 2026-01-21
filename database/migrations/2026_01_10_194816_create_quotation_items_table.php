<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();

            $table->string('description')->nullable();
            $table->decimal('dia', 10, 3)->nullable();
            $table->decimal('length', 10, 3)->nullable();
            $table->decimal('width', 10, 3)->nullable();
            $table->decimal('height', 10, 3)->nullable();

            $table->integer('qty')->default(1);
            $table->decimal('qty_in_kg', 10, 2)->nullable();
            $table->foreignId('material_type_id')->nullable();
            $table->string('material')->nullable();
            $table->decimal('material_rate', 10, 2)->nullable();
            $table->decimal('material_cost', 10, 2)->nullable();

            $table->decimal('lathe', 10, 2)->nullable();
            $table->decimal('mg', 10, 2)->nullable();
            $table->decimal('rg', 10, 2)->nullable();
            $table->decimal('cg', 10, 2)->nullable();
            $table->decimal('sg', 10, 2)->nullable();

            $table->decimal('vmc_soft', 10, 2)->nullable();
            $table->decimal('vmc_hard', 10, 2)->nullable();

            $table->decimal('edm_hole', 10, 2)->nullable();
            $table->decimal('ht', 10, 2)->nullable();
            $table->decimal('wirecut', 10, 2)->nullable();

            $table->decimal('machining_cost', 12, 2)->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
