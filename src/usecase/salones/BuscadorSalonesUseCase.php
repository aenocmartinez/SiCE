<?php

namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;

class BuscadorSalonesUseCase {

    public function ejecutar(string $criterio): array {                  
        return Salon::buscadorSalones($criterio, new SalonDao());
    }
}