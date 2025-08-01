<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Src\domain\repositories\EstadisticasRepository;
use Src\view\dto\ResumenParticipantesNuevosAntiguosDTO;

class ResumenParticipantesNuevosAntiguosDao extends Model implements EstadisticasRepository
{
    protected $table = 'resumen_participantes_calendario';

    public function actualizarResumenParticipantesNuevosYAntiguos(int $calendarioID = 0): bool
    {
        try {
            $sql = "
                INSERT INTO resumen_participantes_calendario (calendario_id, total_participantes, nuevos, antiguos)
                SELECT
                    ? AS calendario_id,
                    COUNT(*) AS total_participantes,
                    SUM(CASE WHEN a.participante_id IS NULL THEN 1 ELSE 0 END) AS nuevos,
                    SUM(CASE WHEN a.participante_id IS NOT NULL THEN 1 ELSE 0 END) AS antiguos
                FROM (
                    SELECT fi.participante_id
                    FROM formulario_inscripcion fi
                    JOIN grupos g ON fi.grupo_id = g.id
                    WHERE g.calendario_id = ?
                    AND fi.estado = 'Pagado'
                ) ia
                LEFT JOIN (
                    SELECT DISTINCT fi.participante_id
                    FROM formulario_inscripcion fi
                    JOIN grupos g ON fi.grupo_id = g.id
                    WHERE g.calendario_id < ?
                    AND fi.estado = 'Pagado'
                ) a ON ia.participante_id = a.participante_id
                ON DUPLICATE KEY UPDATE
                    total_participantes = VALUES(total_participantes),
                    nuevos = VALUES(nuevos),
                    antiguos = VALUES(antiguos),
                    actualizado_en = CURRENT_TIMESTAMP
            ";

            DB::insert(DB::raw($sql), [$calendarioID, $calendarioID, $calendarioID]);

            return true;
        } catch (\Throwable $e) {
            dd($e->getMessage());
            // Puedes descomentar esta línea para monitoreo si usas Sentry
            // \Sentry\captureException($e);
            return false;
        }
    }


    public function buscarResumenParticipantesPorCalendario(int $calendarioID): ResumenParticipantesNuevosAntiguosDTO
    {
        $data = DB::table('resumen_participantes_calendario')
            ->select('total_participantes', 'nuevos', 'antiguos')
            ->where('calendario_id', $calendarioID)
            ->first();

        if (!$data) {
            // Si no hay datos, devolvemos un DTO con ceros
            return new ResumenParticipantesNuevosAntiguosDTO(0, 0, 0);
        }

        return new ResumenParticipantesNuevosAntiguosDTO(
            $data->total_participantes,
            $data->nuevos,
            $data->antiguos
        );
    }

}
