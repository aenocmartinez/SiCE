<?php

namespace Src\usecase\tipo_salones;

use Src\dao\mysql\TipoSalonDao;
use Src\domain\TipoSalon;

class ListarTipoSalonesUseCase {
    public function ejecutar(): array {
        $salonRepository = new TipoSalonDao();
        return TipoSalon::listar($salonRepository);
    }
}