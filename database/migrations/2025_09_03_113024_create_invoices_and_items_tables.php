<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Invoices Table
       Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('client_id');

    // Invoice Info
    $table->string('invoice_no')->nullable();
    $table->date('invoice_date');
    $table->string('our_ch_no')->nullable();
    $table->date('our_ch_no_date')->nullable();
    $table->string('y_ch_no')->nullable();
    $table->date('y_ch_no_date')->nullable();
    $table->string('p_o_no')->nullable();
    $table->date('p_o_no_date')->nullable();
     $table->string('description_fast')->nullable();
    $table->string('gst_no')->nullable();
    $table->string('msme_no')->nullable();

    // Buyer Details
    $table->string('buyer_name');
    $table->text('buyer_address')->nullable();

    // Consignee Details
    $table->string('consignee_name')->nullable();
    $table->text('consignee_address')->nullable();

    // Extra Contact Details
    $table->string('ki_attn_name')->nullable();
    $table->string('_ki_contact_no')->nullable();
    $table->string('ki_gst')->nullable();

     $table->string('kind_attn_name')->nullable();
    $table->string('contact_no')->nullable();
    $table->string('kind_gst')->nullable();

    $table->string('description')->nullable();
    $table->string('hsn_code')->nullable();
    $table->integer('qty')->default(1);
    $table->decimal('rate', 10, 2)->default(0);
    $table->decimal('amount', 10, 2)->default(0);
    $table->decimal('hrs_per_job', 10, 2)->default(0);
    $table->decimal('cost', 10, 2)->default(0);

    // Totals
    $table->decimal('sub_total', 10, 2)->default(0);
    $table->decimal('sgst', 10, 2)->default(0);
    $table->decimal('cgst', 10, 2)->default(0);
    $table->decimal('igst', 10, 2)->default(0);
    $table->decimal('total_tax_payable', 10, 2)->default(0);
    $table->decimal('grand_total', 10, 2)->default(0);

    // Others
    $table->string('declaration')->nullable();
    $table->text('note')->nullable();
    $table->text('bank_details')->nullable();
    $table->string('amount_in_words')->nullable();
    $table->softDeletes();
    $table->timestamps();
});

    }

    public function down(): void
    {

        Schema::dropIfExists('invoices');
    }
};
