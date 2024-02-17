<?php

namespace Src\usecase\orientadores;

use Src\domain\Orientador;

class ListarOrientadoresPaginadoUseCase {

    public function ejecutar($page=1) {

        return Orientador::Paginar($page);
    }
}