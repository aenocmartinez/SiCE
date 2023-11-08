<?php

namespace Src\usecase\convenios;

use Src\dao\mysql\ConvenioDao;
use Src\view\dto\Response;

class EliminarConvenioUseCase {

    public function ejecutar(int $convenioId): Response {
        $convenioRepository = new ConvenioDao();
        $convenio = $convenioRepository->buscarConvenioPorId($convenioId);
        if (!$convenio->existe()) {
            return new Response("404", "El convenio no existe.");
        }
        
        $convenio->setRepository($convenioRepository);
        
        $exito = $convenio->eliminar();
        if (!$exito) {
            return new Response("500", "Ha ocurrido un error en el sistema");
        }        
        
        return new Response("200", "El convenio se ha eliminado con éxito.");        
    }
}