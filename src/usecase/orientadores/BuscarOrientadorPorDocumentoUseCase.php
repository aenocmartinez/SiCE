<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;

class BuscarOrientadorPorDocumentoUseCase {

    public function ejecutar(string $tipoDocumento, string $documento): Orientador {

        $orientadorRepository = new OrientadorDao();
        return Orientador::buscarPorDocumento($tipoDocumento, $documento, $orientadorRepository);
    }
}