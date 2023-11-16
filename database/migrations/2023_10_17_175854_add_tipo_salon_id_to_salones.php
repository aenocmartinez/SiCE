<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoSalonIdToSalones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salones', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_salon_id')->after('hoja_vida')->nullable();
            $table->foreign('tipo_salon_id')->references('id')->on('tipo_salones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salones', function (Blueprint $table) {
            //
        });
    }
}
