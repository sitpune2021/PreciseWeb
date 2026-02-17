<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // Change amount to decimal
            $table->decimal('amount', 10, 2)->default(0)->change();

            // Plan Snapshot Fields
            $table->string('plan_title')->nullable()->after('plan_id');
            $table->integer('plan_days')->nullable()->after('plan_title');
            $table->integer('gst_percentage')->nullable()->after('plan_days');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // Revert amount back to integer
            $table->integer('amount')->default(0)->change();

            // Drop snapshot fields
            $table->dropColumn([
                'plan_title',
                'plan_days',
                'gst_percentage'
            ]);
        });
    }
};
