<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\view\dto\Response;

class LegalizarInscripcionUseCase {

    public function ejecutar(int $formularioId, string $voucher): Response {

        $response = new Response;
        $formularioRepository = new FormularioInscripcionDao();
        $exito = $formularioRepository->legalizarFormulario($formularioId, $voucher);

        $response->code = "200";
        $response->message = "El formulario se ha legalizado con éxito.";
        if (!$exito) {
            $response->code = "500";
            $response->message = "Ha ocurrido un error en el sistema";
        }
        
        return $response;
    }
}