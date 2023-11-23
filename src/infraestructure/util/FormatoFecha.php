<?php

namespace Src\infraestructure\util;

use Carbon\Carbon;

class FormatoFecha {

    public static function personalizado($fecha) {        
        $fechaFormateada = Carbon::parse($fecha);

        return $fechaFormateada->diffForHumans();
    }

    public static function fechaActual01enero1970() {
        date_default_timezone_set('America/Bogota');
        $fecha = Carbon::now();
        $fechaFormateada = $fecha->isoFormat('DD [de] MMMM, YYYY', 'Do MMMM, YYYY');
        return $fechaFormateada;
    }

    public static function horaActual1030AM() {
        date_default_timezone_set('America/Bogota');
        $hora = Carbon::now();
        $horaFormateada = $hora->format('h:i A');    
        return $horaFormateada;
    }    
}