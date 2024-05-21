<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\infraestructure\util\Paginate;

class BuscadorGruposUseCase {

    public function ejecutar(string $criterio, $page=1): Paginate {
        return GrupoDao::buscadorGrupos($criterio, $page);
    }
}