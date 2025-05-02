<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;
use Src\domain\Convenio;
use Src\view\dto\CalendarioDto;
use Src\view\dto\Response;

class CrearCalendarioUseCase {

    public function ejecutar(CalendarioDto $calendarioDto): Response {
        $calendarioRepository = new CalendarioDao();
        $calendario = Calendario::buscarPorNombre($calendarioDto->nombre, $calendarioRepository);

        if ($calendario->existe()) 
        {
            return new Response('200', 'El calendario ya existe');        
        }
        
        $calendario->setNombre($calendarioDto->nombre);
        $calendario->setFechaInicio($calendarioDto->fechaInicial);
        $calendario->setFechaFinal($calendarioDto->fechaFinal);
        $calendario->setRepository($calendarioRepository);
        $calendario->setFechaInicioClase($calendarioDto->fechaInicioClase);
        $calendario->setFechaCertificado($calendarioDto->fechaCertificado);
        $calendario->setEstaFormularioInscripcionAbierto($calendarioDto->estaFormularioInscripcionAbierto);

        $exito = $calendario->crear();
        if (!$exito) {
            return new Response('500', 'Ha ocurrido un error en el sistema');
        }

        $calendario->pasarANoDisponibleLosBeneficiosPorConvenioDeUnParticipante();

        $convenio = new Convenio("Convenio empleados y contratistas UnicolMayor");
        $convenio->setFecInicio($calendarioDto->fechaInicial);
        $convenio->setFecFin($calendarioDto->fechaFinal);
        $convenio->setDescuento(100);
        $convenio->esCooperativa(false);
        $convenio->setEsUCMC(true);
        $convenio->setCalendario($calendario);
        $convenio->crear();        

        return new Response('200', 'Registro creado con éxito');
    }
}