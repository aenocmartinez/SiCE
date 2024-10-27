<?php

namespace Src\usecase\calendarios;

use Illuminate\Support\Facades\Log;
use Src\dao\mysql\CalendarioDao;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\CursoCalendario;
use Src\view\dto\Response;

class RetirarCursoACalendarioUseCase {

    public function ejecutar(int $calendarioId=0, int $cursoCalendarioId=0): Response {

        $calendarioRepository = new CalendarioDao();

        $calendario = new Calendario();
        $calendario->setId($calendarioId);

        $cursoCalendario = new CursoCalendario($calendario, new Curso());
        $cursoCalendario->setId($cursoCalendarioId);
    
        $calendarioRepository->retirarCurso($cursoCalendario);

        // if (!$exito) {
        //     return new Response('500', 'No se puede eliminar el regitro porque tiene registros relacionados.');
        // }

        return new Response('200', 'El curso se ha retirado con éxito');        
    }
}