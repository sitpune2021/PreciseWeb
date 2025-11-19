<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('useradmin', function (Blueprint $table) {
    $table->id();
    $table->string('full_name');
    $table->string('email')->unique();
    $table->string('user_name')->unique();
    $table->string('mobile')->nullable();
    $table->string('password');
    $table->enum('role', ['Admin','Programmer','Supervisor','Finance'])->default('Programmer');
    $table->enum('status', ['Active','Inactive'])->default('Active');
    $table->timestamps();
});

    }

    public function down(): void {
        Schema::dropIfExists('useradmin');
    }
};
