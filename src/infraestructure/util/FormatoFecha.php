<?php

namespace Src\infraestructure\util;

use Carbon\Carbon;

class FormatoFecha {

    public static function personalizado($fecha) {        
        $fechaFormateada = Carbon::parse($fecha);

        return $fechaFormateada->diffForHumans();
    }
}