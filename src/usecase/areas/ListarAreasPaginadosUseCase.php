<?php

namespace Src\usecase\areas;

use Src\domain\Area;

class ListarAreasPaginadosUseCase {

    public function ejecutar($page=1) {
        
        return Area::Paginar($page);
    }
}