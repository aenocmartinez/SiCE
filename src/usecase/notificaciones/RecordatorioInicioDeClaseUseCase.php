<?php

namespace Src\usecase\notificaciones;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\Calendario;
use Src\domain\FormularioInscripcion;
use Src\domain\notificaciones\MensajeCursoExtension;
use Src\domain\notificaciones\NotificacionGrupal;
use Src\domain\notificaciones\ContenidoNotificacionDTO;
use Src\domain\notificaciones\Mailtrap;
use Src\domain\Participante;
use Src\infraestructure\util\FormatoFecha;

class RecordatorioInicioDeClaseUseCase
{
    /**
     * Envía recordatorios a los participantes legalizados.
     *
     * @param Calendario $periodo Periodo del curso.
     */
    public function Ejecutar(Calendario $periodo)
    {
        $formularios = (new FormularioInscripcionDao())->listarFormulariosPorEstadoYCalendario("pagado", $periodo->getId());
        if (empty($formularios)) {
            throw new \InvalidArgumentException("La lista de participantes está vacía.");
        }

        $mensaje = new MensajeCursoExtension();

        $contenidoBase = $mensaje->generarContenido('recordatorio_clases', [
            'ASUNTO' => 'Recordatorio de Inicio de Clases',
            'FECHA_INICIO' => FormatoFecha::fechaDDdeMMdeYYYY($periodo->getFechaInicioClase()),
            'NOMBRE' => '{{NOMBRE}}'
        ]);


        $destinatarios = [];
        foreach ($formularios as $index => $formulario) {
            if ($index > 10) {
                break;
            }

            if ($formulario instanceof FormularioInscripcion && $formulario->getParticipante()->getEmail()) {
                $nombreParticipante = ucwords(strtolower($formulario->getParticipante()->getNombreCompleto()));
                $txtMensaje = str_replace('{{NOMBRE}}', $nombreParticipante, $contenidoBase->getMensaje());
                $txtMensaje = str_replace('{{NOMBRE_CURSO}}', $formulario->getGrupoNombreCurso(), $txtMensaje);

                $contenidoPersonalizado = new ContenidoNotificacionDTO(
                    $formulario->getParticipante()->getEmail(),
                    $contenidoBase->getAsunto(),
                    $txtMensaje,
                    $contenidoBase->getAdjunto()
                );
                $destinatarios[] = $contenidoPersonalizado;
            }
        }

        if (empty($destinatarios)) {
            throw new \InvalidArgumentException("No se encontraron destinatarios válidos.");
        }

        $medio = new Mailtrap();
        $notificacionGrupal = new NotificacionGrupal($medio, $destinatarios, $contenidoBase);
        $notificacionGrupal->enviar();
    }
}
