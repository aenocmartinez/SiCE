<?php

namespace Src\usecase\convenios;

use Src\dao\mysql\ConvenioDao;
use Src\domain\Convenio;

class BuscarConvenioPorIdUseCase {

    public function ejecutar(int $convenioId=0): Convenio {
        $convenioRepository = new ConvenioDao();
        return $convenioRepository->buscarConvenioPorId($convenioId);
    }
}