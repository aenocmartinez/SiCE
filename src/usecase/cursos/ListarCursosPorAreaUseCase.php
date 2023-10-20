<?php

namespace Src\usecase\cursos;

use Src\dao\mysql\CursoDao;

class ListarCursosPorAreaUseCase {

    public function ejecutar(int $idArea): array {
        $cursoRepository = new CursoDao();
        return $cursoRepository->listarCursosPorArea($idArea);
    }
}