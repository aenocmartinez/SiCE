<?php

namespace Src\usecase\cursos;

use Src\dao\mysql\CursoDao;
use Src\domain\Area;
use Src\domain\Curso;
use Src\view\dto\CursoDto;
use Src\view\dto\Response;

class CrearCursoUseCase {
    public function ejecutar(CursoDto $cursoDto): Response {
        $cursoRepository = new CursoDao();
        
        $curso = Curso::buscarPorNombreYArea($cursoDto->nombre, $cursoDto->areaId, $cursoRepository);
        if ($curso->existe()) 
            return new Response("500", "el curso ya existe");

        $curso = new Curso();
        $curso->setRepository($cursoRepository);
        $curso->setNombre($cursoDto->nombre);
        $curso->setTipoCurso($cursoDto->tipoCurso);
        $curso->setArea(new Area($cursoDto->areaId));

        $exito = $curso->crear();
        if (!$exito) 
            return new Response("500", "Ha ocurrido un error en el sistema.");

        return new Response("201", "Registro creado con éxito.");
    }
}