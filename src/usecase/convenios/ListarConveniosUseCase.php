<?php

namespace Src\usecase\convenios;

use Src\dao\mysql\ConvenioDao;

class ListarConveniosUseCase {

    public function ejecutar(): array {
        $convenioRepository = new ConvenioDao();

        return $convenioRepository->listarConvenios();
    }
}