<?php

namespace Src\usecase\convenios;

use Src\dao\mysql\ConvenioDao;
use Src\domain\Convenio;

class ListarParticipantesConvenioUseCase {

    public function ejecutar($convenioId=0): array {

        $convenioDao = new ConvenioDao();
        $convenio = $convenioDao->buscarConvenioPorId($convenioId);
        if (!$convenio->existe()) {
            return [];
        }

        return $convenio->listarParticipantes();
    }
}