<?php

namespace Src\usecase\areas;

use Src\domain\Area;
use Src\view\dto\Response;
use Src\dao\mysql\AreaDao;

class EliminarAreaUseCase {

    public function ejecutar(int $id): Response {

        $areaRepository = new AreaDao();
        $area = Area::buscarPorId($id, $areaRepository);
        if (!$area->existe()) 
            return new Response('404', 'Área no encontrada');

        $area->setRepository($areaRepository);
        $exito = $area->eliminar();

        if (!$exito) 
            return new Response('500', 'Ha ocurrido un error en el sistema');
        
        return new Response('200', 'Registro eliminado con éxito');
    }
}