<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;

class EliminarOrientadorUseCase {

    public function ejecutar(int $id=0): array{

        $orientadorRepository = new OrientadorDao();

        $orientador = Orientador::buscarPorId($id, $orientadorRepository);
        if (!$orientador->existe()) {
            return [
                "code" => "404",
                "message" => "orientador no encontrado"
            ];            
        }

        $orientador->setRepository($orientadorRepository);
        $exito = $orientador->eliminar();
        if (!$exito) {
            return [
                "code" => "500",
                "message" => "ha ocurrido un error en el sistema",
            ];
        }        

        return [
            "code" => "200",
            "message" => "registro eliminado con éxito"
        ];

    }
}