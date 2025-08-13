<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;
use Src\view\dto\Response;

class EliminarOrientadorUseCase {

    public function ejecutar(int $id=0): Response{

        $orientadorRepository = new OrientadorDao();

        $orientador = Orientador::buscarPorId($id, $orientadorRepository);
        if (!$orientador->existe()) {
            return new Response('404', 'instructor no encontrado');
        }

        $orientador->setRepository($orientadorRepository);
        $exito = $orientador->eliminar();
        if (!$exito) {
            return new Response('500', 'Ha ocurrido un error en el sistema');
        }        

        return new Response('200', 'Registro eliminado con éxito');
    }
}