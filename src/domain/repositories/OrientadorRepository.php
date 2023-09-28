<?php

namespace Src\domain\repositories;

use Src\domain\Area;
use Src\domain\Orientador;

interface OrientadorRepository {
    public function listarOrientadores(): array;
    public function buscarOrientadorPorId($id): Orientador;
    public function buscadorOrientador(string $criterio): array;
    public function buscarOrientadorPorDocumento(string $tipo, string $documento): Orientador;
    public function crearOrientador(Orientador $orientador): bool;
    public function eliminarOrientador(Orientador $orientador): bool;
    public function actualizarOrientador(Orientador $orientador): bool;
    public function agregarArea(Orientador $orientador, Area $area): bool;
    public function quitarArea(Orientador $orientador, Area $area): bool;
    public function listarAreasDeUnOrientador(Orientador $orientador): array;
}