<?php
namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;
use Src\view\dto\SalonDto;

class ActualizarSalonUseCase {

    public function ejecutar(SalonDto $salonDto): array {
        
        $salonRepository = new SalonDao();

        $salon = Salon::buscarPorId($salonDto->id, $salonRepository);
        if (!$salon->existe()) {
            return [
                "code" => "404",
                "message" => "salón no encontrado"
            ];
        }

        $salon->setRepository($salonRepository);
        $salon->setDisponible($salonDto->disponible);
        $salon->setCapacidad($salonDto->capacidad);
        $salon->setNombre($salonDto->nombre);

        $exito = $salon->actualizar();
        if (!$exito) {
            return [
                "code" => "500",
                "message" => "ha ocurrido un error en el sistema",
            ];            
        }

        return [
            "code" => 200,
            "message" => "registro actualizado con éxito"
        ];
    }
}