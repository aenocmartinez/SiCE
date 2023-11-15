<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;

class BuscarFormulariosUseCase {

    public function ejecutar(int $periodoId, $estado): array {

        return (new FormularioInscripcionDao)->listarFormulariosPorPeriodo($periodoId, $estado);
    }
}