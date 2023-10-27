<?php

namespace Src\domain\repositories;

use Src\domain\Area;

interface AreaRepository {
    public function listarAreas(): array;
    public function buscarAreaPorNombre(string $nombre): Area;
    public function buscarAreaPorId(int $id = 0): Area;
    public function crearArea(Area $area): bool;
    public function eliminarArea(Area $area): bool;
    public function actualizarArea(Area $area): bool;
    public function listarOrientadoresPorArea(int $areaId): array;
}