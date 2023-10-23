<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\CalendarioDao;

class ListarCursosPorCalendarioUseCase {

    public function ejecutar(int $calendarioId=0): array {

        $calendarioRepository = new CalendarioDao();
        return $calendarioRepository->listarCursosPorCalendario($calendarioId);
    }
}