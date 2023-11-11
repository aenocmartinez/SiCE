<?php

namespace Src\usecase\participantes;

use Src\dao\mysql\ParticipanteDao;
use Src\domain\Participante;

class BuscarParticipantePorIdUseCase {

    public function ejecutar(int $participanteId): Participante {
        $participanteRepository = new ParticipanteDao();
        return $participanteRepository->buscarParticipantePorId($participanteId);
    }

}