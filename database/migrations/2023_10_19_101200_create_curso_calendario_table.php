<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursoCalendarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curso_calendario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calendario_id');
            $table->unsignedBigInteger('curso_id');
            $table->enum('modalidad', ['Presencial', 'Virtual'])->default('Presencial');
            $table->double('costo', 8, 2)->default(0);
            $table->integer('cupo')->default(0);

            $table->foreign('calendario_id')->references('id')->on('calendarios');
            $table->foreign('curso_id')->references('id')->on('cursos');

            $table->unique(['calendario_id', 'curso_id', 'modalidad'], 'curso_calendario_index');

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
        Schema::dropIfExists('curso_calendario');
    }
}
