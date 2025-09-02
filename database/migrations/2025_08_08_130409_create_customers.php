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
            $table->integer('login_id');
            $table->string('name');
            $table->string('code', '50');
            $table->string('contact_person');
            $table->string('phone_no', '15');
            $table->string('email_id', '30')->nullable();
            $table->string('gst_no', '20')->nullable();
            $table->text('address');
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
