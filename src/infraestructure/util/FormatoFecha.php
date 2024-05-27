<?php

namespace Src\infraestructure\util;

use Carbon\Carbon;
use DateTime;

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
    
    public static function fecha01enero1970($fecha) {
        $fecha = Carbon::createFromFormat('Y-m-d', $fecha, 'UTC');
        $fechaFormateada = $fecha->isoFormat('DD [de] MMMM, YYYY', 'Do MMMM, YYYY');
        return $fechaFormateada;
    }

    public static function fechaDDdeMMdeYYYY($fecha) {
        $fecha = Carbon::createFromFormat('Y-m-d', $fecha, 'UTC');
        $fechaFormateada = $fecha->isoFormat('DD [de] MMMM [de] YYYY', 'Do MMMM, YYYY');
        return $fechaFormateada;
    }   
    
    public static function fechaTimestampFormateadaA_YMD($fecha) {
        return Carbon::createFromFormat('Y-m-d H:i:s', $fecha)->format('Y-m-d');
    }

    public static function fechaFormateadaA5DeAgostoDe2024($fecha) {
        $fecha = new DateTime($fecha);
        $formato_deseado = $fecha->format('j') . ' de ' . $fecha->format('F') . ' de ' . $fecha->format('Y');
        $meses_en_espanol = array(
            'January' => 'enero',
            'February' => 'febrero',
            'March' => 'marzo',
            'April' => 'abril',
            'May' => 'mayo',
            'June' => 'junio',
            'July' => 'julio',
            'August' => 'agosto',
            'September' => 'septiembre',
            'October' => 'octubre',
            'November' => 'noviembre',
            'December' => 'diciembre'
        );

        $nombre_mes_ingles = $fecha->format('F');
        $formato_deseado = str_replace($nombre_mes_ingles, $meses_en_espanol[$nombre_mes_ingles], $formato_deseado);

        return $formato_deseado;
    }
}