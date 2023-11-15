<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;

class FormularioInscripcionDao extends Model{

    protected $table = 'formulario_inscripcion';
    protected $fillable = ['grupo_id', 
                            'participante_id', 
                            'convenio_id', 
                            'costo_curso', 
                            'valor_descuento', 
                            'total_a_pagar',
                            'medio_pago'];
}