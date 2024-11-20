<?php

namespace Src\domain\repositories;

use Src\domain\Area;
use Src\domain\Orientador;
use Src\infraestructure\util\Paginate;

interface OrientadorRepository {
    public function listarOrientadores(): array;
    public function buscarOrientadorPorId($orientadorId): Orientador;
    public static function buscadorOrientador(string $criterio, $page=1): Paginate;
    public function buscarOrientadorPorDocumento(string $tipo, string $documento): Orientador;
    public function crearOrientador(Orientador &$orientador): bool;
    public function eliminarOrientador(Orientador $orientador): bool;
    public function actualizarOrientador(Orientador $orientador): bool;
    public function agregarArea(Orientador $orientador, Area $area): bool;
    public function quitarArea(Orientador $orientador): bool;
    public function listarAreasDeUnOrientador(Orientador $orientador): array;
    public static function listarOrientadoresPaginado($page=1): Paginate;
}