<?php

namespace Src\domain\repositories;

use Src\domain\Calendario;
use Src\domain\CursoCalendario;

interface CalendarioRepository {
    public function listarCalendarios(): array;
    public function buscarCalendarioPorNombre(string $nombre): Calendario;
    public function buscarCalendarioPorId(int $id = 0): Calendario;
    public function crearCalendario(Calendario &$calendario): bool;
    public function eliminarCalendario(Calendario $calendario): bool;
    public function actualizarCalendario(Calendario $calendario): bool;
    public function agregarCurso(CursoCalendario $cursoCalendario): bool;
    public function retirarCurso(CursoCalendario $cursoCalendario): bool;
    public function listarCursos(int $calendarioId, int $areaId): array;
    public function listarCursosPorCalendario(int $calendarioId): array;
    public function buscarCursoCalendario(int $calendariId=0, int $cursoId=0, string $modalidad=''): CursoCalendario;
    public static function existeCalendarioVigente(): bool;
    public static function obtenerCalendarioActualVigente(): Calendario;
    public function listarInscripcionesPorCalendario(int $calendarioId): array;
    public function listarGruposParaInscripcion(int $calendarioId): array;
    public static function pasarANoDisponibleLosBeneficiosPorConvenioDeUnParticipante(): void;
    public static function listadoParticipantesPorCalendario($calendarioId=0): array;
    public function listarConveniosPorCalendario($calendarioId): array;
}