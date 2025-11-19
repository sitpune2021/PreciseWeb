<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('gst_no')->nullable();
            $table->date('date')->nullable();
            $table->string('udyam_no')->nullable();
            $table->text('bank_details')->nullable();
            $table->text('declaration')->nullable();
            $table->text('note')->nullable();
            $table->string('logo')->nullable();
            $table->string('stamp')->nullable();
            $table->text('footer_note')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_settings');
    }
};
