<?php

namespace Src\domain\repositories;

use Src\domain\TipoSalon;

interface TipoSalonRepository {
    public function listarTipoSalones(): array;    
    public function buscarTipoSalonPorNombre(string $nombre): TipoSalon;
    public function buscarTipoSalonPorId(int $id = 0): TipoSalon;
    public function crearTipoSalon(TipoSalon $tipoSalon): bool;
    public function eliminarTipoSalon(TipoSalon $tipoSalon): bool;
    public function actualizarTipoSalon(TipoSalon $tipoSalon): bool;        
}