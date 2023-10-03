<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\Grupo;
use Src\domain\Orientador;
use Src\domain\Salon;
use Src\view\dto\GrupoDto;
use Src\view\dto\Response;

class CrearGrupoUseCase {

    public function ejecutar(GrupoDto $grupoDto): Response {
        $grupoRepository = new GrupoDao();
        $grupo = new Grupo();

        $curso = new Curso;
        $curso->setId($grupoDto->cursoId);

        $orientador = new Orientador;
        $orientador->setId($grupoDto->orientadorId);

        $calendario = new Calendario; 
        $calendario->setId($grupoDto->calendarioId);

        $salon = new Salon; 
        $salon->setId($grupoDto->salonId);

        $grupo->setCurso($curso);
        $grupo->setOrientador($orientador);
        $grupo->setCalendario($calendario);
        $grupo->setSalon($salon);
        $grupo->setDia($grupoDto->dia);
        $grupo->setJornada($grupoDto->jornada);
        $grupo->setRepository($grupoRepository);

        $existe = Grupo::validarExistencia($grupo, $grupoRepository);
        if ($existe) {
            return new Response('200', 'Ya existe un grupo con los datos ingresados.');
        }

        $salonDisponible = Grupo::validarSalonDisponible($grupo, $grupoRepository);
        if (!$salonDisponible) {
            return new Response('401', 'El salón indicado está ocupado con otra clase el día '.$grupoDto->dia.' en la jornada '.$grupoDto->jornada);
        }

        $exito = $grupo->crear();
        if (!$exito)
            return new Response('500', 'Ha ocurrido un error en el sistema');
        
        return new Response('200', 'Registro creado con éxito');
    }
}