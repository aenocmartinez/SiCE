<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvenioParticipanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convenio_participante', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('participante_id');
            $table->unsignedBigInteger('convenio_id');
            $table->string('cedula', 30);
            $table->enum('redimido', ['SI', 'NO'])->default('NO');
            $table->enum('disponible', ['SI', 'NO'])->default('SI');
            $table->timestamps();

            $table->foreign('convenio_id')->references('id')->on('convenios')->onDelete('restrict');
            // $table->foreign('participante_id')->references('id')->on('participantes')->onDelete('restrict');

            // $table->unique(['convenio_id', 'participante_id']);
            $table->unique(['convenio_id', 'cedula']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('convenio_participante');
    }
}
