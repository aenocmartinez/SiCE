<?php

namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;

class BuscarSalonPorIdUseCase {

    public function ejecutar(int $id=0): array{
        $salonRepository = new SalonDao();
        $salon = Salon::buscarPorId($id, $salonRepository);
        if (!$salon->existe()) {
            return [
                "code" => "404",
                "message" => "salón no encontrado"
            ];
        }
        
        return [
            "code" => "200",
            "data" => [
                "id" => $salon->getId(),
                "nombre" => $salon->getNombre(),
                "capacidad" => $salon->getCapacidad(),
                "esta_disponible" => $salon->estaDisponible(),
            ]
        ];
    }
}