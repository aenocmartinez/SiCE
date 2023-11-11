<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;

class BuscadorGruposUseCase {

    public function ejecutar(string $criterio): array {
        $grupoRepository = new GrupoDao();
        return $grupoRepository->buscadorGrupos($criterio);
    }
}