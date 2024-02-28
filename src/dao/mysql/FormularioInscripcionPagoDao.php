<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;

class FormularioInscripcionPagoDao extends Model {
    protected $table = 'formulario_inscripcion_pagos';
    protected $fillable = ['formulario_id', 'valor', 'medio', 'voucher', 'created_at', 'updated_at'];
}