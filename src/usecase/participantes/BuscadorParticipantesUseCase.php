<?php

namespace Src\usecase\participantes;

use Src\dao\mysql\ParticipanteDao;

class BuscadorParticipantesUseCase {

    public function ejecutar(string $criterio, $page=1) {
        $participanteRepository = new ParticipanteDao();
        return $participanteRepository->buscadorParticipantes($criterio, $page);
    }
}