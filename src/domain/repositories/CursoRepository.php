<?php

namespace Src\domain\repositories;

use Src\domain\Curso;
use Src\infraestructure\util\Paginate;

interface CursoRepository {
    public function listarCursos(): array;
    public function buscarCursoPorNombreYArea(string $nombre, int $areaId): Curso;
    public function buscarCursoPorId(int $id = 0): Curso;
    public function crearCurso(Curso $curso): bool;
    public function eliminarCurso(Curso $curso): bool;
    public function actualizarCurso(Curso $curso): bool;    
    public function listarCursosPorArea(int $areaId): array;
    public static function listaCursosPaginados($page=1): Paginate;
    public static function buscadorCursos(string $criterio, $page): Paginate;
    public static function top5CursosMasInscritosPorCalendario($calendarioId): array;
}