<?php

namespace Src\infraestructure\medioPago;

use Src\domain\Grupo;
use Src\domain\Participante;

class PagoEnBanco implements IMedioPago{

    public function realizarPago(Participante $participante, Grupo $grupo, $totalAPagar): bool {
        

        dd("Participante: " . $participante->getNombreCompleto() . " - " . $grupo->getJornada());

        return false;
    }
}