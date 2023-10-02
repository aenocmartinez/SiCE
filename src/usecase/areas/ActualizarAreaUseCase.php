<?php

namespace Src\usecase\areas;

use Src\dao\mysql\AreaDao;
use Src\domain\Area;
use Src\view\dto\AreaDto;
use Src\view\dto\Response;

class ActualizarAreaUseCase {

    public function ejecutar(AreaDto $areaDto): Response {

        $areaRepository = new AreaDao();
        
        $area = Area::buscarPorId($areaDto->id, $areaRepository);
        if (!$area->existe()) 
            return new Response('404', 'Área no encontrada');
    
        $area->setRepository($areaRepository);
        $area->setNombre($areaDto->nombre);
        $exito = $area->actualizar();

        if (!$exito) 
            return new Response('500', 'Ha ocurrido un error en el sistema');
        
        return new Response('200', 'Registro actualizado con éxito');
    }
}