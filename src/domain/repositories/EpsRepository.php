<?php

namespace Src\domain\repositories;

use Src\domain\Eps;

interface EpsRepository {    
    public function buscar(string $nombre): Eps;
    public function listar(): array;
    public function crear(string $nombre): bool;
}