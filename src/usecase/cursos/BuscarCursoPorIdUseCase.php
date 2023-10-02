<?php

namespace Src\usecase\cursos;

use Src\dao\mysql\CursoDao;
use Src\domain\Curso;

class BuscarCursoPorIdUseCase {
    public function ejecutar(int $id=0): Curso {        
        return Curso::buscarPorId($id, new CursoDao());
    }
}