<?php

namespace Src\usecase\areas;

use Src\domain\Area;
use Src\dao\mysql\AreaDao;

class BuscarAreaPorIdUseCase {

    public function ejecutar(int $id): Area {
        $areaRepository = new AreaDao();
        return Area::buscarPorId($id, $areaRepository);
    }
}