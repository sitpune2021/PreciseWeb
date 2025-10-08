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
       Schema::create('material_orders', function (Blueprint $table) {
    $table->id();
    $table->integer('admin_id');
    $table->unsignedBigInteger('customer_id');
     $table->string('work_order_no')->nullable();
    $table->date('date');
    $table->string('work_order_desc');
    // Finish size
    $table->decimal('f_diameter', 10, 2)->nullable();
    $table->decimal('f_length', 10, 2)->nullable();
    $table->decimal('f_width', 10, 2)->nullable();
    $table->decimal('f_height', 10, 2)->nullable();
    // Raw size
    $table->decimal('r_diameter', 10, 2)->nullable();
    $table->decimal('r_length', 10, 2)->nullable();
    $table->decimal('r_width', 10, 2)->nullable();
    $table->decimal('r_height', 10, 2)->nullable();
    // Other fields
    $table->string('material');
    $table->integer('quantity');
     $table->softDeletes();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_orders');
    }
};
