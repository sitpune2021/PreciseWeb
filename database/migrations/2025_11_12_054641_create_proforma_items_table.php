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
        Schema::create('proforma_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->index();
            //  $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('work_order_id');
            $table->integer('project_id')->nullable();
            $table->string('part_name')->nullable();
            $table->string('hsn_code')->nullable();
            $table->integer('qty')->default(0);
            $table->decimal('rate', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('hrs', 12, 2)->default(0);
            $table->decimal('vmc', 15, 2)->default(0);
            $table->decimal('adj', 15, 2)->default(0);
            $table->decimal('sgst', 8, 2)->default(0);
            $table->decimal('cgst', 8, 2)->default(0);
            $table->decimal('igst', 8, 2)->default(0);
            $table->string('vo_no')->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('proforma_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proforma_items');
    }
};
