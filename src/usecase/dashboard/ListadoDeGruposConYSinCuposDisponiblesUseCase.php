<?php

namespace Src\usecase\dashboard;

use Src\domain\Calendario;

class ListadoDeGruposConYSinCuposDisponiblesUseCase {

    public function ejecutar(): array {
        $grupos = [];
        $grupos_sin_cupos = [];
        $grupos_con_cupos = [];

        $periodo = Calendario::Vigente();
        if (!$periodo->existe()) {
            return $grupos;
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
            ];
            
            if ($grupo->tieneCuposDisponibles()) {                
                $grupos_con_cupos[] = $item;
                continue;
            }

            $grupos_sin_cupos[] = $item;
        }

        $grupos['sin_cupos'] = $grupos_sin_cupos; 
        $grupos['con_cupos'] = $grupos_con_cupos; 

        return $grupos;
    }
}