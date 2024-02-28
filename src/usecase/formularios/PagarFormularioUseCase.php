<?php

namespace Src\usecase\formularios;

use Src\domain\FormularioInscripcion;
use Src\view\dto\Response;

class PagarFormularioUseCase {

    public function ejecutar(FormularioInscripcion $formularioInscripcion): Response {
            
        return new Response("201", "Se ha registrado con éxito.");
    }
}