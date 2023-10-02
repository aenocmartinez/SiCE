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

    public static function eps(): array {
       return [
            "COOSALUD EPS-S",
            "NUEVA EPS",
            "MUTUAL SER",
            "ALIANSALUD EPS",
            "SALUD TOTAL EPS S.A.",
            "EPS SANITAS",
            "EPS SURA",
            "FAMISANAR",
            "SERVICIO OCCIDENTAL DE SALUD EPS SOS",
            "SALUD MIA",
            "COMFENALCO VALLE",
            "COMPENSAR EPS",
            "EPM - EMPRESAS PUBLICAS DE MEDELLIN",
            "FONDO DE PASIVO SOCIAL DE FERROCARRILES NACIONALES DE COLOMBIA",
            "CAJACOPI ATLANTICO",
            "CAPRESOCA",
            "COMFACHOCO",
            "COMFAORIENTE",
            "EPS FAMILIAR DE COLOMBIA",
            "ASMET SALUD",
            "EMSSANAR E.S.S.",
            "CAPITAL SALUD EPS-S",
            "SAVIA SALUD EPS",
            "DUSAKAWI EPSI",
            "ASOCIACION INDIGENA DEL CAUCA EPSI",
            "ANAS WAYUU EPSI",
            "MALLAMAS EPSI",
            "PIJAOS SALUD EPSI",
            "SALUD BÓLIVAR EPS SAS"
       ];       
    }
}