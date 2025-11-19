<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('project_id');
            $table->string('part');
            $table->date('date');
            $table->string('part_code')->nullable(false)->change();
            $table->text('part_description');
            $table->decimal('dimeter', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->string('exp_time')->nullable();
            $table->integer('quantity');
            $table->string('material')->nullable();
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void
    {

        Schema::dropIfExists('work_orders');
    }
};
