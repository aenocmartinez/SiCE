<?php

namespace Src\infraestructure\util;

class ListaDeValor {

    public static function diasSemana(): array {
        return [
            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes',
            'Sábado',
            'Domingo'
        ];
    }

    public static function jornadas(): array {
        return [
            'Mañana',
            'Tarde',
            'Noche'
        ];
    }
}