<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\domain\Grupo;

class ListarGruposUseCase {

    public function ejecutar(): array {
        $grupoRepository = new GrupoDao();
        return Grupo::listar($grupoRepository);
    }
}