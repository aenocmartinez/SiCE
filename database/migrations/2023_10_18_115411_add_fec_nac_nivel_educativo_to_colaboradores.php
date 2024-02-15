<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFecNacNivelEducativoToColaboradores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orientadores', function (Blueprint $table) {
            $table->date('fec_nacimiento')->after('observacion')->nullable();
            $table->string('nivel_estudio', 50)->after('fec_nacimiento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orientadores', function (Blueprint $table) {
            $table->dropColumn('fec_nacimiento');
            $table->dropColumn('nivel_estudio');
        });
    }
}
