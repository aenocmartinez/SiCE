<?php

namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;

class EliminarSalonUseCase {

    public function ejecutar(int $id=0): array {
        $salonRepository = new SalonDao();
        $salon = Salon::buscarPorId($id, $salonRepository);
        if (!$salon->existe()) {
            return [
                "code" => "404",
                "message" => "salón no encontrado",
            ];
        }

        $salon->setRepository($salonRepository);
        $exito = $salon->eliminar();
        if (!$exito) {
            return [
                "code" => "500",
                "message" => "ha ocurrido un error en el sistema",
            ];
        }
        
        return [
            "code" => "200",
            "message" => "registro eliminado con éxito"
        ];
    }
}