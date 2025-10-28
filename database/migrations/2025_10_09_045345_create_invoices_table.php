<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('invoice_no')->nullable()->unique();
            $table->dateTime('invoice_date')->nullable();
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('total_tax', 15, 2)->default(0);
            $table->decimal('adjustment', 15, 2)->default(0);
            $table->decimal('round_off', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('total_hrs', 16, 3)->default(0);
            $table->decimal('total_vmc', 16, 3)->default(0);
            $table->text('declaration')->nullable();
            $table->text('note')->nullable();
            $table->text('bank_details')->nullable();
            $table->string('amount_in_words')->nullable();
            $table->string('status')->nullable()->default('draft');
            $table->timestamps();

            // Indexes if needed:
            $table->index(['admin_id']);
            $table->index(['customer_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
