<?php

namespace Src\usecase\dashboard;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;
use Src\usecase\cursos\ListarCursosUseCase;
use Src\usecase\grupos\ListarCursosPorCalendarioUseCase;
use Src\usecase\orientadores\ListarOrientadoresUseCase;

class DashboardUseCase {

    public function ejecutar() {

        $datosDashboard = [
            'totalInscripciones' => 0,
            'totalMatriculados' => 0,
            'totalPorConvenio' => 0,
            'totalPendintesDePago' => 0,
            'totalAnulados' => 0,
            'pagoSinDescuento' => 0,
            'pagoPorConvenio' => 0,
            'pagoPendientes' => 0,
            'pagoTotal' => 0,
            'totalCursosAbiertos' => 0,
            'totalOrientadores' => 0,
            'totalCursosCreados' => 0,
        ];

        $listaOrientadores = (new ListarOrientadoresUseCase)->ejecutar();
        $datosDashboard['totalOrientadores'] = sizeof($listaOrientadores);

        $listaCursos = $listarCursos = (new ListarCursosUseCase)->ejecutar();
        $datosDashboard['totalCursosCreados'] = sizeof($listaCursos);
                
        
        $calendarioVigente = Calendario::Vigente();

        if (!$calendarioVigente->existe()) {
            return $datosDashboard;
        }

        $calendarioVigente->setRepository(new CalendarioDao);

        $inscripciones = $calendarioVigente->formulariosInscritos();

        $datosDashboard = (new TotalesInscripcionesUseCase)->ejecutar($inscripciones);

        
        $listaCursosAbiertos = (new ListarCursosPorCalendarioUseCase)->ejecutar($calendarioVigente->getId());
        $datosDashboard['totalCursosAbiertos'] = sizeof($listaCursosAbiertos);        

        return $datosDashboard;
    }
}