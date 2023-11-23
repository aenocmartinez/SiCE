<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\Response;

class ConfirmarInscripcionUseCase {
    
    public function ejecutar(ConfirmarInscripcionDto $confirmarInscripcionDto): Response {
        
        $formularioRepository = new FormularioInscripcionDao();   
                
        $exito = $formularioRepository->crearInscripcion($confirmarInscripcionDto);
        if (!$exito) {
            return new Response("500", "Ha ocurrido al intentar confirmar la inscripción.");
        }

        return (new PagarFormularioUseCase)->ejecutar($confirmarInscripcionDto);
    }
}