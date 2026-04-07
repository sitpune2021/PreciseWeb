<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proforma_items', function (Blueprint $table) {
            $table->string('vmc', 255)->change(); // decimal → varchar
        });
    }

    public function down(): void
    {
        Schema::table('proforma_items', function (Blueprint $table) {
            $table->decimal('vmc', 15, 2)->default(0)->change(); // revert back
        });
    }
};