<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\dao\mysql\CursoDao;
use Src\domain\Calendario;
use Src\infraestructure\util\Validador;
use Src\view\dto\Response;
use Src\view\dto\CursoCalendarioDto;

class AgregarCursoACalendarioUseCase {

    public function ejecutar(Calendario $calendario, int $areaId, array $cursos = []): Response 
    {
        $ids_de_cursos_abiertos = $calendario->obtenerIDsDeCursosAbiertosPorCalendarioYArea($areaId);
        $nuevos_ids_de_cursos_abiertos = [];

        foreach ($cursos as $cursoData) {   

            if (is_null($cursoData['costo'])) {
                continue;
            }

            $curso = (new CursoDao())->buscarCursoPorId($cursoData['curso_id']);
            if (!$curso->existe()) {
                continue;
            }

            $calendario->setRepository(new CalendarioDao());
            $exito = $calendario->agregarCurso($curso, [
                'cupo' => 0, 
                'costo' => Validador::convertirAEnteroDesdeMoneda($cursoData['costo']), 
                'modalidad' => $cursoData['modalidad']
            ]);

            if ($exito) {
                $nuevos_ids_de_cursos_abiertos[] = $cursoData['curso_id'];
            }
        }

        $ids_para_retirar_del_calendario = array_diff($ids_de_cursos_abiertos, $nuevos_ids_de_cursos_abiertos);
        
        $response = (new RetirarCursoACalendarioUseCase)->ejecutar($calendario, $ids_para_retirar_del_calendario);

        if ($response->code != 200) {
            $response->message = "Ha ocurrido un error durante la apertura de los cursos.";
            return $response;
        }

        return new Response('200', "La apertura de los cursos se ha realizado de forma exitosa.");
    }

}