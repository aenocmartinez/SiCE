<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMedioDePagoToFormularioInscripcion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_inscripcion', function (Blueprint $table) {
            $table->string('medio_pago', 20)->after('total_a_pagar')->default('pagoBanco');
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
            //
        });
    }
}
