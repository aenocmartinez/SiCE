<?php

namespace Src\usecase\tipo_salones;

use Src\domain\TipoSalon;

class ListarTipoSalonesPaginadoUseCase {
    
    public function ejecutar($page=1) {

        return TipoSalon::Paginar($page);
    }
}