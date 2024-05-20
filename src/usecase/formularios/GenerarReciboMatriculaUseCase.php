<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;

class GenerarReciboMatriculaUseCase {

    public function ejecutar($formularioId=0): array {

        return FormularioInscripcionDao::GenerarReciboDeMatricula($formularioId);
    }
}