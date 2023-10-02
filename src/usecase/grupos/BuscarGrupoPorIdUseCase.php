<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\domain\Grupo;

class BuscarGrupoPorIdUseCase {

    public function ejecutar(int $id=0): Grupo {
        $grupoRepository = new GrupoDao();
        return Grupo::buscarPorId($id, $grupoRepository);
    }
}