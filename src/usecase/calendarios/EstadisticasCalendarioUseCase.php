<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\dao\mysql\CursoDao;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\dao\mysql\ParticipanteDao;
use Src\domain\Calendario;
use Src\domain\FormularioInscripcion;
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

        $inscripciones = $calendario->formulariosInscritos();

        $totales = (new TotalesInscripcionesUseCase)->ejecutar($inscripciones);
        
        // $lista = (new ListarCursosPorCalendarioUseCase)->ejecutar($calendario->getId());

        // $data["participantesHombres"] = ParticipanteDao::numeroParticipantesPorGeneroYCalendario('M', $calendario->getId());
        $data["participantesHombres"] = ParticipanteDao::where('sexo','M')->count();

        // $data["participantesMujeres"] = ParticipanteDao::numeroParticipantesPorGeneroYCalendario('F', $calendario->getId());
        $data["participantesMujeres"] = ParticipanteDao::where('sexo','F')->count();

        // $data["participantesOtrosGeneros"] = ParticipanteDao::numeroParticipantesPorGeneroYCalendario('Otro', $calendario->getId());
        $data["participantesOtrosGeneros"] = ParticipanteDao::where('sexo','Otros')->count();

        $data["participantesConvenio"] = ParticipanteDao::numeroParticipantesPorConvenioYCalendario($calendario->getId());
        $data["topCursosInscritos"] = CursoDao::top5CursosMasInscritosPorCalendario($calendario->getId());
        // $data["numeroParticipantesUnicos"] = ParticipanteDao::numeroParticipantesUnicosPorCalendario($calendario->getId());
        $data["numeroParticipantesUnicos"] = ParticipanteDao::count();
        
        $data["totalFormularioInscritosEnOficina"] = FormularioInscripcion::contadorInscripcionesSegunMedio('en oficina');
        $data["totalFormularioInscritosEnLinea"] = FormularioInscripcion::contadorInscripcionesSegunMedio('formulario publico');
                
        $data["existe"] = true;
        $data["nombre"] = $calendario->getNombre();
        $data["fechaInicio"] = $calendario->getFechaInicioFormateada();
        $data["fechaFin"] = $calendario->getFechaFinalFormateada();
        $data["estado"] = $calendario->estado();
        $data["ingresosConvenio"] = $totales["pagoPorConvenio"];
        $data["totalIngresos"] = $totales["pagoTotal"];
        $data["totalParticipantes"] = $totales["totalMatriculados"];
                

        return $data;
    }
}