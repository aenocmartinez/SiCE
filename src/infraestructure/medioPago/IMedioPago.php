<?php

namespace Src\infraestructure\medioPago;

use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\Response;

interface IMedioPago {

    public function Pagar(ConfirmarInscripcionDto $confirmarInscripcionDto): Response;
}