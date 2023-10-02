<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\domain\Grupo;

class EliminarGrupoUseCase {
    
    public function ejecutar(int $id=0): bool {

        $grupoRepository = new GrupoDao();
        $grupo = Grupo::buscarPorId($id, $grupoRepository);

        if (!$grupo->existe()) {
            return false;
        }

        $grupo->setRepository($grupoRepository);
        return $grupo->eliminar();
    }
    
}