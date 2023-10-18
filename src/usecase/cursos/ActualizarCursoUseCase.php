<?php

namespace Src\usecase\cursos;

use Src\dao\mysql\CursoDao;
use Src\domain\Area;
use Src\domain\Curso;
use Src\view\dto\CursoDto;
use Src\view\dto\Response;

class ActualizarCursoUseCase {

    public function ejecutar(CursoDto $cursoDto): Response {

        $cursoRepository = new CursoDao();
        $curso = Curso::buscarPorId($cursoDto->id, $cursoRepository);
        if (!$curso->existe()) 
            return new Response("404", "Curso no encontrado");
        
        $curso->setRepository($cursoRepository);
        $curso->setNombre($cursoDto->nombre);
        $curso->setArea(new Area($cursoDto->areaId));

        $exito = $curso->actualizar();

        if (!$exito)
            return new Response("500", "Ha ocurrido un error en el sistema");
        
        return new Response("200", "Registro actualizado con éxito");
    }
}