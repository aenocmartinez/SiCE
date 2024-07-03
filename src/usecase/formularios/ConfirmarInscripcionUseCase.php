<?php

namespace Src\usecase\formularios;

use Carbon\Carbon;
use Src\dao\mysql\DiaFestivoDao;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\infraestructure\diasFestivos\Calendario;
use Src\domain\FormularioInscripcion;
use Src\domain\Grupo;
use Src\domain\Participante;
use Src\infraestructure\medioPago\PagoFactory;
use Src\infraestructure\util\UUID;
use Src\usecase\convenios\BuscarConvenioPorIdUseCase;
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
        if ($confirmarInscripcionDto->formularioId > 0) {
            $formularioInscripcion = FormularioInscripcionDao::buscarFormularioPorId($confirmarInscripcionDto->formularioId);
        }

        $grupo = new Grupo();
        $grupo->setId($confirmarInscripcionDto->grupoId);


        $convenio = (new BuscarConvenioPorIdUseCase)->ejecutar($confirmarInscripcionDto->convenioId);

        $totalAPagar = $confirmarInscripcionDto->totalAPagar;
        $estado = $confirmarInscripcionDto->estado;
        if ($convenio->esCooperativa()) {
            $totalAPagar = 0;
            $estado = "Pagado";
        }        

        $participante = new Participante();
        $participante->setId($confirmarInscripcionDto->participanteId);

        $formularioInscripcion->setGrupo($grupo);
        $formularioInscripcion->setConvenio($convenio);
        $formularioInscripcion->setParticipante($participante);
        $formularioInscripcion->setEstado($estado);  
        $formularioInscripcion->setCostoCurso($confirmarInscripcionDto->costoCurso);
        $formularioInscripcion->setValorDescuento($confirmarInscripcionDto->valorDescuento);
        $formularioInscripcion->setTotalAPagar($totalAPagar);
        $formularioInscripcion->setFechaCreacion($fechaActual);
        $formularioInscripcion->setMedioInscripcion($confirmarInscripcionDto->medioInscripcion);
        
        $formularioInscripcion->setFechaMaxLegalizacion(Calendario::fechaSiguienteDiaHabil($fechaActual, $diasFestivos));
        if (strlen($confirmarInscripcionDto->fec_max_legalizacion)>0) {
            $formularioInscripcion->setFechaMaxLegalizacion($confirmarInscripcionDto->fec_max_legalizacion);
        }

        $numeroFormulario = UUID::generarUUIDNumerico();
        
        // $formularioInscripcion->setNumero(strtotime($fechaActual->format('Y-m-d H:i:s.u')) . $confirmarInscripcionDto->participanteId);
        $formularioInscripcion->setNumero($numeroFormulario);
        $formularioInscripcion->setValorPago($confirmarInscripcionDto->valorPagoParcial);
        $formularioInscripcion->setPathComprobantePago($confirmarInscripcionDto->pathComprobantePago);

        if (strlen($confirmarInscripcionDto->comentarios)>0) {
            $formularioInscripcion->setComentarios($confirmarInscripcionDto->comentarios);
        }

        if (!$grupo->tieneCuposDisponibles()) {            
            return new Response("409", "El grupo no tiene cupos disponibles");
        }

        $exito = false;
        if ($formularioInscripcion->existe()) {    
            
            $formularioInscripcion->setEstado('Revisar comprobante de pago');
            
            $exito = $formularioInscripcion->Actualizar();            
            if (!$exito) {                
                return new Response("500", "Ha ocurrido al intentar confirmar la inscripción.");
            }

        } else { 
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
        }

        // EmailService::SendEmail("Confirmación de inscripción cursos de extensión - UCMC", Mensajes::CuerpoCorreoConfirmacionInscripcion($formularioInscripcion->getNumero()), env('EMAIL_DESTINATARIOS'));

        return new Response("201", "¡La inscripción se ha realizado con éxito!");
    }
}