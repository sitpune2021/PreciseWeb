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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
           
            $table->integer('login_id')->default(0);
            $table->string('name'); // required
            $table->string('code', 50)->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone_no', 15)->nullable();
            $table->string('email_id', 30)->nullable();
            $table->string('gst_no', 20)->nullable();
            $table->text(  'address')->nullable();
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
