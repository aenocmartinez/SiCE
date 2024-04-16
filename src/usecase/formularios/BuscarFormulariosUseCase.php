<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\Calendario;
use Src\infraestructure\util\Paginate;

class BuscarFormulariosUseCase {

    public function ejecutar($periodoId=0, $estado="", $documento="", $page=1): Paginate {

        // $calendario = Calendario::Vigente();
        // if ($calendario->existe()) {
        //     $periodoId = $calendario->getId();
        // }

        // if ($periodoId == $calendario->getId()) {

        // }

        return (new FormularioInscripcionDao)->listarFormulariosPorPeriodo($periodoId, $estado, $documento, $page);
    }
}