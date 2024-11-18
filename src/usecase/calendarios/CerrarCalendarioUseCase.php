<?php

namespace Src\usecase\calendarios;

use Src\domain\Calendario;
use Src\domain\Convenio;
use Src\usecase\convenios\FacturarConvenioUseCase;

class CerrarCalendarioUseCase
{

    public function Ejecutar(Calendario $periodo)
    {
        //$fechaDeCierre = date('Y-m-d H:m:s', strtotime('-1 day'));
        $fechaDeCierre = date('Y-m-d H:m:s');

        foreach($periodo->listarConvenios() as $convenio) 
        {
            if ($convenio->esCooperativa())
            {
                (new FacturarConvenioUseCase)->ejecutar($convenio);
            }

            $convenio->setFecFin($fechaDeCierre);
            $convenio->actualizar();
        }

        $periodo->setFechaFinal($fechaDeCierre);
        $periodo->setEstaFormularioInscripcionAbierto(false);
        $periodo->actualizar();
        
    }
}