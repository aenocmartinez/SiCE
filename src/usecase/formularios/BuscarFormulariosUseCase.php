<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\Calendario;
use Src\infraestructure\util\Paginate;

class BuscarFormulariosUseCase {

    public function ejecutar($periodoId=0, $estado="", $page=1, $documento=""): Paginate {      
        return (new FormularioInscripcionDao)->listarFormulariosPorPeriodo($periodoId, $estado, $documento, $page);
    }
}