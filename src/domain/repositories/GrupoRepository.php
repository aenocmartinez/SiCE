<?php

namespace Src\domain\repositories;

use Src\domain\Calendario;
use Src\domain\Grupo;
use Src\infraestructure\util\Paginate;

interface GrupoRepository {
    public static  function listarGrupos($page=1, Calendario $periodo): Paginate;
    public function buscarGrupoPorId(int $id): Grupo;
    public function crearGrupo(Grupo $grupo): bool;
    public function eliminarGrupo(Grupo $grupo): bool;
    public function actualizarGrupo(Grupo $grupo): bool;
    public function existeGrupo(Grupo $grupo): bool;
    public function salonDisponible(Grupo $grupo): bool;
    public function listarGruposDisponiblesParaMatricula(int $calendarioId, int $areaId): array;
    public static function buscadorGrupos(string $criterio, Calendario $calendario, $page=1): Paginate;
    public function tieneCuposDisponibles($grupoId=0): bool;
    public static function listadoParticipantesGrupo($grupoId=0): array;
    public static function restriccionesParaCrearOActualizarUnGrupo(Grupo $grupo, Calendario $calendario): string;
    public function totalDeParticipantesPendienteDePagoSinConvenio(int $grupoId): int;
    public function participantesPendienteDePagoSinConvenio(int $grupoId): array;
    public function cancelarGrupo($grupoId=0): bool;
}