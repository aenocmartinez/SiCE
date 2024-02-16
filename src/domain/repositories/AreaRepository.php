<?php

namespace Src\domain\repositories;

use Src\domain\Area;
use Src\infraestructure\util\Paginate;

interface AreaRepository {
    public function listarAreas(): array;
    public function buscarAreaPorNombre(string $nombre): Area;
    public function buscarAreaPorId(int $id = 0): Area;
    public function crearArea(Area $area): bool;
    public function eliminarArea(Area $area): bool;
    public function actualizarArea(Area $area): bool;
    public function listarOrientadoresPorArea(int $areaId): array;
    public static function listaAreasPaginados($page=1): Paginate;
}