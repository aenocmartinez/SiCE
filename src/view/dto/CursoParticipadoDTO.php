<?php

namespace Src\view\dto;

class CursoParticipadoDTO
{
    public $curso_id;
    public $nombre_curso;
    public $grupo_id;
    public $asistencias;
    public $total_sesiones;
    public $porcentaje_asistencia;
    public $aprobado;

    public function __construct(
        $curso_id,
        $nombre_curso,
        $grupo_id,
        $asistencias,
        $total_sesiones,
        $porcentaje_asistencia,
        $aprobado
    ) {
        $this->curso_id = $curso_id;
        $this->nombre_curso = $nombre_curso;
        $this->grupo_id = $grupo_id;
        $this->asistencias = $asistencias;
        $this->total_sesiones = $total_sesiones;
        $this->porcentaje_asistencia = $porcentaje_asistencia;
        $this->aprobado = $aprobado;
    }
}
