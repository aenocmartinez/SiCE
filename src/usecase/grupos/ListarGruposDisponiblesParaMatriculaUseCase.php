<?php 

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;

class ListarGruposDisponiblesParaMatriculaUseCase {

    public function ejecutar(int $calendarioId, int $areaId): array {
        $grupoRepository = new GrupoDao();
        return $grupoRepository->listarGruposDisponiblesParaMatricula($calendarioId, $areaId);
    }
}