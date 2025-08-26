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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('part');
            $table->date('date');
            $table->string('part_code')->nullable(false)->change();
            $table->unsignedBigInteger('customer_id'); // Add customer_id

            $table->text('part_description');
            $table->string('dimeter');
            $table->string('length');
            $table->string('width');
            $table->string('height');
            $table->time('exp_time');
            $table->integer('quantity');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }


};
