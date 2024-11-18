<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;
use Src\domain\Convenio;
use Src\view\dto\CalendarioDto;
use Src\view\dto\Response;

class ActualizarCalendarioUseCase {

    public function ejecutar(CalendarioDto $calendarioDto): Response {

        $calendarioRepository = new CalendarioDao();
        
        $calendario = Calendario::buscarPorId($calendarioDto->id, $calendarioRepository);
        if (!$calendario->existe()) 
        {
            return new Response('200', 'Calendario no encontrado');
        }
        
        $fechaInicioActual = $calendario->getFechaInicio();
        $fechaFinalActual = $calendario->getFechaFinal();

        $calendario->setRepository($calendarioRepository);
        $calendario->setNombre($calendarioDto->nombre);
        $calendario->setFechaInicio($calendarioDto->fechaInicial);
        $calendario->setFechaFinal($calendarioDto->fechaFinal);
        $calendario->setFechaInicioClase($calendarioDto->fechaInicioClase);

        $exito = $calendario->actualizar();
        if (!$exito) {
            return new Response('500', 'Ha ocurrido un error en el sistema');
        }

        $convenioUCMC = Convenio::UCMCActual();
        if ($convenioUCMC->existe()) {
            $convenioUCMC->setFecInicio($calendarioDto->fechaInicial);
            $convenioUCMC->setFecFin($calendarioDto->fechaFinal);
            $convenioUCMC->actualizar();
        }

        if ($fechaFinalActual != $calendarioDto->fechaInicial || 
            $fechaFinalActual != $calendarioDto->fechaFinal
        ){
            $convenios = Convenio::listadoDeConveniosPorPeriodo($calendario);
            foreach ($convenios as $convenio)
            {
                $convenio->setFecInicio($calendario->getFechaInicio());
                $convenio->setFecFin($calendario->getFechaFinal());
                $convenio->actualizar();
            }
        }
        
        return new Response('200', 'Registro actualizado con éxito');
    }
}