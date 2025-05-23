<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\view\dto\Response;

class AnularFormularioUseCase {

    public function ejecutar($numeroFormulario, $motivo=""): Response {
        $response = new Response();

        $formularioRepository = new FormularioInscripcionDao();
        $exito = $formularioRepository->anularInscripcion($numeroFormulario, $motivo);

        $response->code = "200";
        $response->message = "El formulario se ha sido anulado.";
        if (!$exito) {
            $response->code = "500";
            $response->message = "Ha ocurrido un error en el sistema";
        }

        return $response;
    }
}