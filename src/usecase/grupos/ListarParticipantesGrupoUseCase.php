<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;

class ListarParticipantesGrupoUseCase {

    public function ejecutar($grupoId=0): array {

        $grupoDao = new GrupoDao();
        return $grupoDao->listadoParticipantesGrupo($grupoId);
    }    
}