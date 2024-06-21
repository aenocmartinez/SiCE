<?php

namespace Src\usecase\cambios_traslados;

use Src\domain\CambioTraslado;
use Src\infraestructure\util\Paginate;

class BuscadorCambiosYTrasladosUseCase {

    public function ejecutar(string $criterio, $page=1): Paginate {
        
        return CambioTraslado::buscadorCambiosYTraslados($criterio, $page);
    }
}