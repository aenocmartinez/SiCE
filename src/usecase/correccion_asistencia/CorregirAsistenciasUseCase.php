<?php

namespace Src\usecase\correccion_asistencia;

use Illuminate\Support\Facades\DB;
use Src\dao\mysql\GrupoDao;
use Src\dao\mysql\ParticipanteDao;
use Src\domain\AsistenciaClase;
use Src\usecase\participantes\ListarSesionesDeParticipanteEnGrupoUseCase;
use Src\view\dto\CorregirAsistenciasInput;
use Src\view\dto\CorregirAsistenciasOutput;

class CorregirAsistenciasUseCase
{
    /** @var GrupoDao */
    private $grupoDao;

    /** @var ParticipanteDao */
    private $participanteDao;

    /** @var ListarSesionesDeParticipanteEnGrupoUseCase */
    private $listarSesionesUc;

    public function __construct(
        GrupoDao $grupoDao,
        ParticipanteDao $participanteDao,
        ListarSesionesDeParticipanteEnGrupoUseCase $listarSesionesUc
    ) {
        $this->grupoDao = $grupoDao;
        $this->participanteDao = $participanteDao;
        $this->listarSesionesUc = $listarSesionesUc;
    }

    public function ejecutar(CorregirAsistenciasInput $in): CorregirAsistenciasOutput
    {
        $actual   = $this->listarSesionesUc->ejecutar($in->participanteId, $in->grupoId);
        $sesiones = is_array($actual['sesiones'] ?? null) ? $actual['sesiones'] : [];

        $idToNumero = [];  
        $prevMap    = [];  

        foreach ($sesiones as $s) {
            $sid = (int)($s['id'] ?? $s['sesion_id'] ?? 0);
            if ($sid <= 0) continue;

            $num = (int)($s['numero'] ?? $s['nro'] ?? $s['orden'] ?? 0);
            if ($num > 0) {
                $idToNumero[$sid] = $num;
            }

            $prevMap[$sid] = (int)($s['asistio'] ?? $s['asistencia'] ?? $s['presente'] ?? 0);
        }

        $cambios = [];
        foreach ($in->cambios as $chg) {
            $sid = (int)($chg['sesion_id'] ?? 0);
            if ($sid <= 0) continue;

            $cambios[$sid] = [
                'asistio' => (int)($chg['asistio'] ?? 0),
                'numero'  => (int)($chg['numero'] ?? 0),
            ];
        }

        $creados = 0;
        $actualizados = 0;

        DB::transaction(function () use ($in, $cambios, $idToNumero, $prevMap, &$creados, &$actualizados) {
            $participante = $this->participanteDao->buscarParticipantePorId($in->participanteId);

            foreach ($cambios as $sesionId => $chg) {
                $numeroSesion = (int)($idToNumero[$sesionId] ?? $chg['numero'] ?? 0);
                if ($numeroSesion <= 0) {
                    throw new \RuntimeException("No se pudo resolver el número de sesión para ID {$sesionId}");
                }

                $asistio = (int)$chg['asistio'];

                $ok = $this->grupoDao->guardarAsistenciaCorreccion(
                    $in->grupoId,
                    new AsistenciaClase($participante, $numeroSesion, $asistio)
                );

                if (!$ok) {
                    throw new \RuntimeException("Error guardando asistencia (sesión {$numeroSesion})");
                }

                if (!array_key_exists($sesionId, $prevMap)) {
                    $creados++;
                } else {
                    $actualizados++;
                }
            }
        });

        $final = $this->listarSesionesUc->ejecutar($in->participanteId, $in->grupoId);

        return new CorregirAsistenciasOutput(
            ['creados' => $creados, 'actualizados' => $actualizados],
            $final
        );
    }
}
