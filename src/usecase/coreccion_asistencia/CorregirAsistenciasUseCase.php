<?php

namespace Src\usecase\asistcoreccion_asistenciaencias;

use Illuminate\Support\Facades\DB;
use Src\usecase\asistencias\dto\CorregirAsistenciasInput;
use Src\dao\mysql\ParticipanteDao;

class CorregirAsistenciasUseCase
{
    public function __construct(private ParticipanteDao $participanteDao) {}

    public function ejecutar(CorregirAsistenciasInput $in): array
    {
        $marcadas = 0; $desmarcadas = 0;

        DB::transaction(function () use ($in, &$marcadas, &$desmarcadas) {
            // Marcar (crear/activar)
            foreach ($in->marcar as $sid) {
                // Debe ser idempotente: unique(participante_id, sesion_id)
                $this->participanteDao->registrarAsistenciaAClase($in->participanteId, $sid);
                $marcadas++;
            }

            // Desmarcar (eliminar/anular)
            foreach ($in->desmarcar as $sid) {
                if (method_exists($this->participanteDao, 'eliminarAsistenciaAClase')) {
                    $this->participanteDao->eliminarAsistenciaAClase($in->participanteId, $sid);
                } else {
                    $this->participanteDao->anularAsistenciaAClase($in->participanteId, $sid);
                }
                $desmarcadas++;
            }

            // Bitácora opcional
            if (method_exists($this->participanteDao, 'registrarBitacoraCorreccionAsistencia')) {
                $this->participanteDao->registrarBitacoraCorreccionAsistencia([
                    'participante_id' => $in->participanteId,
                    'grupo_id'        => $in->grupoId,
                    'marcar'          => $in->marcar,
                    'desmarcar'       => $in->desmarcar,
                    'observacion'     => $in->observacion,
                    'actor_id'        => $in->actorId,
                    'actor_nombre'    => $in->actorNombre,
                    'actor_ip'        => $in->actorIp,
                    'actor_ua'        => $in->actorUserAgent,
                    'ts'              => now()->toDateTimeString(),
                ]);
            }
        });

        return [
            'marcadas'    => $marcadas,
            'desmarcadas' => $desmarcadas,
        ];
    }
}
