<?php

namespace Src\usecase\convenios;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Src\domain\Convenio;
use Src\domain\Participante;

class CargarBeneficiariosConvenioUseCase {

    const CEDULA = 0;        
    
    public function ejecutar(Convenio $convenio, $archivo) {
        

        $reader = ReaderEntityFactory::createXLSXReader(); // O createReaderFromFile($file) para autodetectar el formato
        $reader->open($archivo);

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $row) {

                $registro = $row->toArray();

                $participante = Participante::buscarParticipantePorCedula($registro[self::CEDULA]);

                if (!$participante->existe()) {
                    $participante->setDocumento($registro[self::CEDULA]);
                }
                
                $convenio->agregarParticipante($participante);                            
            }
        }

        $reader->close();
    }
}