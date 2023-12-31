<?php

namespace Src\domain\repositories;

use Src\domain\Participante;
use Src\view\dto\ConfirmarInscripcionDto;

interface ParticipanteRepository {

    public function buscarParticipantePorDocumento(string $tipo, string $documento): Participante;
    public function crearParticipante(Participante $participante): bool;
    public function actualizarParticipante(Participante $participante): bool;
    public function buscarParticipantePorId(int $participanteId): Participante;
    public function listarParticipantes(): array;
    public function buscadorParticipantes(string $criterio): array;
    public function listarFormulariosDeInscripcionParticipante(int $participanteId): array;
    public function eliminarParticipante(int $participanteId): bool;
}