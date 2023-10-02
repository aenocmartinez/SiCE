<?php

namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;
use Src\view\dto\Response;
use Src\view\dto\SalonDto;

class CrearSalonUseCase {
    public function ejecutar(SalonDto $salonDto): Response{

        $salonRepository = new SalonDao();
        $salon = Salon::buscarPorNombre($salonDto->nombre, $salonRepository);
        if ($salon->existe()) 
            return new Response("200", "El salón ya existe");        

        $salon->setRepository($salonRepository);
        $salon->setNombre($salonDto->nombre);
        $salon->setCapacidad($salonDto->capacidad);
        $salon->setDisponible($salonDto->disponible);

        $exito = $salon->crear();
        if (!$exito) 
            return new Response("500", "Ha ocurrido un error en el sistema");
        
        return new Response("201", "registro creado con éxito");
    }
}