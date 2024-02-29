<?php

namespace Src\infraestructure\medioPago;

use Src\domain\FormularioInscripcion;
use Src\view\dto\Response;

interface IMedioPago {

    public function Pagar(FormularioInscripcion $formulario, $voucher, $valorPago): Response;
}