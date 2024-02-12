<?php

namespace Src\usecase\grupos;

use Src\dao\mysql\GrupoDao;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\CursoCalendario;
use Src\domain\Grupo;
use Src\domain\Orientador;
use Src\domain\Salon;
use Src\view\dto\GrupoDto;
use Src\view\dto\Response;

class CrearGrupoUseCase {

    public function ejecutar(GrupoDto $grupoDto): Response {
        $grupoRepository = new GrupoDao();

        $grupo = new Grupo();


        $calendario = new Calendario();
        $calendario->setId($grupoDto->calendarioId);

        $cursoCalendario = new CursoCalendario($calendario, new Curso());
        $cursoCalendario->setId($grupoDto->cursoCalendarioId);

        $grupo->setCursoCalendario($cursoCalendario);

        $orientador = new Orientador;
        $orientador->setId($grupoDto->orientadorId);


        $salon = new Salon; 
        $salon->setId($grupoDto->salonId);

        $grupo->setOrientador($orientador);
        $grupo->setSalon($salon);
        $grupo->setDia($grupoDto->dia);
        $grupo->setJornada($grupoDto->jornada);
        $grupo->setCupo($grupoDto->cupo);
        $grupo->setHora($grupoDto->hora);
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