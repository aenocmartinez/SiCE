<?php

namespace Src\usecase\calendarios;

use GrupoDto;
use Src\dao\mysql\GrupoDao;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\Grupo;
use Src\domain\Orientador;
use Src\domain\Salon;
use Src\view\dto\Response;

class ActualizarGrupoUseCase {

    public function ejecutar(GrupoDto $grupoDto): Response {

        $grupoRepository = new GrupoDao();

        $grupo = Grupo::buscarPorId($grupoDto->id, $grupoRepository);

        if (!$grupo->existe()) {
            return new Response('404', 'Grupo no encontrado');
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
        
        $exito = $grupo->actualizar();
        if (!$exito) {
            return new Response('500', 'Ha ocurrido un error en el sistema');
        }

        return new Response('200', 'Registro actualizado con éxito');        
    }
}