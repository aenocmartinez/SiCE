<?php
namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;
use Src\view\dto\Response;
use Src\view\dto\SalonDto;

class ActualizarSalonUseCase {

    public function ejecutar(SalonDto $salonDto): Response {
        
        $salonRepository = new SalonDao();

        $salon = Salon::buscarPorId($salonDto->id, $salonRepository);
        if (!$salon->existe()) 
            return new Response("404", "salón no encontrado");

        $salon->setRepository($salonRepository);
        $salon->setDisponible($salonDto->disponible);
        $salon->setCapacidad($salonDto->capacidad);
        $salon->setNombre($salonDto->nombre);

        $exito = $salon->actualizar();
        if (!$exito) 
            return new Response("500", "Ha ocurrido un error en el sistema");

        
        return new Response("200", "Registro actualizado con éxito");
    }
}