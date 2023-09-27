<?php

namespace Src\usecase\areas;

use Src\domain\Area;
use Src\dao\mysql\AreaDao;

class CrearAreaUseCase {

    public function ejecutar(string $nombre): array {
        $resp = array();

        $areaRepository = new AreaDao();        
        $area = Area::buscarPorNombre($nombre, $areaRepository);

        if ($area->existe()) {
            return [
                "code" => "200",
                "message" => "el área ya existe"
            ];
        }
        
        $area->setRepository($areaRepository);
        $area->setNombre($nombre);

        $resultado = $area->crear();

        if (!$resultado) {
            return [
                "code" => "500",
                "message" => "ha ocurrido un error en el sistema"
            ];
        }

        return [
            "code" => "201",
            "message" => "registro creado con éxito"
        ];
    }
}