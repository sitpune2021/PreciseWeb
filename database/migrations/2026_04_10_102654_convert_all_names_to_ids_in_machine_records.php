<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        //  STEP 1: Add new ID columns
        Schema::table('machine_records', function (Blueprint $table) {
            $table->unsignedBigInteger('operator_id')->nullable()->after('operator');
            $table->unsignedBigInteger('machine_id')->nullable()->after('machine');
            $table->unsignedBigInteger('material_id')->nullable()->after('material');
            $table->unsignedBigInteger('setting_id')->nullable()->after('setting_no');
        });

        //  STEP 2: EXACT MATCH (SAFE ✅)

        // 🔹 Operator
        DB::statement("
            UPDATE machine_records mr
            JOIN operators o 
            ON LOWER(TRIM(o.operator_name)) = LOWER(TRIM(mr.operator))
            SET mr.operator_id = o.id
            WHERE mr.operator_id IS NULL
        ");

        // 🔹 Machine
        DB::statement("
            UPDATE machine_records mr
            JOIN machines m 
            ON LOWER(TRIM(m.machine_name)) = LOWER(TRIM(mr.machine))
            SET mr.machine_id = m.id
            WHERE mr.machine_id IS NULL
        ");

        // 🔹 Material
        DB::statement("
            UPDATE machine_records mr
            JOIN material_types mat 
            ON LOWER(TRIM(mat.material_type)) = LOWER(TRIM(mr.material))
            SET mr.material_id = mat.id
            WHERE mr.material_id IS NULL
        ");

        // 🔹 Setting
        DB::statement("
            UPDATE machine_records mr
            JOIN settings s 
            ON LOWER(TRIM(s.setting_name)) = LOWER(TRIM(mr.setting_no))
            SET mr.setting_id = s.id
            WHERE mr.setting_id IS NULL
        ");

        // ✅ STEP 3: CUSTOM MANUAL FIX

        // Operator mapping
        DB::statement("
            UPDATE machine_records 
            SET operator_id = (SELECT id FROM operators WHERE operator_name='Rajesh' LIMIT 1)
            WHERE LOWER(operator) = 'datta'
        ");

        DB::statement("
            UPDATE machine_records 
            SET operator_id = (SELECT id FROM operators WHERE operator_name='Laxman' LIMIT 1)
            WHERE LOWER(operator) = 'ganesh'
        ");

        // Material special mapping
        DB::statement("
            UPDATE machine_records 
            SET material_id = (SELECT id FROM material_types WHERE material_type='MS' LIMIT 1)
            WHERE material IN ('Regular Material','4th Axis M/C')
        ");

        DB::statement("
            UPDATE machine_records 
            SET material_id = (SELECT id FROM material_types WHERE material_type='SS304' LIMIT 1)
            WHERE material = 'SS Material'
        ");

        DB::statement("
            UPDATE machine_records 
            SET material_id = (SELECT id FROM material_types WHERE material_type='EN31' LIMIT 1)
            WHERE material IN ('HRC ABOVE 45','HRC ABOVE 60')
        ");
    }

    public function down()
    {
        Schema::table('machine_records', function (Blueprint $table) {
            $table->dropColumn([
                'operator_id',
                'machine_id',
                'material_id',
                'setting_id'
            ]);
        });
    }
};