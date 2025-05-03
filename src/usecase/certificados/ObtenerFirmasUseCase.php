<?php

namespace Src\usecase\certificados;

use Src\dao\mysql\FirmaDao;
use Src\view\dto\Response;

class ObtenerFirmasUseCase 
{
    public function ejecutar(): Response
    {
        return new Response("200", "Firmas encontradas", FirmaDao::ObtenerFirmas());
    }    
}