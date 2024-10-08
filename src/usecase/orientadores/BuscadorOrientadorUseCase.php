<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;

class BuscadorOrientadorUseCase {

    public function ejecutar(string $criterio, $page=1) {          
        
        return Orientador::buscador($criterio, $page);
    }
}