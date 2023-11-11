<?php

namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;

class ListarSalonesPorEstadoUseCase {

    public function ejecutar(bool $estado=true): array {
        $salonRepository = new SalonDao();
        return $salonRepository->listarSalonesPorEstado($estado);
    }
}