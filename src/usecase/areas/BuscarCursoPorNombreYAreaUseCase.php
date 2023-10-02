<?php

namespace Src\usecase\areas;

use Src\dao\mysql\CursoDao;
use Src\domain\Curso;

class BuscarCursoPorNombreYAreaUseCase {

    public function ejecutar(string $nombre, int $areaId): Curso {

        return Curso::buscarPorNombreYArea($nombre, $areaId, new CursoDao());
    }
}