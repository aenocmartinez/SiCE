<?php

namespace Src\usecase\cursos;

use Src\dao\mysql\CursoDao;
use Src\domain\Area;
use Src\domain\Curso;
use Src\view\dto\CursoDto;

class CrearCursoUseCase {
    public function ejecutar(CursoDto $cursoDto): array {
        $cursoRepository = new CursoDao();
        
        $curso = Curso::buscarPorNombreYArea($cursoDto->nombre, $cursoDto->areaId, $cursoRepository);
        if ($curso->existe()) {
            return [
                "code" => "200",
                "message" => "el curso ya existe",
            ];
        }

        $curso = new Curso();
        $curso->setRepository($cursoRepository);
        $curso->setModalidad($cursoDto->modalidad);
        $curso->setNombre($cursoDto->nombre);
        $curso->setCosto($cursoDto->costo);

        $area = new Area();
        $area->setId($cursoDto->areaId);
        $curso->setArea($area);

        $resultado = $curso->crear();
        if (!$resultado) {
            return [
                "code" => "500",
                "message" => "ha ocurrido un error en el sistema"
            ];
        }
        
        return [
            "code" => "200",
            "message" => "registro creado con éxito"
        ];
    }
}