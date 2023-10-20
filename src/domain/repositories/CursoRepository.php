<?php

namespace Src\domain\repositories;

use Src\domain\Area;
use Src\domain\Curso;

interface CursoRepository {
    public function listarCursos(): array;
    public function buscarCursoPorNombreYArea(string $nombre, int $areaId): Curso;
    public function buscarCursoPorId(int $id = 0): Curso;
    public function crearCurso(Curso $curso): bool;
    public function eliminarCurso(Curso $curso): bool;
    public function actualizarCurso(Curso $curso): bool;    
    public function listarCursosPorArea(int $areaId): array;
}