<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;
use Src\domain\Convenio;
use Src\view\dto\Response;

class EliminarCalendarioUseCase {

    public function ejecutar(int $id=0): Response {

        $calendarioReposity = new CalendarioDao();

        $calendario = Calendario::buscarPorId($id, $calendarioReposity);
        if (!$calendario->existe())
            return new Response('404', 'Calendario no encontrado');


        $convenioUCMC = Convenio::UCMCActual();
        if ($convenioUCMC->existe()) {
            $convenioUCMC->eliminar();
        }

        $calendario->setRepository($calendarioReposity);
        $exito = $calendario->eliminar();

        if (!$exito)
            return new Response('500', 'Ha ocurrido un error en el sistema');

        return new Response('200', 'Registro eliminado con éxito');
    }
}