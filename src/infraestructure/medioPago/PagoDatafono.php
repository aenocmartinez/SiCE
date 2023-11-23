<?php

namespace Src\infraestructure\medioPago;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\Response;

class PagoDatafono implements IMedioPago {

    public function Pagar(ConfirmarInscripcionDto $confirmarInscripcionDto): Response {
        
        $formularioRepository = new FormularioInscripcionDao;

        $exito = $formularioRepository->pagarInscripcion($confirmarInscripcionDto->formularioId, $confirmarInscripcionDto->voucher);
        if (!$exito) {
            return new Response("500", "Ha ocurrido un error al pagar la inscripción");
        }

        return new Response("201", "Se ha registrado con éxito.");
    }
}