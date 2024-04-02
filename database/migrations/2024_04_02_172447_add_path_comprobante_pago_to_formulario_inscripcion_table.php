<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPathComprobantePagoToFormularioInscripcionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_inscripcion', function (Blueprint $table) {
            $table->string('path_comprobante_pago')->nullable();
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
            $table->dropColumn('path_comprobante_pago');
        });
    }
}
