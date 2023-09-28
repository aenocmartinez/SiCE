<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('costo', 8, 2)->default(0);
            $table->enum('modalidad', ['Presencial', 'Virtual'])->default('Presencial');
            $table->unsignedBigInteger('area_id');
            $table->timestamps();
            
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->unique(['nombre', 'area_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cursos');
    }
}
