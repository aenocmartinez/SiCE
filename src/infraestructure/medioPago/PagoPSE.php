<?php

namespace Src\infraestructure\medioPago;

use Src\domain\FormularioInscripcion;
use Src\view\dto\Response;

class PagoPSE implements IMedioPago {

    public function Pagar(FormularioInscripcion $formulario, string $voucher, $valorPago): Response {
        return new Response();
    }
}