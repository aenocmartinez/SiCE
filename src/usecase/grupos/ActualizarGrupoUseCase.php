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

class ActualizarGrupoUseCase {

    public function ejecutar(GrupoDto $grupoDto): Response {

        $grupoRepository = new GrupoDao();

        $grupo = $grupoRepository->buscarGrupoPorId(intval($grupoDto->id));        

        if (!$grupo->existe()) {
            return new Response('404', 'Grupo no encontrado');
        }

        $cursoCalendario = new CursoCalendario(new Calendario(), new Curso());
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
        $grupo->setRepository($grupoRepository);
        
        // $salonDisponible = Grupo::validarSalonDisponible($grupo, $grupoRepository);
        // if (!$salonDisponible) {
        //     return new Response('401', 'El salón está ocupado en el día y jornada indicado.');
        // }        
        
        $exito = $grupo->actualizar();
        if (!$exito) {
            return new Response('500', 'Ha ocurrido un error en el sistema');
        }

        return new Response('200', 'Registro actualizado con éxito');        
    }
}