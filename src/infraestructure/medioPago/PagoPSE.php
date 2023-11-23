<?php

namespace Src\infraestructure\medioPago;

use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\Response;

class PagoPSE implements IMedioPago {

    public function Pagar(ConfirmarInscripcionDto $confirmarInscripcionDto): Response {
        return new Response();
    }
}