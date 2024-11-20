<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;

class BuscarOrientadorPorIdUseCase {

    public function ejecutar(int $id=0): Orientador {

        $orientadorRepository = new OrientadorDao();

        $orientador = Orientador::buscarPorId($id, $orientadorRepository);

        return $orientador;
    }
}