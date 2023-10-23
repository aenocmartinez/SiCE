<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;

class ListarCursosPorCalendarioUseCase {
    public function ejecutar(int $calendarioId=0, int $areaId=0): array {

        $calendarioRepository = new CalendarioDao();

        $calendario = new Calendario();
        $calendario->setId($calendarioId);

        return $calendarioRepository->listarCursos($calendarioId, $areaId);
    }
}