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
    Schema::create('setup_sheets', function (Blueprint $table) {
    $table->id();
    $table->integer('admin_id');
     $table->unsignedBigInteger('customer_id')->nullable();
    $table->string('part_code')->nullable();
    $table->string('work_order_no')->nullable();
    $table->date('date')->nullable();
    $table->string('description')->nullable();
    $table->string('setup_image')->nullable();
    $table->string('size_in_x')->nullable();
    $table->string('size_in_y')->nullable();
    $table->string('size_in_z')->nullable();
    $table->string('setting')->nullable();
    $table->string('e_time')->nullable();
 
    $table->string('x_refer')->nullable();
    $table->string('y_refer')->nullable();
    $table->string('z_refer')->nullable();
    $table->string('clamping')->nullable();
    $table->string('qty')->nullable();
 
 
    // Dowel Holes
    $table->string('holes')->nullable();
    $table->string('hole_x')->nullable();
    $table->string('hole_y')->nullable();
    $table->string('hole_dia')->nullable();
    $table->string('hole_depth')->nullable();
    $table->softDeletes();
    $table->timestamps();
});
 
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setup_sheets');
    }
};
 