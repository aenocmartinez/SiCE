<?php

namespace Src\usecase\orientadores;

use Illuminate\Support\Facades\Auth;
use Src\dao\mysql\GrupoDao;
use Src\dao\mysql\OrientadorDao;
use Src\domain\Calendario;
use Src\view\dto\Response;

class ObtenerFormularioRegistroAsistenciaUseCase
{
    public function ejecutar(): Response
    {
        $periodo = Calendario::Vigente();
        if (!$periodo->existe()) 
        {
            return new Response("404", "No existe periodo vigente.");
        }

        $orientadorDao = new OrientadorDao();

        $orientador = $orientadorDao->buscarOrientadorPorId(Auth::user()->orientador_id);
        if (!$orientador->existe())
        {
            return new Response("404", "Instructor no encontrado.");
        }

        $orientador->setGruposPorCalendario($periodo->getId());

        $datosFormulario = [
            "orientador" => $orientador,
        ];

        foreach ($orientador->misGrupos() as $grupo) 
        {
            if ($grupo->estaCancelado()) 
            {
                continue;
            }

            $participantes = GrupoDao::listadoParticipantesParaTomarAsistencia($grupo->getId());

            $datosFormulario['grupos'][] = [
                'id' => $grupo->getId(),
                'nombre_curso' => $grupo->getNombreCurso(),
                'jornada' => $grupo->getJornada(),
                'dia' => $grupo->getDia(),
                'nombre_salon' => $grupo->getNombreSalon(),
                'codigo_grupo' => $grupo->getCodigoGrupo(),
                'proxima_sesion' => $grupo->obtenerLaUltimaAsistenciaRegistrada() + 1,
                'participantes' => $participantes,
            ]; 
        }

        // Ordenar los grupos por día y jornada antes de retornar
        $ordenDias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $ordenJornadas = ['Mañana' => 1, 'Tarde' => 2, 'Noche' => 3];

        usort($datosFormulario['grupos'], function ($a, $b) use ($ordenDias, $ordenJornadas) {
            $diaA = array_search($a['dia'], $ordenDias);
            $diaB = array_search($b['dia'], $ordenDias);

            if ($diaA === $diaB) {
                return $ordenJornadas[$a['jornada']] <=> $ordenJornadas[$b['jornada']];
            }

            return $diaA <=> $diaB;
        });

        return new Response("200", "Formulario generado correctamente.", $datosFormulario);
    }    
}
