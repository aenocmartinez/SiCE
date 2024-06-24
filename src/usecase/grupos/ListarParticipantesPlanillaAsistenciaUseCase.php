<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;

class ListarParticipantesPlanillaAsistenciaUseCase {
    
    public function ejecutar($grupoId=0): array {
        
        return GrupoDao::listadoParticipantesPlanillaAsistencia($grupoId);
    }
}