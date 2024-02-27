<?php

namespace Src\usecase\convenios;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Src\domain\Convenio;
use Src\domain\Participante;

class CargarBeneficiariosConvenioUseCase {

    public function ejecutar(Convenio $convenio, $archivo) {

        
        $reader = ReaderEntityFactory::createXLSXReader(); // O createReaderFromFile($file) para autodetectar el formato
        $reader->open($archivo);

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $row) {

                $cedula = $row->toArray();

                $participante = Participante::buscarParticipantePorCedula($cedula[0]);

                if (!$participante->existe()) {
                    continue;
                }
                
                $convenio->agregarParticipante($participante);                            
            }
        }

        $reader->close();
    }
}