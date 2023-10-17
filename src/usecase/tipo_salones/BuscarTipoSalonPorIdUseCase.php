<?php

namespace Src\usecase\tipo_salones;

use Src\dao\mysql\TipoSalonDao;
use Src\domain\TipoSalon;

class BuscarTipoSalonPorIdUseCase {

    public function ejecutar(int $id=0): TipoSalon{
        return TipoSalon::buscarPorId($id, new TipoSalonDao());
    }
}