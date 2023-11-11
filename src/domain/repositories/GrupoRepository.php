<?php

namespace Src\domain\repositories;

use Src\domain\Grupo;

interface GrupoRepository {
    public function listarGrupos(): array;
    public function buscarGrupoPorId(int $id): Grupo;
    public function buscadorGrupo(string $criterio): array;
    public function crearGrupo(Grupo $grupo): bool;
    public function eliminarGrupo(Grupo $grupo): bool;
    public function actualizarGrupo(Grupo $grupo): bool;
    public function existeGrupo(Grupo $grupo): bool;
    public function salonDisponible(Grupo $grupo): bool;
    public function listarGruposDisponiblesParaMatricula(int $calendarioId, int $areaId): array;
    public function buscadorGrupos(string $criterio): array;
}