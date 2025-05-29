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

        $rutaRelativa = str_replace(['/storage/app/public/', '/storage/public/'], '/storage/', $ruta);

        $rutaRelativa = str_replace('/app/public/', '/storage/', $rutaRelativa);

        $rutaRelativa = preg_replace('#/+#', '/', $rutaRelativa);

        return $rutaRelativa;
    }

}