<?php

namespace Src\infraestructure\medioPago;

use Src\domain\FormularioInscripcion;
use Src\domain\FormularioInscripcionPago;
use Src\view\dto\Response;

class PagoEcollect implements IMedioPago {

    public function Pagar(FormularioInscripcion $formulario, $voucher, $valorPago): Response {
        
        if (is_null($voucher)) {
            $voucher = 0;
            $valorPago = 0;
        }

        $exito = $formulario->AgregarPago(new FormularioInscripcionPago("pagoEcollect", $valorPago, $voucher));
        if (!$exito) {
            return new Response("500", "Ha ocurrido un error al pagar la inscripción");
        }

        return new Response("201", "Se ha registrado con éxito.");
    }
}