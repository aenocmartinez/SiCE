<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCursoCalendarioIdToGrupos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->unsignedBigInteger('curso_calendario_id');

            $table->foreign('curso_calendario_id')->references('id')->on('curso_calendario');            

            $table->unique(['curso_calendario_id', 'salon_id', 'orientador_id', 'dia', 'jornada'], 'grupos_index_unique');
            $table->unique(['orientador_id', 'dia', 'jornada'], 'orientador_ocupado_index_unique');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grupos', function (Blueprint $table) {
            //
        });
    }
}