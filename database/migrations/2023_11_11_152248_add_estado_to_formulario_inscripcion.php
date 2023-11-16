<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoToFormularioInscripcion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_inscripcion', function (Blueprint $table) {
            $table->enum('estado', ['Pendiente de pago', 'Pagado', 'Vencido'])->after('numero_formulario')->default('Pendiente de pago');
            $table->decimal('costo_curso',8,2)->after('estado')->default(0);
            $table->decimal('valor_descuento',8,2)->after('costo_curso')->default(0);
            $table->decimal('total_a_pagar',8,2)->after('valor_descuento')->default(0);
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
