<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;

class BuscarCalendarioPorIdUseCase {

    public function ejecutar(int $id=0): Calendario {
        $calendarioRepository = new CalendarioDao();
        return Calendario::buscarPorId($id, $calendarioRepository);
    }
}