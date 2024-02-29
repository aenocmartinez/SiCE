<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsFromFormularioInscripcionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_inscripcion', function (Blueprint $table) {
            $table->dropColumn('voucher');
            $table->dropColumn('medio_pago');
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
            // Si necesitas revertir los cambios, puedes agregar los campos de vuelta en este método.
            $table->string('voucher')->nullable();
            $table->string('medio_pago')->nullable();
        });
    }
}
