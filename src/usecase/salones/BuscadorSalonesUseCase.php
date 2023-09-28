<?php

namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;

class BuscadorSalonesUseCase {

    public function ejecutar(string $criterio): array {
        
        $salonRepository = new SalonDao();
        
        $filtro = [
            "nombre" => $criterio,
            "capacidad" => $criterio,
        ];
        
        $salones = Salon::buscadorSalones($filtro, $salonRepository);
        return [
            "code" => "200",
            "data" => $salones
        ];
    }
}