<?php

namespace Src\usecase\cursos;

use Src\dao\mysql\CursoDao;
use Src\domain\Curso;
use Src\view\dto\Response;

class EliminarCursoUseCase {
    public function ejecutar(int $id=0): Response {
        $cursoRepository = new CursoDao();
        
        $curso = Curso::buscarPorId($id, $cursoRepository);
        if (!$curso->existe()) 
            return new Response("404", "Curso no encontrado");
        
        $curso->setRepository($cursoRepository);

        $exito = $curso->eliminar();
        if (!$exito)
            return new Response("500", "Ha ocurrido un error en el sistema.");

        return new Response("200", "Registro eliminado con éxito.");
    }
}