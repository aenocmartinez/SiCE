<?php

namespace Src\usecase\areas;

use Src\dao\mysql\AreaDao;
use Src\domain\Area;

class ListarOrientadoresPorCursoCalendarioUseCase {

    public function ejecutar(int $areaId=0): array {

        $areaRepository = new AreaDao();
        $area = new Area();
        $area->setId($areaId);
        $area->setRepository($areaRepository);

        return $area->listarOrientadores();
    }
}