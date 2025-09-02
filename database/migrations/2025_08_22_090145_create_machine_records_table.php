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
        Schema::create('machine_records', function (Blueprint $table) {
            $table->id();
            $table->string('part_no');             // PART NO.
            $table->string('work_order')->nullable();
             $table->string('code', 100)->nullable();  // WO
            $table->string('first_set')->nullable();   // 1ST SET
            $table->integer('qty')->default(1);    // QTY
            $table->string('machine');             // M/C
            $table->string('operator');            // OP
            $table->string('setting_no')->nullable();  // SET (1st, 2nd etc.)
            $table->string('est_time')->nullable();
            $table->dateTime('start_time')->nullable();   // START
            $table->dateTime('end_time')->nullable();     // END
            $table->decimal('hrs', 5, 2)->nullable();     // HRS
            $table->decimal('time_taken', 5, 2)->nullable(); // TIME
            $table->decimal('actual_hrs', 5, 2)->nullable(); // HRS (Actual)
            $table->string('invoice_no')->nullable(); // INVOICE NO
            
            
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('machine_records');
    }
};
