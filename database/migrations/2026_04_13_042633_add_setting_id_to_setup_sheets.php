<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ✅ STEP 1: Add column
        Schema::table('setup_sheets', function (Blueprint $table) {
            $table->unsignedBigInteger('setting_id')->nullable()->after('setting');
        });

        // ✅ STEP 2: Exact Match (admin wise)
        DB::statement("
            UPDATE setup_sheets ss
            JOIN settings s 
            ON LOWER(TRIM(s.setting_name)) = LOWER(TRIM(ss.setting))
            AND s.admin_id = ss.admin_id
            SET ss.setting_id = s.id
            WHERE ss.setting_id IS NULL
        ");

        // ✅ STEP 3: LIKE Match (correct direction + admin wise)
        DB::statement("
            UPDATE setup_sheets ss
            JOIN settings s 
            ON LOWER(TRIM(ss.setting)) LIKE CONCAT('%', LOWER(TRIM(s.setting_name)), '%')
            AND s.admin_id = ss.admin_id
            SET ss.setting_id = s.id
            WHERE ss.setting_id IS NULL
        ");

        // ✅ STEP 4: Manual Fix (only NULL rows)
        DB::statement("
            UPDATE setup_sheets 
            SET setting_id = 1
            WHERE LOWER(setting) = '5tha' 
            AND setting_id IS NULL
        ");
    }

    public function down()
    {
        Schema::table('setup_sheets', function (Blueprint $table) {
            $table->dropColumn('setting_id');
        });
    }
};