<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrientadorAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orientador_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orientador_id');
            $table->unsignedBigInteger('area_id');
            $table->timestamps();

            $table->foreign('orientador_id')->references('id')->on('orientadores');
            $table->foreign('area_id')->references('id')->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orientador_areas');
    }
}
