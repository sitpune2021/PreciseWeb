 <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->integer('project_no')->default(0)->comment('project number');
                $table->integer('admin_id');
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->string('project_name');
                // $table->string('customer_name');
                $table->string('customer_code');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->integer('quantity');
                $table->date('date')->nullable();
                $table->boolean('status')->default(1);
                $table->softDeletes();
                $table->timestamps();
            });
        }
        public function down(): void
        {
            Schema::dropIfExists('projects');
        }
    };
