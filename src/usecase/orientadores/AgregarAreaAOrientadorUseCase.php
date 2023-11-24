<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\AreaDao;
use Src\dao\mysql\OrientadorDao;
use Src\domain\Area;
use Src\domain\Orientador;
use Src\view\dto\Response;

class AgregarAreaAOrientadorUseCase {

    public function ejecutar(int $orientadorId=0, $idAreas=[]) {
        $orientadorRepository = new OrientadorDao();
        
        $orientador = new Orientador();
        $orientador->setId($orientadorId);
        $orientador->setRepository($orientadorRepository);

        $orientador->quitarAreas();
        foreach ($idAreas as $areaId) {
            $area = new Area();
            $area->setId($areaId);

            $orientador->agregarArea($area);
        }
    }
}