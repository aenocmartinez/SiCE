<?php

namespace Src\usecase\convenios;

use Src\dao\mysql\ConvenioDao;
use Src\domain\Calendario;
use Src\view\dto\ConvenioDto;
use Src\view\dto\Response;

class CrearConvenioUseCase {

    public function ejecutar(ConvenioDto $convenioDto): Response {
        $convenioRepository = new ConvenioDao();

        $convenio = $convenioRepository->buscarConvenioPorNombreYCalendario($convenioDto->nombre, $convenioDto->calendarioId);
        if ($convenio->existe()) {
            return new Response("500", "el convenio ya existe");
        }

        $convenio->setRepository($convenioRepository);
        $convenio->setNombre($convenioDto->nombre);
        $convenio->setFecInicio($convenioDto->fechaInicial);
        $convenio->setFecFin($convenioDto->fechaFinal);
        $convenio->setDescuento($convenioDto->descuento);
        $convenio->setEsCooperativa($convenioDto->esCooperativa);
        $convenio->setComentarios($convenioDto->comentarios);

        $calendario = new Calendario();
        $calendario->setId($convenioDto->calendarioId);

        $convenio->setCalendario($calendario);

        $exito = $convenio->crear();
        if (!$exito) {
            return new Response("500", "Ha ocurrido un error en el sistema");
        }

        return new Response("201", "El convenio se ha creado con éxito.");
    }
}