<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGruposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curso_id');
            $table->unsignedBigInteger('calendario_id');
            $table->unsignedBigInteger('salon_id');
            $table->unsignedBigInteger('orientador_id');
            $table->enum('dia', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'])->default('Lunes');
            $table->enum('jornada', ['Mañana', 'Tarde', 'Noche'])->default('Mañana');
            $table->timestamps();
            
            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
            $table->foreign('calendario_id')->references('id')->on('calendarios')->onDelete('cascade');
            $table->foreign('salon_id')->references('id')->on('salones')->onDelete('cascade');
            $table->foreign('orientador_id')->references('id')->on('orientadores')->onDelete('cascade');

            $table->unique(['curso_id', 'calendario_id', 'salon_id', 'orientador_id', 'dia', 'jornada'], 'grupos_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupos');
    }
}
