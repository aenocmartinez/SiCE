<?php

namespace Src\usecase\participantes;

use Src\dao\mysql\ParticipanteDao;

class ListarFormulariosParticipanteUseCase {

    public function ejecutar(int $participanteId): array {

        return (new ParticipanteDao())->listarFormulariosDeInscripcionParticipante($participanteId);
    }
}