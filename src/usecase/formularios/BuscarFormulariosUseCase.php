<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\Calendario;
use Src\infraestructure\util\Paginate;

class BuscarFormulariosUseCase {

    public function ejecutar($periodoId=0, $estado="", $page=1): Paginate {

        $calendario = Calendario::Vigente();
        if ($calendario->existe()) {
            $periodoId = $calendario->getId();
        }

        return (new FormularioInscripcionDao)->listarFormulariosPorPeriodo($periodoId, $estado, $page);
    }
}