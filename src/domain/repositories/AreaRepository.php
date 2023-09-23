<?php

namespace Src\domain\repositories;

use Src\domain\Area;

interface AreaRepository {
    public function listarAreas(): array;
    public function buscarAreaPorNombre(string $nombre): Area;
    public function crearArea(Area $area): bool;
}