<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\dao\mysql\CursoDao;
use Src\dao\mysql\ParticipanteDao;
use Src\domain\Calendario;
use Src\usecase\dashboard\TotalesInscripcionesUseCase;

class EstadisticasCalendarioUseCase {

    public function ejecutar($id=0) {

        $data = [
            "nombre" => "",
            "fechaInicio" => "",
            "fechaFin" => "",
            "estado" => "",
            "numeroParticipantes" => 0,
            "numeroCursos" => 0,
            "totalIngresos" => 0,
            "participantesMujeres" => 0,
            "participantesHombres" => 0,
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

        $data["participantesHombres"] = ParticipanteDao::numeroParticipantesPorGeneroYCalendario('M', $calendario->getId());
        $data["participantesMujeres"] = ParticipanteDao::numeroParticipantesPorGeneroYCalendario('F', $calendario->getId());
        $data["topCursosInscritos"] = CursoDao::top5CursosMasInscritosPorCalendario($calendario->getId());
                
        $data["existe"] = true;
        $data["nombre"] = $calendario->getNombre();
        $data["fechaInicio"] = $calendario->getFechaInicioFormateada();
        $data["fechaFin"] = $calendario->getFechaFinalFormateada();
        $data["estado"] = $calendario->estado();
        $data["numeroCursos"] = 0;
        $data["totalIngresos"] = $totales["pagoTotal"];        
        $data["totalParticipantes"] = $totales["totalMatriculados"];
        

        return $data;
    }
}