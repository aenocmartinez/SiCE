<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;

class ListarCalendariosUseCase {

    public function ejecutar(): array {

        $calendarioRepository = new CalendarioDao();

        $calendarios = Calendario::listar($calendarioRepository);

        return $calendarios;
    }
}