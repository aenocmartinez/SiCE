<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\FormularioInscripcion;

class BuscarFormularioPorNumeroUseCase {

    public function ejecutar($numeroFormulario): FormularioInscripcion{

        $formularioRepository = new FormularioInscripcionDao();
        return $formularioRepository->buscarFormularioPorNumero($numeroFormulario);
    }
}