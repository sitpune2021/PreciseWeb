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
        Schema::create('material_reqs', function (Blueprint $table) {
            $table->id();
 
            // Customer relation
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
 
 
            // Work order details
            $table->string('code')->nullable();
            $table->date('date')->nullable();
            $table->string('description')->nullable();
            $table->string('work_order_no')->nullable();
            $table->string('dia')->nullable();
           
 
            // Dimensions
            $table->double('length', 10, 2)->nullable();
            $table->double('width', 10, 2)->nullable();
            $table->double('height', 10, 2)->nullable();
           
 
            // Material & Quantity
            $table->string('material')->nullable();
            $table->double('qty', 10, 2)->nullable();
            $table->double('weight', 10, 3)->nullable();
 
            // Machine Processes
            $table->double('lathe', 10, 2)->nullable();
            $table->double('mg4', 10, 2)->nullable();
            $table->double('mg2', 10, 2)->nullable();
            $table->double('rg2', 10, 2)->nullable();
            $table->double('sg4', 10, 2)->nullable();
            $table->double('sg2', 10, 2)->nullable();
            $table->double('vmc_hrs', 10, 2)->nullable();
            $table->double('vmc_cost', 10, 2)->nullable();
            $table->double('hrc', 10, 2)->nullable();
 
            // EDM / Other
            $table->double('edm_qty', 10, 2)->nullable();
            $table->double('edm_rate', 10, 2)->nullable();
            $table->string('cl')->nullable();
 
            // Total Cost
            $table->double('total_cost', 12, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_reqs');
    }
};
 
 