<?php

namespace Src\usecase\certificados;

use Illuminate\Support\Facades\DB;

class BuscarCertificadoPorUUIDUseCase
{
    public function ejecutar(string $uuid): ?array
    {
        $registro = DB::table('certificados_generados AS c')
            ->where('c.uuid', $uuid)
            ->select('c.participante_id', 'c.grupo_id')
            ->first();

        return $registro ? (array) $registro : null;
    }
}
