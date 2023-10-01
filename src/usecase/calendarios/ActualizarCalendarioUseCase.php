<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;
use Src\view\dto\CalendarioDto;

class ActualizarCalendarioUseCase {

    public function ejecutar(CalendarioDto $calendarioDto): bool {

        $calendarioRepository = new CalendarioDao();
        
        $calendario = Calendario::buscarPorId($calendarioDto->id, $calendarioRepository);

        if (!$calendario->existe()) {
            return false;
        }

        $calendario->setRepository($calendarioRepository);

        $calendario->setNombre($calendarioDto->nombre);
        $calendario->setFechaInicio($calendarioDto->fechaInicial);
        $calendario->setFechaFinal($calendarioDto->fechaFinal);

        return $calendario->actualizar();
    }
}