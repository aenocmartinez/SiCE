<?php

namespace Src\usecase\areas;

use Src\domain\Area;
use Src\dao\mysql\AreaDao;
use Src\view\dto\AreaDto;

class BuscarAreaPorIdUseCase {

    public function ejecutar(int $id): array {
        $resp = [];
        $areaRepository = new AreaDao();
        $area = Area::buscarPorId($id, $areaRepository);
        if (!$area->existe()) {
            $resp["code"] = "404";
            $resp["message"] = "área no encontrada";
            return $resp;
        }

        $resp["code"] = "200";
        $resp["data"] = [
            "id" => $area->getId(),
            "nombre" => $area->getNombre(),
        ];

        return $resp;
    }
}