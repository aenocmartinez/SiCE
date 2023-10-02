<?php

namespace Src\usecase\calendarios;

use GrupoDto;
use Src\dao\mysql\GrupoDao;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\Grupo;
use Src\domain\Orientador;
use Src\domain\Salon;

class ActualizarGrupoUseCase {

    public function ejecutar(GrupoDto $grupoDto): bool {

        $grupoRepository = new GrupoDao();

        $grupo = Grupo::buscarPorId($grupoDto->id, $grupoRepository);

        if (!$grupo->existe()) {
            return false;
        }

        $grupo->setRepository($grupoRepository);

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
        
        return $grupo->actualizar();
    }
}