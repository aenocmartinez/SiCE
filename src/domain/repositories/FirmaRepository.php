<?php

namespace Src\domain\repositories;

use Src\domain\Firma;

interface FirmaRepository {
    public static function ObtenerFirmas(): Firma;
    public function GuardarFirma(Firma $firma): bool;
}