<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machine_records', function (Blueprint $table) {
            // Add status column if not exists, default 'pending'
            if (!Schema::hasColumn('machine_records', 'status')) {
                $table->enum('status', ['pending', 'complete'])
                    ->default('pending')
                    ->after('updated_at');
            } else {
                // Modify existing status column (optional)
                $table->enum('status', ['pending', 'complete'])
                    ->default('pending')
                    ->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('machine_records', function (Blueprint $table) {
            // Drop status column on rollback
            $table->dropColumn('status');
        });
    }
};
