<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;

class ListarParticipantesPorCalendarioUseCase {
    
    public function ejecutar(int $calendarioId=0): array {

        return CalendarioDao::listadoParticipantesPorCalendario($calendarioId);
    }
}