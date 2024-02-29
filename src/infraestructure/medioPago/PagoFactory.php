<?php

namespace Src\infraestructure\medioPago;

class PagoFactory {

    public static function Medio(string $tipoMedio): IMedioPago {
        $mediosMap = self::cargarMedios();

        if (!isset($mediosMap[$tipoMedio])) {
            return $mediosMap['pagoDatafono'];    
        }

        return $mediosMap[$tipoMedio];

    }

    private static function cargarMedios(): array {
        $mediosMap = [];
        $mediosMap['pagoBanco'] = new PagoEnBanco;
        $mediosMap['pagoDatafono'] = new PagoDatafono;
        $mediosMap['pagoPSE'] = new PagoPSE;
        $mediosMap['pagoEcollect'] = new PagoEcollect;

        return $mediosMap;
    }
}