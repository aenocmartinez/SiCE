<?php

namespace Src\usecase\cursos;

use Src\dao\mysql\CursoDao;
use Src\domain\Curso;

class BuscarCursoPorIdUseCase {
    public function ejecutar(int $id=0): array {
        
        $cursoRepository = new CursoDao();
        
        $curso = Curso::buscarPorId($id, $cursoRepository);        
        if (!$curso->existe()) {
            return [
                "code" => "200",
                "message" => "curso no encontrado"
            ];
        }


        return [
            "code" => "200",
            "data" => [
                "id" => $curso->getId(),
                "nombre" => $curso->getNombre(),
                "modalidad" => $curso->getModalidad(),
                "costo" => $curso->getCosto(),
                "area" => [
                    "id" => $curso->areaId(),
                    "nombre" => $curso->areaNombre(),
                ],
            ]
        ];
    }
}