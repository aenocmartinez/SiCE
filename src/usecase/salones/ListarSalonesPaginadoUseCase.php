<?php

namespace Src\usecase\salones;

use Src\domain\Salon;

class ListarSalonesPaginadoUseCase {

    public function ejecutar($page=1) {
        return Salon::paginar($page);
    }
}