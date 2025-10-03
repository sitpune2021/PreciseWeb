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
       Schema::create('material_types', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id');
            $table->string('material_type'); 
            $table->decimal('material_gravity', 10, 2)->nullable(); 
            $table->decimal('material_rate', 10, 2);
            $table->boolean('status')->default(1);
            $table->boolean('is_active')->default(1);
             $table->softDeletes();
            $table->timestamps();
        });
   
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_types');
    }
};