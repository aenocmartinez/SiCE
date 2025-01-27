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
            <p>Apreciado(a): {{NOMBRE}}</p>
            <p>Reciba un cordial saludo,</p>
            <p>Le recordamos que el curso <strong>\"{{NOMBRE_CURSO}}\"</strong>, en el cual se encuentra inscrito(a), impartido por la Universidad Colegio Mayor de Cundinamarca, dará inicio el próximo <strong>{{FECHA_INICIO}}</strong>.</p>            
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
