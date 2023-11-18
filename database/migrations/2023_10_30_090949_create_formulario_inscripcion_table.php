<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormularioInscripcionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulario_inscripcion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grupo_id');
            $table->unsignedBigInteger('participante_id');
            $table->unsignedBigInteger('convenio_id')->nullable();
            $table->string('codigo_banco', 50)->nullable();
            $table->string('numero_formulario')->nullable();


            $table->foreign('grupo_id')->references('id')->on('grupos');
            $table->foreign('participante_id')->references('id')->on('participantes');
            $table->foreign('convenio_id')->references('id')->on('convenios');

            // $table->unique(['grupo_id', 'participante_id'], 'participante_grupo');
            $table->unique(['codigo_banco']);
            $table->unique(['numero_formulario']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formulario_inscripcion');
    }
}
