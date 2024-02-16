<?php

namespace Src\usecase\salones;

use Src\domain\Salon;

class BuscadorSalonesUseCase {

    public function ejecutar(string $criterio, $page=1) {                  
        return Salon::buscadorSalones($criterio, $page);
    }
}