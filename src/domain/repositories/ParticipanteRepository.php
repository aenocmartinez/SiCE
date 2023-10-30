<?php

namespace Src\domain\repositories;

use Src\domain\Participante;

interface ParticipanteRepository {

    public function buscarParticipantePorDocumento(string $tipo, string $documento): Participante;
}