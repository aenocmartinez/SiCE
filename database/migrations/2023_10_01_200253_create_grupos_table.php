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
            $table->string("nombre", 15)->unique()->nullable();
            $table->unsignedBigInteger('salon_id');
            $table->unsignedBigInteger('orientador_id');
            $table->enum('dia', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'])->default('Lunes');
            $table->enum('jornada', ['Mañana', 'Tarde', 'Noche'])->default('Mañana');
            $table->timestamps();
            $table->foreign('salon_id')->references('id')->on('salones');
            $table->foreign('orientador_id')->references('id')->on('orientadores');
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
