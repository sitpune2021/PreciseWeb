<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hsncodes', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id');
            $table->string('hsn_code');
            $table->unsignedTinyInteger('sgst')->nullable()->default(0);
            $table->unsignedTinyInteger('cgst')->nullable()->default(0);
            $table->unsignedTinyInteger('igst')->nullable()->default(0);

            $table->string('invoice_desc');
            $table->boolean('status')->default(1);
            $table->boolean('is_active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('hsncodes');
    }
};
