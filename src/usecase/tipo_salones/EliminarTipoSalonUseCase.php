<?php

namespace Src\usecase\tipo_salones;

use Src\dao\mysql\TipoSalonDao;
use Src\domain\TipoSalon;
use Src\view\dto\Response;

class EliminarTipoSalonUseCase {

    public function ejecutar(int $id=0): Response {

        $response = new Response("200", "Registro eliminado con éxito");

        $salonRepository = new TipoSalonDao();
        $tipoSalon = TipoSalon::buscarPorId($id, $salonRepository);
        if (!$tipoSalon->existe()) {
            return new Response("404", "Tipo de salón no encontrado");
        }

        $tipoSalon->setRepository($salonRepository);
        $exito = $tipoSalon->eliminar();
        if (!$exito) {
            return new Response("500", "Ha ocurrido un error en el sistema");
        }
        
        return $response;

    }
}