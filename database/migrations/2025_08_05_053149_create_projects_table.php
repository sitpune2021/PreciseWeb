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
                $table->unsignedBigInteger('customer_id');

                $table->string('name');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('code')->nullable();
                $table->text('description')->nullable();
                $table->string('work_order_no');
                $table->integer('qty');
                $table->date('startdate')->nullable();
                $table->date('enddate')->nullable();

                $table->softDeletes();
                $table->timestamps();

                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
