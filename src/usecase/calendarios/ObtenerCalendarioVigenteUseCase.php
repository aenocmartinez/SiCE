<?php

namespace Src\usecase\calendarios;

use Src\domain\Calendario;

class ObtenerCalendarioVigenteUseCase {

    public function ejecutar() {
        
        return Calendario::Vigente();
    }
}