<?php

namespace Src\infraestructure\util;

class Validador {

    public static function esEntero($valor): bool {
        $limiteMinimo = -2147483648;
        $limiteMaximo = 2147483647;
        
        return ($valor >= $limiteMinimo && $valor <= $limiteMaximo);
    }

    public static function parametroId($id): bool {
        if (strlen($id) == 0)
            return false;

        if (!is_numeric($id)) 
            return false;
        
        if (!Validador::esEntero($id)) 
            return false;    
        
        return true;
    }

    public static function convertirAEnteroDesdeMoneda($texto) {                
        $numeroLimpio = preg_replace('/[^\d]/', '', $texto);
        return (int)$numeroLimpio;
    }
}