<?php

namespace Src\usecase\certificados;

use Src\dao\mysql\GrupoDao;
use Src\dao\mysql\ParticipanteDao;
use Src\view\dto\Response;

class ObtenerDatosParaCertificadoUseCase
{
    public function ejecutar(int $participanteID, int $grupoID): Response
    {
        $participanteDao = new ParticipanteDao();
        $grupoDao = new GrupoDao();

        $participante = $participanteDao->buscarParticipantePorId($participanteID);
        if (!$participante->existe())
        {
            return new Response("404", "Participante no encontrado");
        }
        
        $grupo = $grupoDao->buscarGrupoPorId($grupoID);
        if (!$grupo->existe())
        {
            return new Response("404", "Grupo no encontrado");
        }


        return new Response("200", "Datos encontrados para el certificado", [
            "participante" => $participante,
            "grupo" => $grupo
        ]);
    }
}