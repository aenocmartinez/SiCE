<?php

namespace Src\domain\notificaciones;

use Exception;

class MensajeCursoExtension
{
    private array $plantillas = [
        'confirmacion_inscripcion' => "
            <p>Estimado/a {{NOMBRE}},</p>
            <p>Su inscripción en el curso <strong>{{CURSO}}</strong> ha sido confirmada.</p>
            <p>Fecha de inicio: {{FECHA_INICIO}}</p>
            <p>Saludos cordiales,</p>
            <p>El equipo de Cursos de Extensión</p>
        ",
        'recordatorio_clases' => "
            <p>Respetado(a): {{NOMBRE}}</p>
            <p>Reciba un cordial saludo,</p>
            <p>Le recordamos que el curso <strong>\"{{NOMBRE_CURSO}}\"</strong>, impartido por la Universidad Colegio Mayor de Cundinamarca, en el cual usted se encuentra inscrito(a), dará inicio el próximo <strong>{{FECHA_INICIO}}</strong>.</p>            
            <p><br></p>
            <p></p>
            <p>Equipo de Cursos de Extensión</p>
            <p><strong>Universidad Colegio Mayor de Cundinamarca</strong></p>
        ",
        'inscripcion_no_legalizada' => "
            <p>Respetado(a) {{NOMBRE}},</p>
            <p>Se le recuerda que el inicio de Clases de los Cursos de Extensión, es a partir del <strong>{{FECHA_INICIO}}</strong>, en el día y jornada de su curso matriculado, por lo tanto, si no ha legalizado su curso, el cupo reservado será asignado a otro aspirante.</p>
            <p><br></p>
            <p></p>
            <p>Equipo de Cursos de Extensión</p>
            <p><strong>Universidad Colegio Mayor de Cundinamarca</strong></p>
        ",        
    ];

    public function obtenerPlantilla(string $tipo): string
    {
        if (!isset($this->plantillas[$tipo])) {
            throw new Exception("No existe la plantilla para el tipo: {$tipo}");
        }

        return $this->plantillas[$tipo];
    }

    public function generarContenido(string $tipo, array $datos): ContenidoNotificacionDTO
    {
        $plantilla = $this->obtenerPlantilla($tipo);

        foreach ($datos as $clave => $valor) {
            $plantilla = str_replace("{{{$clave}}}", $valor, $plantilla);
        }

        return new ContenidoNotificacionDTO(
            $datos['DESTINATARIO'] ?? '',
            $datos['ASUNTO'],
            $plantilla,
            $datos['ADJUNTO'] ?? ""
        );
    }
}
