<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\domain\Grupo;
use Src\view\dto\Response;

class EliminarGrupoUseCase {
    
    public function ejecutar(int $id=0): Response {

        $grupoRepository = new GrupoDao();
        $grupo = Grupo::buscarPorId($id, $grupoRepository);

        if (!$grupo->existe()) {
            return new Response('404', 'Grupo no encontrado');
        }

        $grupo->setRepository($grupoRepository);
        $exito = $grupo->eliminar();

        if (!$exito) {
            return new Response('500', 'Ha ocurrido un error en el sistema');
        }

        return new Response('200', 'Registro eliminado con éxito');
    }
    
}