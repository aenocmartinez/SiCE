<?php

namespace App\Support;

use Carbon\Carbon;
use Src\domain\Participante;

class BancoPreguntasIdentidad
{
    /**
     * Retorna una lista de preguntas posibles.
     * Cada pregunta tiene:
     * - texto: lo que se muestra
     * - campos: qué campos del modelo compara
     * - tipo: atomizada | parcial | simple | compuesta | lógica
     * - extra: especifica cómo atomizar o qué tipo de ocultamiento hacer
     */
    public static function todas(): array
    {
        return [
            [
                'texto' => '¿En qué año nació?',
                'campos' => ['fecha_nacimiento'],
                'tipo' => 'atomizada',
                'extra' => 'year',
            ],
            [
                'texto' => '¿En qué mes nació? (número del mes)',
                'campos' => ['fecha_nacimiento'],
                'tipo' => 'atomizada',
                'extra' => 'month',
            ],
            // [
            //     'texto' => '¿Cuál es su EPS?',
            //     'campos' => ['eps'],
            //     'tipo' => 'simple',
            // ],
            [
                'texto' => 'Complete su número de teléfono: 320 *** **89',
                'campos' => ['telefono'],
                'tipo' => 'oculto_telefono',
            ],
            [
                'texto' => 'Complete su correo: ***@dominio',
                'campos' => ['email'],
                'tipo' => 'oculto_correo',
            ],
            // [
            //     'texto' => '¿Cuál fue el año de su último curso aprobado?',
            //     'campos' => ['ultimo_curso_aprobado'],
            //     'tipo' => 'simple',
            // ],
            [
                'texto' => '¿Cuál es el nombre de su contacto de emergencia?',
                'campos' => ['contacto_emergencia'],
                'tipo' => 'simple',
            ],
            [
                'texto' => '¿Cuál es el número de teléfono de su contacto de emergencia?',
                'campos' => ['telefono_contacto_emergencia'],
                'tipo' => 'simple',
            ],
            [
                'texto' => '¿Cuál es su primer nombre y primer apellido?',
                'campos' => ['primer_nombre', 'primer_apellido'],
                'tipo' => 'compuesta',
            ],
            // [
            //     'texto' => '¿Cuál es su dirección de residencia?',
            //     'campos' => ['direccion'],
            //     'tipo' => 'simple',
            // ],
        ];
    }    

    public static function generarAleatorias(int $cantidad = 3): array
    {
        $todas = self::todas();
        shuffle($todas);
        return array_slice($todas, 0, $cantidad);
    }

    // public static function respuestaEsperada(array $pregunta, Participante $participante): string
    // {
    //     $tipo = $pregunta['tipo'];
    //     $extra = $pregunta['extra'] ?? null;

    //     $map = [
    //         'primer_nombre' => 'getPrimerNombre',
    //         'segundo_nombre' => 'getSegundoNombre',
    //         'primer_apellido' => 'getPrimerApellido',
    //         'segundo_apellido' => 'getSegundoApellido',
    //         'fecha_nacimiento' => 'getFechaNacimiento',
    //         'tipo_documento' => 'getTipoDocumento',
    //         'documento' => 'getDocumento',
    //         'direccion' => 'getDireccion',
    //         'telefono' => 'getTelefono',
    //         'email' => 'getEmail',
    //         'eps' => 'getEps',
    //         'estado_civil' => 'getEstadoCivil',
    //         'sexo' => 'getSexo',
    //         'contacto_emergencia' => 'getContactoEmergencia',
    //         'telefono_contacto_emergencia' => 'getTelefonoEmergencia',
    //         'ultimo_curso_aprobado' => 'getFormularioInscripcion' 
    //     ];
    
    //     // Extraer valores reales usando getters
    //     $valores = collect($pregunta['campos'])->map(function ($campo) use ($map, $participante) {
    //         if (!isset($map[$campo])) {
    //             throw new \Exception("Getter no definido para el campo '$campo'");
    //         }
    
    //         $getter = $map[$campo];
    //         $valor = $participante->$getter();
    
    //         // Si el campo es especial, como 'ultimo_curso_aprobado', lo tratamos aparte
    //         if ($campo === 'ultimo_curso_aprobado') {
    //             try {
    //                 $valor = $participante->cursosAprobados();
    //                 if (empty($valor)) {
    //                     return '';
    //                 }
    
    //                 // Obtener el año del último curso aprobado
    //                 $ultimo = collect($valor)->last();
    //                 return date('Y', strtotime($ultimo->getFechaFin()));
    //             } catch (\Throwable $e) {
    //                 return '';
    //             }
    //         }
    
    //         return strtolower(trim($valor));
    //     });
    
    //     return match ($tipo) {
    //         'atomizada' => match ($extra) {
    //             'year' => date('Y', strtotime($valores[0])),
    //             'month' => date('m', strtotime($valores[0])),
    //             'day' => date('d', strtotime($valores[0])),
    //             default => $valores[0],
    //         },
    //         'parcial' => match ($extra) {
    //             'ultimos_3' => substr($valores[0], -3),
    //             'primeros_3' => substr($valores[0], 0, 3),
    //             default => $valores[0],
    //         },
    //         'compuesta' => $valores->implode(' '),
    //         'oculto_correo',
    //         'oculto_direccion',
    //         'oculto_telefono',
    //         'oculto_anio_nacimiento',
    //         'simple' => $valores[0],
    //         default => $valores[0],
    //     };
    // }    

   public static function respuestaEsperada(array $pregunta, Participante $participante): string
    {
        $tipo = $pregunta['tipo'];
        $extra = $pregunta['extra'] ?? null;

        $map = [
            'primer_nombre' => 'getPrimerNombre',
            'segundo_nombre' => 'getSegundoNombre',
            'primer_apellido' => 'getPrimerApellido',
            'segundo_apellido' => 'getSegundoApellido',
            'fecha_nacimiento' => 'getFechaNacimiento',
            'tipo_documento' => 'getTipoDocumento',
            'documento' => 'getDocumento',
            'direccion' => 'getDireccion',
            'telefono' => 'getTelefono',
            'email' => 'getEmail',
            'eps' => 'getEps',
            'estado_civil' => 'getEstadoCivil',
            'sexo' => 'getSexo',
            'contacto_emergencia' => 'getContactoEmergencia',
            'telefono_contacto_emergencia' => 'getTelefonoEmergencia',
            'ultimo_curso_aprobado' => 'getFormularioInscripcion',
        ];

        // Extraer valores reales usando getters
        $valores = collect($pregunta['campos'])->map(function ($campo) use ($map, $participante) {
            if (!isset($map[$campo])) {
                throw new \Exception("Getter no definido para el campo '$campo'");
            }

            $getter = $map[$campo];
            $valor = $participante->$getter();

            if ($campo === 'ultimo_curso_aprobado') {
                try {
                    $valor = $participante->cursosAprobados();
                    if (empty($valor)) {
                        return '';
                    }
                    $ultimo = collect($valor)->last();
                    return date('Y', strtotime($ultimo->getFechaFin()));
                } catch (\Throwable $e) {
                    return '';
                }
            }

            return strtolower(trim($valor));
        });

        return match ($tipo) {
            'atomizada' => match ($extra) {
                'year' => date('Y', strtotime($valores[0])),
                'month' => date('m', strtotime($valores[0])),
                'day' => date('d', strtotime($valores[0])),
                default => $valores[0],
            },
            'parcial' => match ($extra) {
                'ultimos_3' => substr($valores[0], -3),
                'primeros_3' => substr($valores[0], 0, 3),
                default => $valores[0],
            },
            'compuesta' => $valores->implode(' '),
            'oculto_correo' => $valores[0],
            'oculto_direccion' => $valores[0],
            'oculto_telefono' => $valores[0],
            'oculto_anio_nacimiento' => $valores[0],
            'simple' => $valores[0],
            default => $valores[0],
        };
    }

    public static function personalizarTexto(array $pregunta, Participante $participante): string
    {
        $texto = $pregunta['texto'];
        $tipo = $pregunta['tipo'];
    
        switch ($tipo) {
            case 'oculto_correo':
                $correo = $participante->getEmail();
                $dominio = substr(strrchr($correo, "@"), 1);
                return str_replace('***@dominio', "***@{$dominio}", $texto);
    
            case 'oculto_direccion':
                $direccion = $participante->getDireccion() ?? '';
                $visible = substr($direccion, 0, 10);
                $oculto = str_repeat('*', max(5, strlen($direccion) - 10));
                return "Complete su dirección: {$visible}{$oculto}";
    
            case 'oculto_telefono':
                $tel = preg_replace('/\D+/', '', $participante->getTelefono() ?? '');
                if (strlen($tel) >= 7) {
                    return 'Complete su número de teléfono: ' .
                        substr($tel, 0, 3) . ' *** **' . substr($tel, -2);
                } else {
                    return 'Complete su número de teléfono: ***';
                }
    
            case 'oculto_anio_nacimiento':
                $fecha = $participante->getFechaNacimiento() ?? '';
                $anio = date('Y', strtotime($fecha));
                return 'Complete su año de nacimiento: ' . substr($anio, 0, 2) . '**';
    
            default:
                return $texto;
        }
    }
    

}
