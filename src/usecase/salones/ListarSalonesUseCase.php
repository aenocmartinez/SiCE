<?php

namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;

class ListarSalonesUseCase {
    public function ejecutar(): array {
        $salonRepository = new SalonDao();
        return Salon::listarSalones($salonRepository);
    }
}