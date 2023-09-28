<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrientadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orientadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo_documento', ['CC', 'TI', 'CE', 'PP']);
            $table->string('documento', 20);
            $table->string('email_institucional')->nullable();
            $table->string('email_personal')->nullable();
            $table->string('direccion')->nullable();
            $table->string('eps')->nullable();
            $table->boolean('estado')->default(true);
            $table->text('observacion')->nullable();
                        
            $table->unique(['tipo_documento', 'documento']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orientadores');
    }
}
