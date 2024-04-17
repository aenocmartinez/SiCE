<?php

namespace Src\usecase\dashboard;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\Calendario;

class BuscarFormulariosPorEstadoYCalendarioUseCase {

    public function ejecutar($estado, $calendarioId=0): array {

        if ($calendarioId == 0) {            
            $calendario = Calendario::Vigente();            
            if (!$calendario->existe()) {
                return [];
            }
            $calendarioId = $calendario->getId();
        }
        
        return (new FormularioInscripcionDao())->listarFormulariosPorEstadoYCalendario($estado, $calendarioId);
    }

}