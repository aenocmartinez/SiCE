<?php

namespace Src\infraestructure\medioPago;

use Src\domain\FormularioInscripcionPago;
use Src\view\dto\Response;

class PagoEcollect implements IMedioPago {

    public function Pagar(FormularioInscripcionPago $datosDePago): Response {
        return new Response();
    }
}