<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\AreaDao;
use Src\dao\mysql\OrientadorDao;
use Src\domain\Area;
use Src\domain\Orientador;

class AgregarAreaAOrientadorUseCase {

    public function ejecutar(int $idOrientador=0, int $idArea=0) {
        $orientadorRepository = new OrientadorDao();
        $areaRepository = new AreaDao();

        $orientador = Orientador::buscarPorId($idOrientador, $orientadorRepository);
        if (!$orientador->existe()) {
            return [
                "code" => "200",
                "message" => "el orientador no existe"
            ];
        }

        $area = Area::buscarPorId($idArea, $areaRepository);
        if (!$area->existe()) {
            return [
                "code" => "200",
                "message" => "el área no existe"
            ];            
        }

        $orientador->setRepository($orientadorRepository);
        $orientador->agregarArea($area);

    }
}