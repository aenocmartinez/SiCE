<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\FormularioInscripcion;

class BuscarFormularioPorIdUseCase {

    public function ejecutar($formularioId = 0): FormularioInscripcion {      
        return (new FormularioInscripcionDao())->buscarFormularioPorId($formularioId);
    }
}