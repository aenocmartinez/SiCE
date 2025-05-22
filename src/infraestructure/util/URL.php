<?php

namespace Src\infraestructure\util;


class URL
{
    public static function ObtenerRutaRelativa($path)
    {
        $ruta = parse_url($path, PHP_URL_PATH);

        $rutaRelativa = str_replace('storage/app/', '/storage/', $ruta);

        $rutaRelativa = str_replace('//', '/', $rutaRelativa);

        return $rutaRelativa; 
    }

}