<?php

namespace Src\infraestructure\medioPago;

use Src\domain\FormularioInscripcionPago;
use Src\view\dto\Response;

interface IMedioPago {

    public function Pagar(FormularioInscripcionPago $datosDePago): Response;
}