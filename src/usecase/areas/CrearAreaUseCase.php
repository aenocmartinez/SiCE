<?php

namespace Src\usecase\areas;

use Src\domain\Area;
use Src\dao\mysql\AreaDao;

class CrearAreaUseCase {

    public function execute(string $nombre): string {

        $areaRepository = new AreaDao();        
        $area = Area::buscarPorNombre($nombre, $areaRepository);

        if ($area->existe()) {
            return "El área ya existe";
        }
        
        $area->setRepository($areaRepository);
        $area->setNombre($nombre);

        if (!$area->crear()) {
            return "Ha ocurrido un error en el sistema";
        }

        return "creando";
    }
}