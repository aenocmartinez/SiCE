<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;

class BuscadorOrientadorUseCase {

    public function ejecutar(string $criterio): array {
        $orientadorRepository = new OrientadorDao();
        $orientadores = Orientador::buscador($criterio, $orientadorRepository);

        return [
            "code" => "200",
            "data" => $orientadores
        ];
    }
}