<?php

namespace Src\usecase\orientadores;

use Illuminate\Support\Facades\Auth;
use Src\dao\mysql\OrientadorDao;
use Src\domain\Calendario;
use Src\domain\Orientador;

class DashboardOrientadorUseCase
{
    public function ejecutar(): array
    {
        $periodo = Calendario::Vigente();
        if (!$periodo->existe()) 
        {
            return [];
        }

        $orientadorDao = new OrientadorDao();

        $orientador = $orientadorDao->buscarOrientadorPorId(Auth::user()->orientador_id);
        if (!$orientador->existe())
        {
            return [];
        }

        $orientador->setGruposPorCalendario($periodo->getId());

        // dd($orientador);
        $totalCursosActuales = 0;
        $totalParticipantes = 0;
        $horario = [
            "Lunes" => [],
            "Martes" => [],
            "Miércoles" => [],
            "Jueves" => [],
            "Viernes" => [],
            "Sábado" => [],
        ];

        foreach($orientador->misGrupos() as $grupo) 
        {
            if ($grupo->estaCancelado()) 
            {
                continue;
            }

            $totalCursosActuales++;
            $totalParticipantes += $grupo->getTotalInscritos();
            $horario[$grupo->getDia()][] = [
                "nombre_curso" => $grupo->getNombreCurso(),
                "jornada" => $grupo->getJornada(),
                "nombre_salon" => $grupo->getNombreSalon(),
                "codigo_grupo" => $grupo->getCodigoGrupo(),
                "total_participantes" => $grupo->getTotalInscritos(),
            ];
        }

        return [
            "total_cursos_actuales" => $totalCursosActuales,
            "total_participantes" => $totalParticipantes,
            "horario" => $horario,
        ];
        
    }
}