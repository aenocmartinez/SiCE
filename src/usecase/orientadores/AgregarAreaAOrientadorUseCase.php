<?php

namespace Src\usecase\orientadores;

use Src\dao\mysql\AreaDao;
use Src\dao\mysql\OrientadorDao;
use Src\domain\Area;
use Src\domain\Orientador;
use Src\view\dto\Response;

class AgregarAreaAOrientadorUseCase {

    public function ejecutar(int $idOrientador=0, int $idArea=0): Response {
        $orientadorRepository = new OrientadorDao();
        $areaRepository = new AreaDao();

        $orientador = Orientador::buscarPorId($idOrientador, $orientadorRepository);
        if (!$orientador->existe()) {
            return new Response('200', 'Orientador no encontrado');
        }

        $area = Area::buscarPorId($idArea, $areaRepository);
        if (!$area->existe()) {
            return new Response('200', 'Área no encontrada');
        }

        $orientador->setRepository($orientadorRepository);
        $exito = $orientador->agregarArea($area);

        if (!$exito) {
            return new Response('500', 'El orientador ya tiene esta área asignada');
        }

        return new Response('200', 'Se ha agregado con éxito el área');
    }
}