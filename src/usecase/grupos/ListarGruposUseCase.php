<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\domain\Grupo;

class ListarGruposUseCase {

    public function ejecutar($page=1) {
        return Grupo::listar($page);
    }
}