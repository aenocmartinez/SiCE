<?php

namespace Src\dao\mysql;

use Illuminate\Support\Facades\DB;
use Src\domain\repositories\CertificadoGeneradoRepository;

class CertificadoGeneradoDao implements CertificadoGeneradoRepository
{
    public function registrar(string $uuid, int $participanteID, int $grupoID): bool
    {
        return DB::table('certificados_generados')->insert([
            'uuid' => $uuid,
            'participante_id' => $participanteID,
            'grupo_id' => $grupoID,
            'fecha_generado' => now(),
            'validaciones' => 0,
            'ultima_validacion' => null
        ]);
    }

    public function buscarPorUuid(string $uuid): ?array
    {
        $registro = DB::table('certificados_generados AS c')
            ->join('participantes AS p', 'p.id', '=', 'c.participante_id')
            ->join('grupos AS g', 'g.id', '=', 'c.grupo_id')
            ->join('curso_calendario AS cc', 'cc.id', '=', 'g.curso_calendario_id')
            ->join('cursos AS cu', 'cu.id', '=', 'cc.curso_id')
            ->where('c.uuid', $uuid)
            ->select(
                'c.uuid',
                'c.fecha_generado',
                'c.validaciones',
                'c.ultima_validacion',
                DB::raw("CONCAT(p.primer_nombre, ' ', IFNULL(p.segundo_nombre, ''), ' ', p.primer_apellido, ' ', IFNULL(p.segundo_apellido, '')) AS nombre_participante"),
                'cu.nombre AS nombre_curso'
            )
            ->first();

        return $registro ? (array) $registro : null;
    }

    public function marcarComoValidado(string $uuid): void
    {
        DB::table('certificados_generados')
            ->where('uuid', $uuid)
            ->update([
                'validaciones' => DB::raw('validaciones + 1'),
                'ultima_validacion' => now()
            ]);

    }

}
