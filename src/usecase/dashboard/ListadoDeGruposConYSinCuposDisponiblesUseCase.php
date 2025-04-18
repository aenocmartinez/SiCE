<?php

namespace Src\usecase\dashboard;

use Src\domain\Calendario;
use Src\usecase\calendarios\BuscarCalendarioPorIdUseCase;

class ListadoDeGruposConYSinCuposDisponiblesUseCase {

    public function ejecutar($periodoId=0): array {
        $grupos = [
            'sin_cupos' => [],
            'con_cupos' => [],
            'cancelados' => [],
        ];
        $grupos_sin_cupos = [];
        $grupos_con_cupos = [];
        $grupos_cancelados = [];

        $periodo = Calendario::Vigente();
        if ($periodoId > 0) {
            $periodo = (new BuscarCalendarioPorIdUseCase)->ejecutar($periodoId);
        }

        foreach ($periodo->listaDeGrupos() as $grupo) {

            $item = [
                'grupo_id' => $grupo->getId(),
                'grupo' => $grupo->getNombre(),
                'jornada' => $grupo->getJornada(),
                'dia' => $grupo->getDia(),
                'curso' => $grupo->getNombreCurso(),
                'periodo' => $grupo->getNombreCalendario(),
                'orientador' => $grupo->getNombreOrientador(),
                'total_inscritos' => $grupo->getTotalInscritos(),
                'cupos' => $grupo->getCupo(),
                'total_participantes_pendientes_de_pago_sin_convenio' => $grupo->totalDeParticipantesPendientesDePagoSinConvenio(),
            ];

            if ($grupo->estaCancelado()) {
                $grupos_cancelados[] = $item;
                continue;                
            }   
            
            if ($grupo->tieneCuposDisponibles()) {                
                $grupos_con_cupos[] = $item;
                continue;
            }

            $grupos_sin_cupos[] = $item;
        }

        $grupos['sin_cupos'] = $grupos_sin_cupos; 
        $grupos['con_cupos'] = $grupos_con_cupos;
        $grupos['cancelados'] = $grupos_cancelados; 

        return $grupos;
    }
}