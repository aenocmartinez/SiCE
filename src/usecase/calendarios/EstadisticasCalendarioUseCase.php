<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\dao\mysql\CursoDao;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\dao\mysql\ParticipanteDao;
use Src\domain\Calendario;
use Src\domain\FormularioInscripcion;
use Src\domain\Grupo;
use Src\usecase\dashboard\DashboardUseCase;
use Src\usecase\dashboard\TotalesInscripcionesUseCase;

class EstadisticasCalendarioUseCase {

    public function ejecutar($id=0) {

        $data = [
            "nombre" => "",
            "fechaInicio" => "",
            "fechaFin" => "",
            "estado" => "",
            "numeroParticipantesUnicos" => 0,
            "totalIngresos" => 0,
            "ingresosConvenio" => 0,
            "participantesMujeres" => 0,
            "participantesHombres" => 0,
            "participantesOtrosGeneros" => 0,
            "participantesConvenio" => 0,
            "topCursosInscritos" => [],            
            "existe" => false,
        ];

        $calendario = Calendario::buscarPorId($id);

        if (!$calendario->existe()) {
            return $data;
        }

        $calendario->setRepository(new CalendarioDao());

        $data["participantesHombres"] = ParticipanteDao::numeroParticipantesPorGeneroYCalendario('M', $calendario->getId());
        $data["participantesMujeres"] = ParticipanteDao::numeroParticipantesPorGeneroYCalendario('F', $calendario->getId());
        $data["participantesOtrosGeneros"] = ParticipanteDao::numeroParticipantesPorGeneroYCalendario('Otro', $calendario->getId());
        
        $data["totalInscripcionesLegalizadas"] = FormularioInscripcion::totalInscripcionesLegalizadas($calendario->getId());
        $data["totalInscripcionesLegalizadasPorConvenio"] = FormularioInscripcion::totalInscripcionesLegalizadasPorConvenio($calendario->getId());
        $data["totalInscripcionesLegalizadasRegulares"] = FormularioInscripcion::totalInscripcionesLegalizadasRegulares($calendario->getId());
        $data["totalFormularioInscritosEnOficina"] = FormularioInscripcion::contadorInscripcionesSegunMedio('en oficina', $calendario->getId());
        $data["totalFormularioInscritosEnLinea"] = FormularioInscripcion::contadorInscripcionesSegunMedio('formulario publico', $calendario->getId());
        $data["listaRecaudoPorAreas"] = FormularioInscripcion::listadoDeRecaudoPorAreas($calendario->getId());
        $recaudos = FormularioInscripcion::totalDeDineroRecaudado($calendario->getId());

        $data["total_recaudo"]        = '$' . number_format($recaudos["RECAUDO_TOTAL"], 0, ',', '.') . ' COP';
        $data["total_por_convenio"]   = '$' . number_format($recaudos["RECAUDO_POR_CONVENIO"], 0, ',', '.') . ' COP';
        $data["total_sin_convenio"]   = '$' . number_format($recaudos["RECAUDO_SIN_CONVENIO"], 0, ',', '.') . ' COP';
        $data["total_aplazados"]      = '$' . number_format($recaudos["RECAUDO_APLAZADO"], 0, ',', '.') . ' COP';
                
        $data["existe"] = true;
        $data["nombre"] = $calendario->getNombre();
        $data["fechaInicio"] = $calendario->getFechaInicioFormateada();
        $data["fechaFin"] = $calendario->getFechaFinalFormateada();
        $data["estado"] = $calendario->estado();


        // Otros datos        
        $data['totalMatriculados'] = FormularioInscripcion::totalInscripcionesLegalizadas($calendario->getId());
        $data['totalAplazados'] = FormularioInscripcion::totalPorEstadoYCalendario('Aplazado', $calendario->getId());
        $data['totalPendintesDePago'] = FormularioInscripcion::totalPorEstadoYCalendario('Pendiente de pago', $calendario->getId());
        $data['totalAnulados'] = FormularioInscripcion::totalPorEstadoYCalendario('Anulado', $calendario->getId());
        $data['totalDevolucion'] = FormularioInscripcion::totalPorEstadoYCalendario('Devuelto', $calendario->getId());
        $data['totalCancelados'] = Grupo::totalGruposCancelados($calendario->getId());  
        $data['totalCursosSinCupos'] = Grupo::totalSinCupoDisponible($calendario->getId());

        return $data;
    }
}