<?php

namespace Src\usecase\cursos;

use Src\dao\mysql\CursoDao;
use Src\domain\Curso;

class ListarCursosUseCase {

    public function ejecutar(): array {
        $cursoRepository = new CursoDao();
        $lista = Curso::listar($cursoRepository);
        return [
            "code" => "200",
            "data" => $lista
        ];
    }
}