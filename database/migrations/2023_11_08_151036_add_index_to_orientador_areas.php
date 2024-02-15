<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToOrientadorAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orientador_areas', function (Blueprint $table) {
            $table->unique(['orientador_id', 'area_id'], 'orientador_area');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orientador_areas', function (Blueprint $table) {
            // $table->dropUnique('orientador_area');
        });
    }
}
