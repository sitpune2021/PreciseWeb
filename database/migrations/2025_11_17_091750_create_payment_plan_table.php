<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('payment_plan', function (Blueprint $table) {
    $table->id();
    $table->string('title'); 
    $table->integer('price')->default(0);
    $table->string('short_text');
    $table->string('description')->nullable();
    $table->integer('days')->default(0);
    $table->integer('gst')->default(18);
    $table->boolean('plan_status')->default(1);

    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('payment_plan');
    }
};
