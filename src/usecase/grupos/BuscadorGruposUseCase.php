<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\domain\Calendario;
use Src\infraestructure\util\Paginate;

class BuscadorGruposUseCase {

    public function ejecutar(string $criterio, Calendario $calendario, $page=1): Paginate {
        return GrupoDao::buscadorGrupos($criterio, $calendario, $page);
    }
}