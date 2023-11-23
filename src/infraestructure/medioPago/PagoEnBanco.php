<?php

namespace Src\infraestructure\medioPago;

use Carbon\Carbon;
use Src\infraestructure\pdf\DataPDF;
use Src\infraestructure\pdf\SicePDF;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\Response;

class PagoEnBanco implements IMedioPago{

    public function Pagar(ConfirmarInscripcionDto $confirmarInscripcionDto): Response {

        

        $nombreArchivo = "RECIBO_PAGO_" . strtotime(Carbon::now()) . $confirmarInscripcionDto->formularioId . ".pdf";

        $exito = SicePDF::generar(new DataPDF($nombreArchivo));
        
        if (!$exito) {
            return new Response("500", "Ha ocurrido un error al generar el pdf");
        }

        $response = new Response("201", "Se ha registrado con éxito.");
        $response->data['nombre_archivo'] = $nombreArchivo;
        
        return $response;
    }
}