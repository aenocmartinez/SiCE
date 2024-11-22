<?php

namespace Src\usecase\calendarios;

use Illuminate\Support\Facades\Log;
use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\CursoCalendario;
use Src\view\dto\Response;

class RetirarCursoACalendarioUseCase {

    public function ejecutar(Calendario $calendario, array $cursos_a_retirar=[]): Response {

        $calendarioRepository = new CalendarioDao();

        foreach($cursos_a_retirar as $cursoId)
        {
            $curso = new Curso();
            $curso->setId($cursoId);
            $cursoCalendario = new CursoCalendario($calendario, $curso);
            $calendarioRepository->retirarCurso($cursoCalendario);
        }
    

        return new Response('200', 'El curso se ha retirado con éxito');        
    }
}