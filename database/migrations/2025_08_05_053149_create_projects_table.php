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

                $table->string('name');
                $table->unsignedBigInteger('user_id')->nullable();               
                $table->text('description')->nullable();             
                $table->integer('qty');
                $table->date('startdate')->nullable();
                $table->date('enddate')->nullable();
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
