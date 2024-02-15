<?php

namespace Src\usecase\dashboard;

class TotalesInscripcionesUseCase {

    public function ejecutar($inscripciones=array()): array{
        $totales = array();

        $totalInscripciones = sizeof($inscripciones);
        $totalMatriculados = 0;
        $totalPorConvenio = 0;
        $totalPendintesDePago = 0;
        $totalAnulados = 0;

        $pagoSinDescuento = 0;
        $pagoPorConvenio = 0;
        $pagoPendientes = 0;

        foreach($inscripciones as $inscripcion) {
            
            if ($inscripcion->tieneConvenio()) {
                $totalPorConvenio++;
                $pagoPorConvenio += $inscripcion->getTotalAPagar();
            }

            if ($inscripcion->Pagado()) {
                $totalMatriculados++;
                $pagoSinDescuento += $inscripcion->getTotalAPagar();
            }

            if ($inscripcion->PendienteDePago()) {
                $totalPendintesDePago++;
                $pagoPendientes += $inscripcion->getTotalAPagar();
            }

            if ($inscripcion->Anulado()) {
                $totalAnulados++;                
            }
            
        }

        $totales['totalInscripciones'] = $totalInscripciones;
        $totales['totalMatriculados'] = $totalMatriculados;
        $totales['totalPorConvenio'] = $totalPorConvenio;
        $totales['totalPendintesDePago'] = $totalPendintesDePago;
        $totales['totalAnulados'] = $totalAnulados;
        $totales['pagoSinDescuento'] = number_format($pagoSinDescuento, 0, '.', ',');
        $totales['pagoPorConvenio'] = number_format($pagoPorConvenio, 0, '.', ',');
        $totales['pagoPendientes'] = number_format($pagoPendientes, 0, '.', ',');
        $totales['pagoTotal'] = number_format(($pagoSinDescuento + $pagoPorConvenio), 0, '.', ',');

        return $totales;
    }
}