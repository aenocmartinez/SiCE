<?php
namespace Src\infraestructure\util;

class UUID {

    public static function generarUUIDNumerico() {
        
        $semilla = microtime(true) . mt_rand();        
        $hash = md5($semilla);        
        $uuidNumerico = substr(preg_replace('/[^0-9]/', '', $hash), 0, 13);
        
        while (strlen($uuidNumerico) < 13) {
            $uuidNumerico .= mt_rand(0, 9);
        }    
        return $uuidNumerico;
    }    
}