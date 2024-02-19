<?php

namespace Src\usecase\participantes;

use Src\dao\mysql\ParticipanteDao;

class ListarParticipantesUseCase {

    public function ejecutar($page=1) {
        
        return (new ParticipanteDao())->listarParticipantes($page);
    }
}