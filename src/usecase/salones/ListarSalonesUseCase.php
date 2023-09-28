<?php

namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;

class ListarSalonesUseCase {
    public function ejecutar(): array {
        $salonRepository = new SalonDao();
        $salones = Salon::listarSalones($salonRepository);
        return [
            "code" => "200",
            "data" => $salones
        ];
    }
}