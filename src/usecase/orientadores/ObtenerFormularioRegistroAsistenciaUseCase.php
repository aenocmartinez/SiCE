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
            return new Response("404", "Orientador no encontrado.");
        }

        $orientador->setGruposPorCalendario($periodo->getId());

        $datosFormulario = [
            "orientador" => $orientador,
        ];

        foreach($orientador->misGrupos() as $grupo) 
        {
            if ($grupo->estaCancelado()) 
            {
                continue;
            }
        
            $participantes = GrupoDao::listadoParticipantesPlanillaAsistencia($grupo->getId());

            $datosFormulario['grupos'][] = [
                    'id' => $grupo->getId(),
                    'nombre_curso' => $grupo->getNombreCurso(),
                    "jornada" => $grupo->getJornada(),
                    "dia" => $grupo->getDia(),
                    "nombre_salon" => $grupo->getNombreSalon(),
                    "codigo_grupo" => $grupo->getCodigoGrupo(),
                    'proxima_sesion' => $grupo->obtenerLaUltimaAsistenciaRegistrada() + 1,
                    'participantes' => $participantes,
            ]; 
        }        

        return new Response("200", "Orientador no encontrado.", $datosFormulario);
    }    
}