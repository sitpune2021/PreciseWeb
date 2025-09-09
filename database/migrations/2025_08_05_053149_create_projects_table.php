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
            Schema::create('projects', function (Blueprint $table) {
                $table->id();

                // Customer relation
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->string('project_name');
                $table->string('project_code');
                // $table->string('customer_name');
                $table->string('customer_code');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->integer('quantity');
                $table->date('date')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('projects');
        }
    };
