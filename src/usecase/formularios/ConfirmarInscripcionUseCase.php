<?php

namespace Src\usecase\formularios;

use Carbon\Carbon;
use Src\dao\mysql\DiaFestivoDao;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\infraestructure\diasFestivos\Calendario;
use Src\domain\Convenio;
use Src\domain\FormularioInscripcion;
use Src\domain\FormularioInscripcionPago;
use Src\domain\Grupo;
use Src\domain\Participante;
use Src\infraestructure\medioPago\PagoFactory;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\Response;

class ConfirmarInscripcionUseCase {
    
    public function ejecutar(ConfirmarInscripcionDto $confirmarInscripcionDto): Response { 
           
        date_default_timezone_set('America/Bogota');
        $fechaActual = Carbon::now();        
        $diaFestivo = DiaFestivoDao::buscarDiasFestivoPorAnio($fechaActual->year);
        $diasFestivos = [];
        if ($diaFestivo->existe()) {
            $diasFestivos = explode(',', $diaFestivo->getFechas());
        }

        $formularioInscripcion = new FormularioInscripcion();

        $grupo = new Grupo();
        $grupo->setId($confirmarInscripcionDto->grupoId);

        $convenio = new Convenio();
        $convenio->setId($confirmarInscripcionDto->convenioId);

        $participante = new Participante();
        $participante->setId($confirmarInscripcionDto->participanteId);

        $formularioInscripcion->setGrupo($grupo);
        $formularioInscripcion->setConvenio($convenio);
        $formularioInscripcion->setParticipante($participante);
        $formularioInscripcion->setEstado($confirmarInscripcionDto->estado);  
        $formularioInscripcion->setCostoCurso($confirmarInscripcionDto->costoCurso);
        $formularioInscripcion->setValorDescuento($confirmarInscripcionDto->valorDescuento);
        $formularioInscripcion->setTotalAPagar($confirmarInscripcionDto->totalAPagar);
        $formularioInscripcion->setFechaCreacion($fechaActual);
        $formularioInscripcion->setFechaMaxLegalizacion(Calendario::fechaSiguienteDiaHabil($fechaActual, $diasFestivos));
        $formularioInscripcion->setNumero(strtotime($fechaActual) . $confirmarInscripcionDto->participanteId);
        $formularioInscripcion->setValorPago($confirmarInscripcionDto->valorPagoParcial);
        $formularioInscripcion->setPathComprobantePago($confirmarInscripcionDto->pathComprobantePago);

        if (!$grupo->tieneCuposDisponibles()) {            
            return new Response("409", "El grupo no tiene cupos disponibles");
        }
        
        $exito = $formularioInscripcion->Crear();
        if (!$exito) {
            return new Response("500", "Ha ocurrido al intentar confirmar la inscripción.");
        }

        $medioPago = PagoFactory::Medio($confirmarInscripcionDto->medioPago);
        $exito = $medioPago->Pagar($formularioInscripcion, $confirmarInscripcionDto->voucher, $confirmarInscripcionDto->valorPagoParcial);
        if (!$exito) {
            return new Response("500", "Ha ocurrido al intentar agregar el pago del formulario.");
        }
        
        $formularioInscripcion->RedimirBeneficioConvenio();

        return new Response("201", "¡La inscripción se ha realizado con éxito!");
    }
}