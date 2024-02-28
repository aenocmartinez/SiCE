<?php

namespace Src\usecase\formularios;

use Carbon\Carbon;
use Src\dao\mysql\ConvenioDao;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\view\dto\Response;

class LegalizarInscripcionUseCase {

    public function ejecutar($datosLegalizacion): Response {

        $response = new Response;

        $formularioRepository = new FormularioInscripcionDao();
        date_default_timezone_set('America/Bogota');
        $fechaActual = Carbon::now();

        $formulario = $formularioRepository->buscarFormularioPorId($datosLegalizacion['formularioId']);
        if (!$formulario->existe()) {
            $response->code = "404";
            $response->message = "El formulario no existe";
        }        

        $formulario->setMedioPago("pagoDatafono");
        $formulario->setValorPagoParcial($datosLegalizacion['valorPago']);
        $formulario->setVoucher($datosLegalizacion['voucher']);
        $formulario->setFechaCreacion($fechaActual);

        $exito = $formulario->AgregarPago();
        if (!$exito) {
            $response->code = "500";
            $response->message = "Ha ocurrido un error en el sistema al agregar el pago";
        }
      
        $formulario->setTotalAPagar($datosLegalizacion['total_a_pagar']);        

        if ($datosLegalizacion['convenioId'] > 0) {
            $convenio = (new ConvenioDao())->buscarConvenioPorId($datosLegalizacion['convenioId']);
            $formulario->setConvenio($convenio);            
            $formulario->setValorDescuento($datosLegalizacion['valor_descuento']);
        }

        $exito = $formulario->Legalizar();
        if (!$exito) {
            $response->code = "500";
            $response->message = "Ha ocurrido un error en el sistema al cambiar el estado del formulario";
        }

        
        $response->code = "200";
        $response->message = "El formulario se ha legalizado con éxito.";
        return $response;
    }
}