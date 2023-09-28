<?php

namespace Src\domain\repositories;

use Src\domain\Salon;

interface SalonRepository {
    public function listarSalones(): array;
    public function buscadorSalones($filtro = []): array;
    public function buscarSalonPorNombre(string $nombre): Salon;
    public function buscarSalonPorId(int $id = 0): Salon;
    public function crearSalon(Salon $salon): bool;
    public function eliminarSalon(Salon $salon): bool;
    public function actualizarSalon(Salon $salon): bool;    
}