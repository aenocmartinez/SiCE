<?php

namespace Src\infraestructure\medioPago;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\FormularioInscripcion;
use Src\domain\FormularioInscripcionPago;
use Src\view\dto\Response;

class PagoDatafono implements IMedioPago {

    public function Pagar(FormularioInscripcionPago $datosDePago): Response {
        

        // $pago = new FormularioInscripcionPago();
        // $pago->setMedio("Datafono");
        // $pago->setValor($formularioInscripcion->getValorPagoParcial());
        // $pago->setVoucher($formularioInscripcion->getVoucher());
        // $pago->setFecha($formularioInscripcion->getFechaCreacion());
        
        // $formularioInscripcion->AgregarPago($pago);

        // $formularioRepository = new FormularioInscripcionDao;

        // $exito = $formularioRepository->pagarInscripcion($formularioInscripcion->getId(), $formularioInscripcion->getVoucher());
        // if (!$exito) {
        //     return new Response("500", "Ha ocurrido un error al pagar la inscripción");
        // }

        return new Response("201", "Se ha registrado con éxito.");
    }
}