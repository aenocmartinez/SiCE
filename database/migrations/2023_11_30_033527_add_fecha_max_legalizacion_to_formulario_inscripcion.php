<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaMaxLegalizacionToFormularioInscripcion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_inscripcion', function (Blueprint $table) {
            $table->date('fecha_max_legalizacion')->after('medio_pago')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formulario_inscripcion', function (Blueprint $table) {
            $table->dropColumn('fecha_max_legalizacion');
        });
    }
}
