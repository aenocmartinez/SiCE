<?php

namespace Src\usecase\grupos;

use GrupoDto;
use Src\dao\mysql\GrupoDao;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\Grupo;
use Src\domain\Orientador;
use Src\domain\Salon;
use Src\view\dto\Response;

class CrearGrupoUseCase {

    public function ejecutar(GrupoDto $grupoDto): Response {
        $grupoRepository = new GrupoDao();
        $grupo = new Grupo();

        $curso = new Curso();
        $curso->setId($grupoDto->cursoId);

        $orientador = new Orientador();
        $orientador->setId($grupoDto->orientadorId);

        $calendario = new Calendario();
        $calendario->setId($grupoDto->calendarioId);

        $salon = new Salon();
        $salon->setId($grupoDto->salonId);

        $grupo->setDia($grupoDto->dia);
        $grupo->setJornada($grupoDto->jornada);
        $grupo->setRepository($grupoRepository);

        $exito = $grupo->crear();
        if (!$exito)
            return new Response('500', 'Ha ocurrido un error en el sistema');
        
        return new Response('200', 'Registro creado con éxito');
    }
}