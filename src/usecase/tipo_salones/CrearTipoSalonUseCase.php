<?php

namespace Src\usecase\tipo_salones;

use Src\dao\mysql\TipoSalonDao;
use Src\domain\TipoSalon;
use Src\view\dto\Response;
use Src\view\dto\TipoSalonDto;

class CrearTipoSalonUseCase {
    public function ejecutar(TipoSalonDto $tipoSalonDto): Response{

        $tipoSalonRepository = new TipoSalonDao();
        $tipoSalon = TipoSalon::buscarPorNombre($tipoSalonDto->nombre, $tipoSalonRepository);
        if ($tipoSalon->existe()) 
            return new Response("200", "El tipo de salón ya existe");        

        $tipoSalon->setRepository($tipoSalonRepository);
        $tipoSalon->setNombre($tipoSalonDto->nombre);

        $exito = $tipoSalon->crear();
        if (!$exito) 
            return new Response("500", "Ha ocurrido un error en el sistema");
        
        return new Response("201", "registro creado con éxito");
    }
}