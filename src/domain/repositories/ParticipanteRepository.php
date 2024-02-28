<?php

namespace Src\domain\repositories;

use Src\domain\Convenio;
use Src\domain\Participante;
use Src\infraestructure\util\Paginate;

interface ParticipanteRepository {

    public function buscarParticipantePorDocumento(string $tipo, string $documento): Participante;
    public function crearParticipante(Participante $participante): bool;
    public function actualizarParticipante(Participante $participante): bool;
    public function buscarParticipantePorId(int $participanteId): Participante;
    public function listarParticipantes($page=1): Paginate;
    public function buscadorParticipantes(string $criterio, $page=1): Paginate;
    public function listarFormulariosDeInscripcionParticipante(int $participanteId): array;
    public function eliminarParticipante(int $participanteId): bool;
    public static function numeroParticipantesPorGeneroYCalendario($sexo='M', $calendarioId): int;
    public static function numeroParticipantesPorConvenioYCalendario($calendarioId): int;
    public function buscarBeneficiosAlParticipante(int $participanteId): Convenio;
}