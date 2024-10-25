<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\ReporteNumeroCursoYParticipantePorJornadaDao;
use Src\domain\Calendario;

class ReporteNumeroCursoYParticipantePorJornadaUseCase
{
    public function ejecutar(int $calendarioId=0): array
    {
        $reporte = [];

        if ($calendarioId <= 0) {
            return $reporte;
        }

        $calendario = new Calendario();
        $calendario = $calendario->buscarPorId($calendarioId);

        if (!$calendario->existe()) {
            return $reporte;
        }

        $reporte = $calendario->generarReporteNumeroCursoYParticipantePorJornada();

        return $reporte;
    }
}