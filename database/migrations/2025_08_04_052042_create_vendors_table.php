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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id'); 
            $table->string('vendor_name');
           $table->string('vendor_code')->nullable();
            $table->string('contact_person');
            $table->string('gst_no')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('phone_no');
            $table->string('email_id')->nullable();
            $table->text('address');
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
