<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;

class BuscarOrientadorPorDocumentoUseCase {

    public function ejecutar(string $tipoDocumento, string $documento): array {

        $orientadorRepository = new OrientadorDao();
        $orientador = Orientador::buscarPorDocumento($tipoDocumento, $documento, $orientadorRepository);
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
                "observacion" => $orientador->getObservacion(),
            ],
        ];
    }
}