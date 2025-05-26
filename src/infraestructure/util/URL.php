<?php

namespace Src\infraestructure\util;


class URL
{
    // public static function ObtenerRutaRelativa($path)
    // {
    //     $ruta = parse_url($path, PHP_URL_PATH);

    //     $rutaRelativa = str_replace('storage/app/', '/storage/', $ruta);

    //     $rutaRelativa = str_replace('//', '/', $rutaRelativa);

    //     return $rutaRelativa; 
    // }

    public static function ObtenerRutaRelativa($path)
    {
        $ruta = parse_url($path, PHP_URL_PATH);

        $pos = strpos($ruta, '/storage/');
        if ($pos !== false) {
            // Cortamos desde /storage/
            $rutaRelativa = substr($ruta, $pos);

            // Eliminamos el /public/ si viene después de /storage/
            return str_replace('/storage/public/', '/storage/', $rutaRelativa);
        }

        return $ruta;
    }

}