<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up()
    {
        // 1. Add column first
        Schema::table('work_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('material_id')
                ->nullable()
                ->after('material');
        });

        // 2. Clean old data (trim spaces)
        DB::statement("UPDATE work_orders SET material = TRIM(material)");

        // 3. Map string → id safely
        $materials = DB::table('material_types')->get();

        foreach ($materials as $mat) {
            DB::table('work_orders')
                ->whereRaw('TRIM(material) = ?', [$mat->material_type])
                ->update(['material_id' => $mat->id]);
        }

        // 4. Add foreign key constraint
        Schema::table('work_orders', function (Blueprint $table) {
            $table->foreign('material_id')
                ->references('id')
                ->on('material_types')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['material_id']);
            $table->dropColumn('material_id');
        });
    }
};
