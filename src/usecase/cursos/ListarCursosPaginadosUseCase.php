<?php

namespace Src\usecase\cursos;

use Src\domain\Curso;

class ListarCursosPaginadosUseCase {

    public function ejecutar($page=1) {
        
        return Curso::Paginar($page);
    }
}