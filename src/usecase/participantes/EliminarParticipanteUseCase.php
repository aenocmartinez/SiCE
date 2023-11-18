<?php

namespace Src\usecase\participantes;

use Src\dao\mysql\ParticipanteDao;
use Src\view\dto\Response;

class EliminarParticipanteUseCase {

    public function ejecutar(int $participanteId): Response {

        $response = new Response();

        $participanteRepository = new ParticipanteDao();
        
        $exito = $participanteRepository->eliminarParticipante($participanteId);

        $response->code = "200";
        $response->message = "El registro se ha eliminado con éxito.";
        if (!$exito) {
            $response->code = "500";
            $response->message = "No se puede eliminar el participante porque tiene registros relacionados.";
        }

        return $response;
    }
}