<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('login_id');
            $table->string('name');
            $table->string('phone_no', 15);
            $table->string('email_id', 30)->nullable();
            $table->string('gst_no', 20)->nullable();
            $table->string('logo');
            $table->text('address');
            $table->date('trial_start')->nullable();
            $table->date('trial_end')->nullable();
            $table->integer('plan_type')->default(1);
            $table->date('plan_expiry')->nullable();
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
