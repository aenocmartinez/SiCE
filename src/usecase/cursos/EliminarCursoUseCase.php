<?php

namespace Src\usecase\cursos;

use Src\dao\mysql\CursoDao;
use Src\domain\Curso;

class EliminarCursoUseCase {
    public function ejecutar(int $id): array {
        $cursoRepository = new CursoDao();
        $curso = Curso::buscarPorId($id, $cursoRepository);
        if (!$curso->existe()) {
            return [
                "code" => "200",
                "message" => "curso no encontrado"
            ];
        }

        $curso->setRepository($cursoRepository);
        $exito = $curso->eliminar();
        if (!$exito) {
            return [
                "code" => "500",
                "message" => "ha ocurrido un error en el sistema"
            ];
        }
        
        return [
            "code" => "200",
            "message" => "registro elimiando con éxito"
        ];
    }
}