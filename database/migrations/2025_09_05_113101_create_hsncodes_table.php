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
        Schema::create('hsncodes', function (Blueprint $table) {
           $table->id();
            $table->integer('admin_id');
            $table->string('hsn_code');
            $table->string('sgst');
            $table->string('cgst');
            $table->string('igst');
            $table->string('invoice_desc');
             $table->boolean('status')->default(1);
             $table->boolean('is_active')->default(1); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsncodes');
    }
};
