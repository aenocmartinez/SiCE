<?php

namespace Src\infraestructure\util;

use Src\dao\mysql\EpsDao;
use Src\usecase\eps\ListarEpsUseCase;

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

        $listarEps = new ListarEpsUseCase(new EpsDao());

        return $listarEps->ejecutar();

    //    return [
    //         "COOSALUD EPS-S",
    //         "NUEVA EPS",
    //         "MUTUAL SER",
    //         "ALIANSALUD EPS",
    //         "SALUD TOTAL EPS S.A.",
    //         "EPS SANITAS",
    //         "EPS SURA",
    //         "FAMISANAR",
    //         "SERVICIO OCCIDENTAL DE SALUD EPS SOS",
    //         "SALUD MIA",
    //         "COMFENALCO VALLE",
    //         "COMPENSAR EPS",
    //         "EPM - EMPRESAS PUBLICAS DE MEDELLIN",
    //         "FONDO DE PASIVO SOCIAL DE FERROCARRILES NACIONALES DE COLOMBIA",
    //         "CAJACOPI ATLANTICO",
    //         "CAPRESOCA",
    //         "COMFACHOCO",
    //         "COMFAORIENTE",
    //         "EPS FAMILIAR DE COLOMBIA",
    //         "ASMET SALUD",
    //         "EMSSANAR E.S.S.",
    //         "CAPITAL SALUD EPS-S",
    //         "SANIDAD DEL EJÉRCITO",
    //         "SAVIA SALUD EPS",
    //         "SERVISALUD",
    //         "DUSAKAWI EPSI",
    //         "ASOCIACION INDIGENA DEL CAUCA EPSI",
    //         "ANAS WAYUU EPSI",
    //         "MALLAMAS EPSI",
    //         "PIJAOS SALUD EPSI",
    //         "SALUD BÓLIVAR EPS SAS",
    //         "UNISALUD",
    //         "NO ESTÁ AFILIADO"
    //    ];       
    }

    public static function estadosFormularioInscripcion(): array {
        return [
            [ 'value' => 'Pendiente de pago', 'nombre' => 'Pendiente de pago'],
            [ 'value' => 'Revisar comprobante de pago', 'nombre' => 'Revisar comprobante de pago'],
            [ 'value' => 'Pagado', 'nombre' => 'Pagado'],
            [ 'value' => 'Anulado', 'nombre' => 'Anulado'],
            [ 'value' => 'Aplazado', 'nombre' => 'Aplazado'],
            [ 'value' => 'Devuelto', 'nombre' => 'Devuelto'],
        ];        
    }

    public static function tipoDocumentos(): array {
        return [
            [ 'value' => 'CC', 'nombre' => 'Cédula'],
            [ 'value' => 'TI', 'nombre' => 'Tarjeta de identidad'],
            [ 'value' => 'CE', 'nombre' => 'Cédula de extranjería'],
            [ 'value' => 'PP', 'nombre' => 'Pasaporte'],
        ];        
    }

    public static function obtenerNombreTipoDocumentoPorCodigo(string $value): string {
        $tiposDocumento = [
            'CC' => 'Cédula de Ciudadanía',
            'TI' => 'Tarjeta de identidad',
            'CE' => 'Cédula de extranjería',
            'PP' => 'Pasaporte',
        ];
    
        return $tiposDocumento[$value] ?? '';
    }
    

    public static function motivosCambiosYTraslados(): array {
        return [
            [ 'value' => 'cambio', 'nombre' => 'Cambiar de curso o grupo'],
            // [ 'value' => 'traslado', 'nombre' => 'Trasladar'],
            [ 'value' => 'aplazamiento', 'nombre' => 'Aplazar inscripción'],
            [ 'value' => 'devolucion', 'nombre' => 'Devolución'],
        ];
    }

    public static function tagMotivoCambioYTraslado($opcion='cambio'): string {
        $motivos = [
            'cambio' => 'Cambio de curso',
            // 'traslado' => 'Trasladar',
            'aplazamiento' => 'Aplazar inscripción',
            'devolucion' => 'Devolución',
        ];

        return $motivos[$opcion];
    }

    public static function origenDevoluciones(): array {
        return [
            [ 'value' => 'UnicolMayor', 'nombre' => 'Universidad Colegio Mayor de Cundinamarca'],
            [ 'value' => 'Participante', 'nombre' => 'Participante'],
        ];
    }

    public static function roles(): array {
        return [
            [ 'value' => 'Admin', 'nombre' => 'Administrador'],
            [ 'value' => 'superAdmin', 'nombre' => 'Súper administrador'],
            [ 'value' => 'orientador', 'nombre' => 'Orientador'],
            [ 'value' => 'participante', 'nombre' => 'Participante'],
            [ 'value' => 'financiera', 'nombre' => 'Financiera'],                                                
        ];        
    }    
}