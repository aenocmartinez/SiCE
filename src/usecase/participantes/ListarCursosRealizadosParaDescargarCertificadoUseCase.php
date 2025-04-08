<?php

namespace Src\usecase\participantes;

use Src\dao\mysql\ParticipanteDao;
use Src\view\dto\Response;

class ListarCursosRealizadosParaDescargarCertificadoUseCase
{

    public function ejecutar(int $participanteID = 0): Response
    {

        $particpanteDao = new ParticipanteDao();
        $participante = $particpanteDao->buscarParticipantePorId($participanteID);
        if (!$participante->existe())
        {
            new Response("404", "Participante no encontrado");
        }

        return new Response("200", "Cursos encontrados", $participante);
    }
}