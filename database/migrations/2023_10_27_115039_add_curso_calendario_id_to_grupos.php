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
            $table->unsignedBigInteger('curso_calendario_id')->after('jornada');
            $table->unsignedBigInteger('calendario_id')->after('curso_calendario_id');
            // $table->time('hora');

            $table->foreign('curso_calendario_id')->references('id')->on('curso_calendario');

            // $table->unique(['curso_calendario_id', 'calendario_id', 'salon_id', 'orientador_id', 'dia', 'jornada', 'hora'], 'grupos_index_unique');
            $table->unique(['curso_calendario_id', 'calendario_id', 'salon_id', 'orientador_id', 'dia', 'jornada'], 'grupos_index_unique');
            // $table->unique(['calendario_id', 'orientador_id', 'dia', 'jornada', 'hora'], 'orientador_ocupado_index_unique');
            $table->unique(['calendario_id', 'orientador_id', 'dia', 'jornada'], 'orientador_ocupado_index_unique');
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

            $table->dropForeign(['curso_calendario_id']);
            $table->dropUnique('grupos_index_unique');

            $table->dropUnique('orientador_ocupado_index_unique');
            // $table->dropColumn('hora');
            $table->dropColumn('curso_calendario_id');
            $table->dropColumn('calendario_id');      
        });
    }
}
