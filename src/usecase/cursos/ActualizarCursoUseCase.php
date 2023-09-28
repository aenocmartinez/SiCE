<?php

namespace Src\usecase\cursos;

use Src\dao\mysql\CursoDao;
use Src\domain\Area;
use Src\domain\Curso;
use Src\view\dto\CursoDto;

class ActualizarCursoUseCase {

    public function ejecutar(CursoDto $cursoDto) {

        $cursoRepository = new CursoDao();
        $curso = Curso::buscarPorId($cursoDto->id, $cursoRepository);
        if (!$curso->existe()) {
            return [
                "code" => "404",
                "message" => "curso no encontrado",
            ];
        }
        
        $curso->setRepository($cursoRepository);
        $curso->setNombre($cursoDto->nombre);
        $curso->setModalidad($cursoDto->modalidad);
        $curso->setCosto($cursoDto->costo);

        $area = new Area();
        $area->setId($cursoDto->areaId);
        $curso->setArea($area);

        $exito = $curso->actualizar();
        if (!$exito) {
            return [
                "code" => "500",
                "message" => "ha ocurrido un error en el sistema",
            ];
        }

        return [
            "code" => "200",
            "message" => "el registro se ha actualizado con éxito",
        ];
    }
}