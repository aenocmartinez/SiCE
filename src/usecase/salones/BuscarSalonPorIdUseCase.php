<?php

namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;

class BuscarSalonPorIdUseCase {

    public function ejecutar(int $id=0): Salon{
        return Salon::buscarPorId($id, new SalonDao());
    }
}