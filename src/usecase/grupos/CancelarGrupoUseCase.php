<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\view\dto\Response;

class CancelarGrupoUseCase {

    public function ejecutar($grupoId=0): Response {

        $grupoRepository = new GrupoDao();

        $grupo = $grupoRepository->buscarGrupoPorId(intval($grupoId));
        if (!$grupo->existe()) {
            return new Response('404', 'Grupo no encontrado');
        } 
        
        $grupo->setRepository($grupoRepository);
        if (!$grupo->cancelar()) {
            return new Response('500', 'Ha ocurrido un error en el sistema');
        }

        return new Response('200', 'El grupo se ha cancelado con éxito'); 
    }
}