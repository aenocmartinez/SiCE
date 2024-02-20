<?php

namespace Src\usecase\calendarios;

use Src\domain\Calendario;

class BuscarCalendarioPorIdUseCase {

    public function ejecutar(int $id=0): Calendario {        
        return Calendario::buscarPorId($id);
    }
}