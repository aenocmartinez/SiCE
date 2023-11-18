<?php

namespace Src\infraestructure\util;

class FormatoMoneda {

    public static function PesosColombianos($valor): string {
        return '$' . number_format($valor, 0, ',', '.') . ' COP';
    }
}