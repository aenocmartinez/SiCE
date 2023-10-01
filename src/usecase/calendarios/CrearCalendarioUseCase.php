<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;
use Src\view\dto\CalendarioDto;

class CrearCalendarioUseCase {

    public function ejecutar(CalendarioDto $calendarioDto): bool {

        $calendarioRepository = new CalendarioDao();
        $calendario = Calendario::buscarPorNombre($calendarioDto->nombre, $calendarioRepository);

        if ($calendario->existe()) {
            return false;
        }

        $calendario->setNombre($calendarioDto->nombre);
        $calendario->setFechaInicio($calendarioDto->fechaInicial);
        $calendario->setFechaFinal($calendarioDto->fechaFinal);
        $calendario->setRepository($calendarioRepository);

        return $calendario->crear();        
    }
}