<?php

namespace Src\domain\repositories;

use Src\domain\Convenio;
use Src\domain\FormularioInscripcion;
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
    public static function totalDeFormulariosInscritoPorUnParticipanteEnUnPeriodo($participanteId=0, $calendarioId=0): int;
    public function buscarFormularioInscripcionPorParticipanteYConvenioPendienteDepago($participanteId=0, $convenioId=0):FormularioInscripcion;
    public static function numeroParticipantesUnicosPorCalendario($calendarioId): int;
    public function listarFormulariosPendientesDePago(int $participanteId): array;
    public function aplazamientos();
    public function formularios_inscritos_en_un_periodo($particite_id, $periodo_id): array;
    public function listarCursosAprobados(int $participanteID): array;
    public function listarCursosParticipados(int $participanteID): array;
}