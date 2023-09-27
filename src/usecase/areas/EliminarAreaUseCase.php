<?php

namespace Src\usecase\areas;

use Src\dao\mysql\AreaDao;
use Src\domain\Area;

class EliminarAreaUseCase {

    public function ejecutar(int $id): array {

        $areaRepository = new AreaDao();
        $area = Area::buscarPorId($id, $areaRepository);
        if (!$area->existe()) {
            return [
                "code" => "200",
                "message" => "área no encontrada",
            ];
        }

        $area->setRepository($areaRepository);
        $exito = $area->eliminar();
        if (!$exito) {
            return [
                "code" => "500",
                "message" => "ha ocurrido un error en el sistema",
            ];
        }

        return [
            "code" => "200",
            "message" => "registro eliminado con éxito",
        ];
    }
}