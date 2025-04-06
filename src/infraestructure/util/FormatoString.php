<?php

namespace Src\infraestructure\util;

class FormatoString {

    public static function convertirACapitalCase(string $texto): string
    {
        $textoMinusculas = mb_strtolower($texto, 'UTF-8');
        return mb_convert_case($textoMinusculas, MB_CASE_TITLE, 'UTF-8');
    }
    
}