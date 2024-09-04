<?php

namespace Src\usecase\dashboard;

use Src\domain\Calendario;
use Src\domain\FormularioInscripcion;
use Src\domain\Grupo;

class DashboardUseCase {

    public function ejecutar() {

        $datosDashboard = [
            'totalMatriculados' => 0,
            'totalRevisionesPago' => 0,
            'totalPendintesDePago' => 0,
            'totalAnulados' => 0,
            'pagoSinDescuento' => 0,
            'pagoPorConvenio' => 0,
            'pagoPendientes' => 0,
            'pagoTotal' => 0,
            'totalCursosSinCupos' => 0,
            'totalCancelados' => 0,
            'totalAplazados' => 0,
            'totalDevolucion' => 0,
        ];
                
        $calendarioVigente = Calendario::Vigente();
        if (!$calendarioVigente->existe()) {
            return $datosDashboard;
        }

        $recaudos = FormularioInscripcion::totalDeDineroRecaudado($calendarioVigente->getId());        
        $datosDashboard['totalMatriculados'] = FormularioInscripcion::totalInscripcionesLegalizadas($calendarioVigente->getId());
        $datosDashboard['totalAplazados'] = FormularioInscripcion::totalPorEstadoYCalendario('Aplazado', $calendarioVigente->getId());
        $datosDashboard['totalPendintesDePago'] = FormularioInscripcion::totalPorEstadoYCalendario('Pendiente de pago', $calendarioVigente->getId());
        $datosDashboard['totalRevisionesPago'] = FormularioInscripcion::totalPorEstadoYCalendario('Revisar comprobante de pago', $calendarioVigente->getId());
        $datosDashboard['totalAnulados'] = FormularioInscripcion::totalPorEstadoYCalendario('Anulado', $calendarioVigente->getId());
        $datosDashboard['totalDevolucion'] = FormularioInscripcion::totalPorEstadoYCalendario('Devuelto', $calendarioVigente->getId());
        $datosDashboard['pagoSinDescuento'] = '$' . number_format($recaudos["RECAUDO_SIN_CONVENIO"], 0, ',', '.') . ' COP';
        $datosDashboard['pagoPorConvenio'] = '$' . number_format($recaudos["RECAUDO_POR_CONVENIO"], 0, ',', '.') . ' COP';
        $datosDashboard['pagoPendientes'] = number_format(FormularioInscripcion::totalDeDineroPendienteDePago($calendarioVigente->getId()), 0, '.', ',');
        $datosDashboard['pagoTotal'] = '$' . number_format($recaudos["RECAUDO_TOTAL"], 0, ',', '.'). ' COP';
        $datosDashboard['totalCancelados'] = Grupo::totalGruposCancelados($calendarioVigente->getId());  
        $datosDashboard['totalCursosSinCupos'] = Grupo::totalSinCupoDisponible();
        
        return $datosDashboard;
    }
}