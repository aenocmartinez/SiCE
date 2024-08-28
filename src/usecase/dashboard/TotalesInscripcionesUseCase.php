<?php

namespace Src\usecase\dashboard;

use Src\domain\FormularioInscripcion;

class TotalesInscripcionesUseCase {

    public function ejecutar($calendarioId=0): array{
        $totales = array();

        // $totalInscripciones = sizeof($inscripciones);
        $totalPorConvenio = 0;

        $recaudos = FormularioInscripcion::totalDeDineroRecaudado($calendarioId);
        
        // $totales['totalInscripciones'] = $totalInscripciones;
        $totales['totalMatriculados'] = FormularioInscripcion::totalInscripcionesLegalizadas($calendarioId);
        $totales['totalPorConvenio'] = $totalPorConvenio;
        $totales['totalPendintesDePago'] = FormularioInscripcion::totalPorEstadoYCalendario('Pendiente de pago', $calendarioId);
        $totales['totalRevisionesPago'] = FormularioInscripcion::totalPorEstadoYCalendario('Revisar comprobante de pago', $calendarioId);
        $totales['totalAnulados'] = FormularioInscripcion::totalPorEstadoYCalendario('Anulado', $calendarioId);
        $totales['pagoSinDescuento'] = '$' . number_format($recaudos["RECAUDO_SIN_CONVENIO"], 0, ',', '.') . ' COP';
        $totales['pagoPorConvenio'] = '$' . number_format($recaudos["RECAUDO_POR_CONVENIO"], 0, ',', '.') . ' COP';
        $totales['pagoPendientes'] = number_format(FormularioInscripcion::totalDeDineroPendienteDePago($calendarioId), 0, '.', ',');
        $totales['pagoTotal'] = '$' . number_format($recaudos["RECAUDO_TOTAL"], 0, ',', '.'). ' COP';

        return $totales;
    }
}