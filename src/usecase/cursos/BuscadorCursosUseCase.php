<?php

namespace Src\usecase\cursos;

use Src\domain\Curso;

class BuscadorCursosUseCase {

    public function ejecutar(string $criterio, $page=1) {                  
        return Curso::buscador($criterio, $page);
    }
}