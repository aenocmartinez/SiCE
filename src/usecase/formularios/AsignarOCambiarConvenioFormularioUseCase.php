<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;

class AsignarOCambiarConvenioFormularioUseCase
{

    public function Ejecutar(int $formularioID, int $convenioID)
    {
        FormularioInscripcionDao::asignarConvenioAFormulario($formularioID, $convenioID);
    }
}