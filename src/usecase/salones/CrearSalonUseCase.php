<?php

namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;
use Src\view\dto\SalonDto;

class CrearSalonUseCase {
    public function ejecutar(SalonDto $salonDto): array{
        $salonRepository = new SalonDao();

        $salon = Salon::buscarPorNombre($salonDto->nombre, $salonRepository);
        if ($salon->existe()) {
            return [
                "code" => "200",
                "message" => "el salón ya existe"
            ];            
        }

        $salon->setRepository($salonRepository);
        $salon->setNombre($salonDto->nombre);
        $salon->setCapacidad($salonDto->capacidad);
        $salon->setDisponible($salonDto->disponible);

        $exito = $salon->crear();
        if (!$exito) {
            return [
                "code" => "500",
                "message" => "ha ocurrido un error en el sistema"
            ];            
        }
        
        return [
            "code" => "201",
            "message" => "registro creado con éxito"
        ];
    }
}