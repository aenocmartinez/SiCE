<?php

namespace Src\usecase\areas;

use Src\domain\Area;
use Src\dao\mysql\AreaDao;

class ListarAreasUseCase {

    public function execute(): array {
    
        return Area::listar(new AreaDao());
    }
}