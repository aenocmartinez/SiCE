<?php

namespace Src\usecase\convenios;

use Src\dao\mysql\ConvenioDao;
use Src\domain\Calendario;
use Src\view\dto\ConvenioDto;
use Src\view\dto\Response;

class ActualizarConvenioUseCase {

    public function ejecutar(ConvenioDto $convenioDto): Response {
        $convenioRepository = new ConvenioDao();

        $convenio = $convenioRepository->buscarConvenioPorId($convenioDto->id);
        if (!$convenio->existe()) {
            return new Response("404", "El convenio no existe.");
        }


        $convenio->setRepository($convenioRepository);
        $convenio->setNombre($convenioDto->nombre);
        $convenio->setFecInicio($convenioDto->fechaInicial);
        $convenio->setFecFin($convenioDto->fechaFinal);
        $convenio->setDescuento($convenioDto->descuento);

        $calendario = new Calendario();
        $calendario->setId($convenioDto->calendarioId);

        $convenio->setCalendario($calendario);     
        
        $exito = $convenio->actualizar();
        if (!$exito) {
            return new Response("500", "Ha ocurrido un error en el sistema");
        }        
        
        return new Response("200", "El convenio se ha actualizad con éxito.");
    }
}