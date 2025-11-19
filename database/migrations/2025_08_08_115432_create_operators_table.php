<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id');
            $table->string('operator_name');
            $table->string('phone_no', 15);
            $table->boolean('status')->default(1)->index();
            $table->softDeletes();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('operators');
    }
};
