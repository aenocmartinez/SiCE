<?php

namespace Src\usecase\formularios;

use Carbon\Carbon;
use Src\dao\mysql\ConvenioDao;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\Convenio;
use Src\infraestructure\medioPago\PagoFactory;
use Src\view\dto\Response;

use function Ramsey\Uuid\v1;

class LegalizarInscripcionUseCase {

    public function ejecutar($datosLegalizacion): Response {

        $response = new Response;

        $formulario = FormularioInscripcionDao::buscarFormularioPorId($datosLegalizacion['formularioId']);
        if (!$formulario->existe()) {
            $response->code = "404";
            $response->message = "El formulario no existe";
            return $response;
        }

        $formulario->setTotalAPagar($datosLegalizacion['total_a_pagar']);
        $formulario->setValorDescuento($datosLegalizacion['valor_descuento']);
        $formulario->setEstado("Pagado");

        if (strlen($datosLegalizacion['comentarios'])>0) {
            $formulario->setComentarios($datosLegalizacion['comentarios']);
        }        
        
        
        $convenio = new Convenio();
        if ($datosLegalizacion['convenioId'] > 0) {
            $convenio->setId($datosLegalizacion['convenioId']);
            $formulario->setConvenio($convenio);
        }
        
        $exito = $formulario->Actualizar();
        if (!$exito) {
            $response->code = "500";
            $response->message = "Ha ocurrido un error en el sistema al actualizar el formulario";
        }
        
        $medioPago = PagoFactory::Medio($datosLegalizacion['medioPago']);        
        $medioPago->Pagar($formulario, $datosLegalizacion['voucher'], $datosLegalizacion['valorPago']);
        

        $formulario->RedimirBeneficioConvenio();
        
        $response->code = "200";
        $response->message = "El formulario se ha legalizado con éxito.";
        return $response;
    }
}