<?php

namespace Src\usecase\participantes;

use Src\dao\mysql\ParticipanteDao;

class BuscadorParticipantesUseCase {

    public function ejecutar(string $criterio): array {
        $participanteRepository = new ParticipanteDao();
        return $participanteRepository->buscadorParticipantes($criterio);
    }
}