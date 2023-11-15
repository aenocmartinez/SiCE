<?php

namespace Src\infraestructure\medioPago;

use Src\domain\Grupo;
use Src\domain\Participante;

class PagoPSE implements IMedioPago {

    public function realizarPago(Participante $participante, Grupo $grupo, $totalAPagar): bool {
        return false;
    }
}