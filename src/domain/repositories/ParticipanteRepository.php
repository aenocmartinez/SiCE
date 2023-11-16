<?php

namespace Src\domain\repositories;

use Src\domain\Participante;
use Src\view\dto\ConfirmarInscripcionDto;

interface ParticipanteRepository {

    public function buscarParticipantePorDocumento(string $tipo, string $documento): Participante;
    public function crearParticipante(Participante $participante): bool;
    public function actualizarParticipante(Participante $participante): bool;
    public function buscarParticipantePorId(int $participanteId): Participante;
}