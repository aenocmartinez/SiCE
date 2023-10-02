<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;

class ListarOrientadoresUseCase {
    public function ejecutar(): array {
        $orientadorRepository = new OrientadorDao();
        return Orientador::listar($orientadorRepository);
    }
}