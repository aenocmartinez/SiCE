<?php

namespace Src\usecase\participantes;

use Src\dao\mysql\ParticipanteDao;
use Src\domain\Participante;

class BuscarParticipantePorDocumentoUseCase {

    public function ejecutar(string $tipoDocumento, string $documento): Participante {

        $participanteRepository = new ParticipanteDao();

        return $participanteRepository->buscarParticipantePorDocumento($tipoDocumento, $documento);
    }

}