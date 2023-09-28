<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;
use Src\view\dto\OrientadorDto;

class CrearOrientadorUseCase {

    public function ejecutar(OrientadorDto $orientadorDto) {

        $orientadorRepository = new OrientadorDao();

        $orientador = Orientador::buscarPorDocumento($orientadorDto->tipoDocumento, $orientadorDto->documento, $orientadorRepository);

        if ($orientador->existe()) {
            return [
                "code" => "200",
                "message" => "orientador ya existe"
            ];
        }

        $orientador->setRepository($orientadorRepository);
        $orientador->setNombre($orientadorDto->nombre);
        $orientador->setTipoDocumento($orientadorDto->tipoDocumento);
        $orientador->setDocumento($orientadorDto->documento);
        $orientador->setEmailInstitucional($orientadorDto->emailInstitucional);
        $orientador->setEmailPersonal($orientadorDto->emailPersonal);
        $orientador->setDireccion($orientadorDto->direccion);
        $orientador->setEps($orientadorDto->eps);
        $orientador->setObservacion($orientadorDto->observacion);

        $exito = $orientador->crear();
        if (!$exito) {
            return [
                "code" => "500",
                "message" => "ha ocurrido un error en el sistema",
            ];
        }

        return [
            "code" => "200",
            "message" => "registro creado con éxito",
        ];
    }
}