<?php

namespace Src\usecase\convenios;

use Src\domain\Calendario;
use Src\domain\Convenio;

class ListarConveniosPorPeriodoUseCase
{

    public function Ejecutar($periodoId = 0): array
    {
        $convenios = [];

        $periodo = Calendario::buscarPorId($periodoId);

        if (!$periodo->existe()) 
        {
            return $convenios;
        }

        $convenios = Convenio::listadoDeConveniosPorPeriodo($periodo);

        return $convenios;
    }
}