<?php

namespace Src\usecase\participantes;

use Src\dao\mysql\ParticipanteDao;

class ListarParticipantesUseCase {

    public function ejecutar(): array {

        $participanteRepository = new ParticipanteDao();
        
        return $participanteRepository->listarParticipantes();
    }
}