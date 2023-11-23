<?php

namespace Src\usecase\formularios;

use Src\infraestructure\medioPago\PagoFactory;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\Response;

class PagarFormularioUseCase {

    public function ejecutar(ConfirmarInscripcionDto $confirmarInscripcionDto): Response {
        
        $medioPago = PagoFactory::Medio($confirmarInscripcionDto->medioPago);
        
        return $medioPago->Pagar($confirmarInscripcionDto);
    }
}