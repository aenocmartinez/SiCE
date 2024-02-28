<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\FormularioInscripcion;

class BuscarFormularioPorNumeroUseCase {

    public function ejecutar($numeroFormulario): FormularioInscripcion{
                
        return (new FormularioInscripcionDao())->buscarFormularioPorNumero($numeroFormulario);     
    }
}