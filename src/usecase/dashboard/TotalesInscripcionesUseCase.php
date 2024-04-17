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
        $totalRevisarComprobantesPago = 0;

        $pagoSinDescuento = 0;
        $pagoPorConvenio = 0;
        $pagoPendientes = 0;
        $pagoRevisarComprobantePago = 0;
        $recaudoTotal = 0;

        foreach($inscripciones as $inscripcion) {
            
            if ($inscripcion->Pagado()) {
                $totalMatriculados++;
                $recaudoTotal += $inscripcion->getTotalAPagar();
            }

            if ($inscripcion->RevisarComprobanteDePago()) {
                $recaudoTotal += $inscripcion->getTotalAPagar();
            }            

            if ($inscripcion->tieneConvenio()) {
                $totalPorConvenio++;         
                if ($inscripcion->Pagado()) {
                    $pagoPorConvenio += $inscripcion->getTotalAPagar();
                }   
            } else if ($inscripcion->Pagado()) {
                $pagoSinDescuento += $inscripcion->getTotalAPagar();
            }           

            if ($inscripcion->PendienteDePago()) {
                $totalPendintesDePago++;
                $pagoPendientes += $inscripcion->getTotalAPagar();
            }  

            if ($inscripcion->RevisarComprobanteDePago()) {
                $totalRevisarComprobantesPago++;
                $pagoRevisarComprobantePago += $inscripcion->getTotalAPagar();
            }              

            if ($inscripcion->Anulado()) {
                $totalAnulados++;                
            }
            
        }

        $totales['totalInscripciones'] = $totalInscripciones;
        $totales['totalMatriculados'] = $totalMatriculados;
        $totales['totalPorConvenio'] = $totalPorConvenio;
        $totales['totalPendintesDePago'] = $totalPendintesDePago;
        $totales['totalRevisionesPago'] = $totalRevisarComprobantesPago;
        $totales['totalAnulados'] = $totalAnulados;
        $totales['pagoSinDescuento'] = number_format($pagoSinDescuento, 0, '.', ',');
        $totales['pagoPorConvenio'] = number_format($pagoPorConvenio, 0, '.', ',');
        $totales['pagoPendientes'] = number_format($pagoPendientes, 0, '.', ',');
        $totales['pagoRevisiones'] = number_format($pagoRevisarComprobantePago, 0, '.', ',');
        $totales['pagoTotal'] = number_format(($recaudoTotal), 0, '.', ',');

        return $totales;
    }
}