<?php

namespace Src\usecase\convenios;

use Src\dao\mysql\ConvenioDao;
use Src\domain\Calendario;
use Src\domain\Convenio;
use Src\domain\ConvenioRegla;
use Src\view\dto\ConvenioDto;
use Src\view\dto\Response;

class CrearConvenioUseCase {

    public function ejecutar(ConvenioDto $convenioDto): Response {

        $convenioRepository = new ConvenioDao();

        $convenio = $convenioRepository->buscarConvenioPorNombreYCalendario($convenioDto->nombre, $convenioDto->calendarioId);
        if ($convenio->existe()) {
            return new Response("500", "el convenio ya existe");
        }

        $periodo = Calendario::buscarPorId($convenioDto->calendarioId);
        if (!$periodo->existe()) {
            return new Response("500", "El periodo no existe");
        }
        
        $convenio->setRepository($convenioRepository);
        $convenio->setNombre($convenioDto->nombre);
        $convenio->setFecInicio($periodo->getFechaInicio());
        $convenio->setFecFin($periodo->getFechaFinal());        
        $convenio->setDescuento($convenioDto->descuento);
        $convenio->setEsCooperativa($convenioDto->esCooperativa);
        $convenio->setComentarios($convenioDto->comentarios);
        $convenio->setCalendario($periodo);

        foreach ($convenioDto->reglasDeDescuento as $reglaDto) {
            $convenio->agregarReglaDescuento(
                new ConvenioRegla(
                    $reglaDto['min_participantes'],
                    $reglaDto['max_participantes'],
                    $reglaDto['descuento']
                )
            );
        }        

        $exito = $convenio->crear();
        if (!$exito) {
            return new Response("500", "Ha ocurrido un error en el sistema");
        }

        return new Response("201", "El convenio se ha creado con éxito.");
    }
}