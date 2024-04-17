<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participantes', function (Blueprint $table) {
            $table->id();
            $table->string('primer_nombre');
            $table->string('segundo_nombre')->nullable();
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->date('fecha_nacimiento');
            $table->string('tipo_documento', 5);
            $table->string('documento', 30);
            // $table->date('fecha_expedicion')->nullable();
            $table->enum('sexo', ['M', 'F'])->default('M');
            $table->string('estado_civil', 100);
            $table->string('direccion');
            $table->string('telefono', 100);
            $table->string('email');
            $table->string('eps');

            $table->unique(['tipo_documento', 'documento']);

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
        Schema::dropIfExists('participantes');
    }
}
