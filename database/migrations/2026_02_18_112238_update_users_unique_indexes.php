<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {


            $table->dropUnique('users_email_unique');
            $table->unique(['admin_id', 'email'], 'users_admin_email_unique');
            $table->unique(['admin_id', 'username'], 'users_admin_username_unique');
            $table->unique(['admin_id', 'mobile'], 'users_admin_mobile_unique');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_admin_email_unique');
            $table->dropUnique('users_admin_username_unique');
            $table->dropUnique('users_admin_mobile_unique');
            $table->unique('email');
        });
    }
};
