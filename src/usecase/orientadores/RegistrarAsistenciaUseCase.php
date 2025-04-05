<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\GrupoDao;
use Src\dao\mysql\ParticipanteDao;
use Src\domain\AsistenciaClase;
use Src\view\dto\Response;

class RegistrarAsistenciaUseCase
{

    public function ejecutar(int $grupoID, int $sesion, $listaAsistencia = []): Response
    {
        $grupoDao = new GrupoDao();
        $participanteDao = new ParticipanteDao();

        $grupo = $grupoDao->buscarGrupoPorId($grupoID);
        if (!$grupo->existe()) {
            return new Response("404", "Grupo no encontrado");
        }

        if ($sesion < 1 || $sesion > 16) {
            return new Response("500", "Solo se permiten sesiones entre 1 y 16.");
        }


        $existeRegistroAsistencia = $grupoDao->buscarAsistenciaPorSesion($grupo->getId(), $sesion);
        if ($existeRegistroAsistencia) {
            return new Response("500", "Ya existe un registro de asistencia para la sesión indicada.");
        }

        foreach($listaAsistencia as $item) {
            
            $objeto = (object)$item;

            $participante = $participanteDao->buscarParticipantePorId($objeto->participante_id);
            if (!$participante->existe()) {
                continue;
            }
            
            $grupo->registrarAsistenciaAClase(new AsistenciaClase(
                $participante,
                $sesion,
                $objeto->presente
            ));

        }


        return new Response("200", "Asistencia registrada.");
    }
}