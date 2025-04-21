<?php

namespace Src\usecase\orientadores;

use Illuminate\Support\Facades\Auth;
use Src\dao\mysql\CalendarioDao;
use Src\dao\mysql\GrupoDao;
use Src\dao\mysql\OrientadorDao;
use Src\view\dto\Response;

class ObtenerDatosFormularioReporteAsistenciaUseCase
{
    public function ejecutar(): Response
    {
        $orientadorDao = new OrientadorDao();
        $grupoDao = new GrupoDao();
        $calendarioDao = new CalendarioDao();

        $orientador = $orientadorDao->buscarOrientadorPorId(Auth::user()->orientador_id);
        if (!$orientador->existe()) {
            return new Response("404", "Orientador no encontrado.");
        }

        $periodos = $calendarioDao->listarCalendarios();
        $datosFormulario = [];

        foreach ($periodos as $periodo) {
            $periodoID = $periodo->getId();
            $nombrePeriodo = $periodo->getNombre();

            $orientador->setGruposPorCalendario($periodoID);

            foreach ($orientador->misGrupos() as $grupo) {
                
                if ($grupo->estaCancelado()) {
                    continue;
                }

                $grupoID = $grupo->getId();
                $codigoGrupo = $grupo->getCodigoGrupo();
                $nombreCurso = $grupo->getNombreCurso();
                $jornada = $grupo->getJornada();
                $dia = $grupo->getDia();
                $salon = $grupo->getNombreSalon();
                $area = $grupo->getNombreArea();

                $asistencias = $grupoDao->obtenerAsistenciaAClase($grupoID);
                $participantesTabla = GrupoDao::listadoParticipantesParaTomarAsistencia($grupoID);

                $sesiones = [];

                foreach ($asistencias as $registro) {
                    $sesionNum = $registro->getSesion();
                    $fechaRegistro = $registro->getFechaRegistro();
                    $participanteID = $registro->getParticipante()->getId();
                    $nombre = $registro->getParticipante()->getNombreCompleto();
                    $documento = $registro->getParticipante()->getDocumentoCompleto();
                    $presente = $registro->estaPresente();

                    // Buscar convenio desde el listado plano (índice 6: documento, índice 12: convenio)
                    $convenio = '';
                    foreach ($participantesTabla as $idx => $p) {
                        if ($idx === 0) 
                        {
                            continue; // Saltar encabezado
                        }

                        if (isset($p[6]) && $p[6] === $documento) {
                            $convenio = $p[12] ?? '';
                            break;
                        }
                    }

                    if (!isset($sesiones[$sesionNum])) {
                        $sesiones[$sesionNum] = [
                            'fecha' => $fechaRegistro,
                            'participantes' => [],
                        ];
                    }

                    $sesiones[$sesionNum]['participantes'][] = [
                        'nombre' => $nombre,
                        'doc' => $documento,
                        'convenio' => $convenio,
                        'presente' => $presente,
                    ];
                }

                $datosFormulario['datos'][$nombrePeriodo][] = [
                    'id' => $grupoID,
                    'codigo_grupo' => $codigoGrupo,
                    'nombre_curso' => $nombreCurso,
                    'jornada' => $jornada,
                    'salon' => $salon,
                    'dia' => $dia,
                    'area' => $area,
                    'sesiones' => $sesiones,
                ];
            }
        }
        
        return new Response(200, "OK", $datosFormulario);
    }
}
