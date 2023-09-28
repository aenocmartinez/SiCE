<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;

class BuscarOrientadorPorIdUseCase {

    public function ejecutar(int $id=0): array {

        $orientadorRepository = new OrientadorDao();

        $orientador = Orientador::buscarPorId($id, $orientadorRepository);
        if (!$orientador->existe()) {
            return [
                "code" => "404",
                "message" => "orientador no encontrado",
            ];
        }

        return [
            "code" => "200",
            "data" => [
                "id" => $orientador->getId(),
                "nombre" => $orientador->getNombre(),
                "tipo_documento" => $orientador->getTipoDocumento(),
                "documento" => $orientador->getDocumento(),
                "tipo_numero_documento" => $orientador->getTipoNumeroDocumento(),
                "email_institucional" => $orientador->getEmailInstitucional(),
                "email_personal" => $orientador->getEmailPersonal(),
                "direccion" => $orientador->getDireccion(),
                "eps" => $orientador->getEps(),
                "estado" => $orientador->getEstadoComoTexto(),
                "observacion" => $orientador->getObservacion(),
            ],
        ];
    }
}