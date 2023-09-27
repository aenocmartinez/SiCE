<?php

namespace Src\usecase\areas;

use Src\domain\Area;
use Src\dao\mysql\AreaDao;

class ListarAreasUseCase {

    public function ejecutar(): array {        
        $areas = Area::listar(new AreaDao());
        return [
            "code" => "200",
            "data" => $areas,
        ];
    }
}