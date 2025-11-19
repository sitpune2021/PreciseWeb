<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('machine_records', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id');
            $table->string('part_no');
            $table->string('work_order')->nullable();

            $table->string('code', 100)->nullable();
            $table->string('first_set')->nullable();
            $table->integer('qty')->default(1);
            $table->string('machine');
            $table->string('operator');
            $table->string('setting_no')->nullable();
            $table->string('material')->nullable();
            $table->string('est_time')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('adjustment')->nullable();

            // $table->integer('minute')->nullable();
            $table->decimal('hrs', 5, 2)->nullable();
            // $table->decimal('time_taken', 5, 2)->nullable();
            // $table->decimal('actual_hrs', 5, 2)->nullable();
            $table->string('invoice_no')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('machine_records');
    }
};
