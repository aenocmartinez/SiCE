<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;
use Src\view\dto\CalendarioDto;
use Src\view\dto\Response;

class ActualizarCalendarioUseCase {

    public function ejecutar(CalendarioDto $calendarioDto): Response {

        $calendarioRepository = new CalendarioDao();
        
        $calendario = Calendario::buscarPorId($calendarioDto->id, $calendarioRepository);
        if (!$calendario->existe()) 
            return new Response('200', 'Calendario no encontrado');
        

        $calendario->setRepository($calendarioRepository);
        $calendario->setNombre($calendarioDto->nombre);
        $calendario->setFechaInicio($calendarioDto->fechaInicial);
        $calendario->setFechaFinal($calendarioDto->fechaFinal);

        $exito = $calendario->actualizar();
        if (!$exito)
            return new Response('500', 'Ha ocurrido un error en el sistema');
        
        return new Response('200', 'Registro actualizado con éxito');
    }
}