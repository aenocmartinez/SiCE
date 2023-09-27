<?php

namespace Src\usecase\areas;

use Src\dao\mysql\AreaDao;
use Src\domain\Area;
use Src\view\dto\AreaDto;

class ActualizarAreaUseCase {

    public function ejecutar(AreaDto $areaDto) {

        $areaRepository = new AreaDao();

        $area = Area::buscarPorId($areaDto->id, $areaRepository);
        if (!$area->existe()) {
            return [
                "code" => "404",
                "message" => "área no encontrada",
            ];
        }
        
        $area->setRepository($areaRepository);
        $area->setNombre($areaDto->nombre);
        $exito = $area->actualizar();

        if (!$exito) {
            return [
                "code" => "503",
                "message" => "ha ocurrido un error en el sistema",
            ];
        }

        return [
            "code" => "200",
            "message" => "el registro se ha actualizado con éxito",
        ];
    }
}