<?php

namespace Src\usecase\areas;

use Src\domain\Area;
use Src\dao\mysql\AreaDao;
use Src\view\dto\Response;

class CrearAreaUseCase {

    public function ejecutar(string $nombre): Response {

        $areaRepository = new AreaDao();        

        $area = Area::buscarPorNombre($nombre, $areaRepository);
        if ($area->existe()) {
            return new Response("404", "El área ya existe");
        }
        
        $area->setRepository($areaRepository);
        $area->setNombre($nombre);

        $exito = $area->crear();
        if (!$exito) {
            return new Response("500", "Ha ocurrido un error en el sistema");
        }

        return new Response("200", "Registro creado con éxito");
    }
}