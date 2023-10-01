<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;

class EliminarCalendarioUseCase {

    public function ejecutar(int $id=0): bool {

        $calendarioReposity = new CalendarioDao();

        $calendario = Calendario::buscarPorId($id, $calendarioReposity);
        if (!$calendario->existe())
            return false;

        $calendario->setRepository($calendarioReposity);
        return $calendario->eliminar();
    }
}