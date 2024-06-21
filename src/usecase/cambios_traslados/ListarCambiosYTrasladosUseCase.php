<?php

namespace Src\usecase\cambios_traslados;

use Src\domain\CambioTraslado;
use Src\infraestructure\util\Paginate;

class ListarCambiosYTrasladosUseCase {

    public function ejecutar($page=1): Paginate {

        return CambioTraslado::listar($page);
    }
}