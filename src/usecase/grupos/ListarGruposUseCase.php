<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\domain\Calendario;
use Src\domain\Grupo;

class ListarGruposUseCase {

    public function ejecutar($page=1, Calendario $periodo) {
        return Grupo::listar($page, $periodo);
    }
}