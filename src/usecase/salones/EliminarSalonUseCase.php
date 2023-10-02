<?php

namespace Src\usecase\salones;

use Src\dao\mysql\SalonDao;
use Src\domain\Salon;
use Src\view\dto\Response;

class EliminarSalonUseCase {

    public function ejecutar(int $id=0): Response {

        $response = new Response("200", "Registro eliminado con éxito");

        $salonRepository = new SalonDao();
        $salon = Salon::buscarPorId($id, $salonRepository);
        if (!$salon->existe()) {
            return new Response("404", "Salón no encontrado");
        }

        $salon->setRepository($salonRepository);
        $exito = $salon->eliminar();
        if (!$exito) {
            return new Response("500", "Ha ocurrido un error en el sistema");
        }
        
        return $response;

    }
}