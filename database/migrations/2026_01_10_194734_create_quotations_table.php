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
    Schema::create('quotations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('admin_id')->nullable();
        $table->unsignedBigInteger('customer_id');
        $table->integer('sr_no')->nullable();
        $table->string('quotation_no', 50);
        $table->string('project_name', 100);
       
        $table->date('date');

        $table->decimal('total_manufacturing_cos', 12, 2)->nullable();
        $table->decimal('profit', 12, 2)->nullable();
        $table->decimal('overhead', 12, 2)->nullable();
        $table->string('terms_conditions')->nullable();

        $table->boolean('status')->default(1);
        $table->timestamps();
        $table->softDeletes();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
