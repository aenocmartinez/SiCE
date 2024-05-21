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
        ];
    }

    public static function jornadas(): array {
        return [
            'Mañana',
            'Tarde',
            'Noche'
        ];
    }

    public static function estadoCivil(): array {
        return [
            [ 'value' => 'Soltero/a', 'nombre' => 'Soltero/a'],
            [ 'value' => 'Casado/a', 'nombre' => 'Casado/a'],
            [ 'value' => 'Unión libre o unión de hecho', 'nombre' => 'Unión libre o unión de hecho'],
            [ 'value' => 'Separado/a', 'nombre' => 'Separado/a'],
            [ 'value' => 'Viudo/a', 'nombre' => 'Viudo/a'],                                                
        ];        
    }

    public static function sexo(): array {
        return [
            [
                'value' => 'M',
                'nombre' => 'Masculino',
            ],
            [
                'value' => 'F',
                'nombre' => 'Femenino',
            ],
            [
                'value' => 'Otro',
                'nombre' => 'Otro',
            ]         
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
            "SERVISALUD",
            "DUSAKAWI EPSI",
            "ASOCIACION INDIGENA DEL CAUCA EPSI",
            "ANAS WAYUU EPSI",
            "MALLAMAS EPSI",
            "PIJAOS SALUD EPSI",
            "SALUD BÓLIVAR EPS SAS",
            "NO ESTÁ AFILIADO"
       ];       
    }

    public static function estadosFormularioInscripcion(): array {
        return [
            [ 'value' => 'Pendiente de pago', 'nombre' => 'Pendiente de pago'],
            [ 'value' => 'Revisar comprobante de pago', 'nombre' => 'Revisar comprobante de pago'],
            [ 'value' => 'Pagado', 'nombre' => 'Pagado'],
            [ 'value' => 'Anulado', 'nombre' => 'Anulado'],
        ];        
    }
}