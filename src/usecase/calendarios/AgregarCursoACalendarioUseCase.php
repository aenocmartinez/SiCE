<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\dao\mysql\CursoDao;
use Src\view\dto\Response;
use Src\view\dto\CursoCalendarioDto;

class AgregarCursoACalendarioUseCase {

    public function ejecutar(CursoCalendarioDto $dto): Response {

        $caledarioRepository = new CalendarioDao();
        $cursoRepository = new CursoDao();

        $calendario = $caledarioRepository->buscarCalendarioPorId($dto->calendarioId);
        if (!$calendario->existe()) {
            return new Response('500', 'El calendario no existe');
        }

        if (!$calendario->esVigente()) {
            return new Response('500', 'No es posible agregar curso porque el calendario está caducado.');
        }

        $curso = $cursoRepository->buscarCursoPorId($dto->cursoId);
        if (!$curso->existe()) {
            return new Response('500', 'El curso que intenta agregar no existe.');
        }

        $calendario->setRepository($caledarioRepository);
        $exito = $calendario->agregarCurso($curso, [
            'cupo' => $dto->cupos, 
            'costo' => $dto->costo, 
            'modalidad' => $dto->modalidad
        ]);

        if (!$exito) {
            return new Response('500', 'El curso en la modalidad indicada ya ha sido agregado a este calendario');
        }

        return new Response('200', 'Curso agregado con éxito');
    }
}