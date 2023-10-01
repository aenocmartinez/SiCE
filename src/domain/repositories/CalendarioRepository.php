<?php

namespace Src\domain\repositories;

use Src\domain\Calendario;

interface CalendarioRepository {
    public function listarCalendarios(): array;
    public function buscarCalendarioPorNombre(string $nombre): Calendario;
    public function buscarCalendarioPorId(int $id = 0): Calendario;
    public function crearCalendario(Calendario $calendario): bool;
    public function eliminarCalendario(Calendario $calendario): bool;
    public function actualizarCalendario(Calendario $calendario): bool;       
}