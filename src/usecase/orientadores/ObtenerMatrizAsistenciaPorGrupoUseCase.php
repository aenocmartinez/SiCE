<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\GrupoDao;
use Src\view\dto\Response;

class ObtenerMatrizAsistenciaPorGrupoUseCase
{
    public function ejecutar(int $grupoId): Response
    {
        $grupoDao = new GrupoDao();

        $meta = $grupoDao->obtenerMetaDeGrupo($grupoId);
        if (!$meta) {
            return new Response("404", "Grupo no encontrado.");
        }

        // Sesiones registradas (num, fecha)
        $sesiones = $grupoDao->listarSesionesDeGrupo($grupoId); // [{num, fecha}]

        // Últimas marcas por (sesión, participante) + convenio del formulario del mismo grupo
        $rows = $grupoDao->obtenerUltimasAsistenciasParaMatriz($grupoId);

        // Armar matriz en memoria
        $partIndex = [];           // doc => idx
        $participantes = [];
        $fechaPorSesion = [];      // num => 'Y-m-d'

        foreach ($rows as $r) {
            $nombre = trim(implode(' ', array_filter([
                $r['primer_nombre'] ?? null,
                $r['segundo_nombre'] ?? null,
                $r['primer_apellido'] ?? null,
                $r['segundo_apellido'] ?? null,
            ])));

            $doc = $r['doc'];
            if (!isset($partIndex[$doc])) {
                $partIndex[$doc] = count($participantes);
                $participantes[] = [
                    'nombre'   => $nombre,
                    'doc'      => $doc,
                    'convenio' => $r['convenio'] ?? '',
                    'sesiones' => [], // num => bool
                ];
            }
            $idx = $partIndex[$doc];
            $numSesion = (int) $r['sesion_num'];
            $participantes[$idx]['sesiones'][$numSesion] = (bool) $r['presente'];

            if (!isset($fechaPorSesion[$numSesion]) && !empty($r['fecha_registro'])) {
                $fechaPorSesion[$numSesion] = $r['fecha_registro'];
            }
        }

        // Completar fechas faltantes en sesiones
        $sesionesMap = [];
        foreach ($sesiones as $s) {
            $sesionesMap[(int)$s['num']] = $s['fecha'] ?? null;
        }
        foreach ($fechaPorSesion as $num => $fecha) {
            if (empty($sesionesMap[$num])) $sesionesMap[$num] = $fecha;
        }
        $sesionesOrdenadas = [];
        foreach ($sesionesMap as $num => $fecha) {
            $sesionesOrdenadas[] = ['num' => (int)$num, 'fecha' => $fecha];
        }
        usort($sesionesOrdenadas, fn($a,$b) => $a['num'] <=> $b['num']);

        return new Response(200, "OK", [
            'meta'          => $meta,
            'sesiones'      => $sesionesOrdenadas,
            'participantes' => $participantes,
        ]);
    }
}
