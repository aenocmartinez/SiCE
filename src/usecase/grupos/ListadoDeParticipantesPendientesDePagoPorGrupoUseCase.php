<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\domain\Grupo;

class ListadoDeParticipantesPendientesDePagoPorGrupoUseCase
{

    public function Ejecutar(int $grupoId=0): array
    {
        $repository = new GrupoDao();
        $grupo = Grupo::buscarPorId($grupoId, $repository);
        if (!$grupo->existe()) {
            return [];
        }

        $grupo->setRepository($repository);

        return $grupo->participantesPendientesDePagoSinConvenio();
    }
}