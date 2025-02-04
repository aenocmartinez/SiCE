<?php

namespace Src\usecase\convenios;

use Src\dao\mysql\ConvenioDao;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\dao\mysql\ParticipanteDao;
use Src\domain\Convenio;

class FacturarConvenioUseCase {
    
    const TIPO_DOCUMENTO = 1;
    const DOCUMENTO = 2;
    const ENCABEZADO_DE_LA_LISTA = 0;

    public function ejecutar(Convenio $convenio): Convenio {

        $totalPagoFactura = 0;

        $convenioDao = new ConvenioDao();
        $participanteDao = new ParticipanteDao();

      /*   $convenio = $convenioDao->buscarConvenioPorId($convenioId);
        if (!$convenio->existe()) {
            return $convenio;
        } */

        if ($convenio->getDescuento() == 0) {
            return $convenio;
        }

        foreach($convenio->listarParticipantes() as $index => $data) {
            
            if ($index == self::ENCABEZADO_DE_LA_LISTA) {
                continue;
            }

            $participante = $participanteDao->buscarParticipantePorDocumento($data[self::TIPO_DOCUMENTO], $data[self::DOCUMENTO]);
            
            $formularioInscripcion = $participante->formularioInscripcionPorConvenioPendienteDePago($convenio);
            if (!$formularioInscripcion->existe()) {
                continue;
            }
            
            $descuento = $formularioInscripcion->getCostoCurso() * ($convenio->getDescuento()/100);
            $totalPago = $formularioInscripcion->getCostoCurso() - $descuento;
            $totalPagoFactura += $totalPago;

            $formularioInscripcion->setTotalAPagar($totalPago);
            $formularioInscripcion->setValorDescuento($descuento);
            $formularioInscripcion->setEstado("Pagado");

            $formularioInscripcion->FacturarConvenio();
        }

        $convenio->setTotalAPagar($totalPagoFactura);
        $convenio->setHaSidoFacturado(true);      
        $convenio->actualizarTotalAPagar();    

        
        return $convenio;
    }
}