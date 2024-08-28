<?php

namespace Src\usecase\dashboard;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;
use Src\usecase\grupos\ListarCursosPorCalendarioUseCase;
use Src\usecase\orientadores\ListarOrientadoresUseCase;

class DashboardUseCase {

    public function ejecutar() {

        $datosDashboard = [
            'totalInscripciones' => 0,
            'totalMatriculados' => 0,
            'totalRevisionesPago' => 0,
            'totalPorConvenio' => 0,
            'totalPendintesDePago' => 0,
            'totalAnulados' => 0,
            'pagoSinDescuento' => 0,
            'pagoRevisiones' => 0,
            'pagoPorConvenio' => 0,
            'pagoPendientes' => 0,
            'pagoTotal' => 0,
            'totalCursosAbiertos' => 0,
            'totalOrientadores' => 0,
            'totalCursosCreados' => 0,
            'totalCursosSinCupos' => 0,
            'totalCursosConCupos' => 0,
        ];

        $grupos = (new ListadoDeGruposConYSinCuposDisponiblesUseCase)->ejecutar();
        
        $listaOrientadores = (new ListarOrientadoresUseCase)->ejecutar();        
                
        $calendarioVigente = Calendario::Vigente();

        if (!$calendarioVigente->existe()) {
            $datosDashboard['totalOrientadores'] = sizeof($listaOrientadores);
            return $datosDashboard;
        }

        $calendarioVigente->setRepository(new CalendarioDao);

        $datosDashboard = (new TotalesInscripcionesUseCase)->ejecutar($calendarioVigente->getId());              
        
        $listaCursosAbiertos = (new ListarCursosPorCalendarioUseCase)->ejecutar($calendarioVigente->getId());
        $datosDashboard['totalCursosAbiertos'] = sizeof($listaCursosAbiertos);        

        $datosDashboard['totalOrientadores'] = sizeof($listaOrientadores);
        $datosDashboard['totalCursosSinCupos'] = sizeof($grupos['sin_cupos']);
        $datosDashboard['totalCursosConCupos'] = sizeof($grupos['con_cupos']);
        
        return $datosDashboard;
    }
}