<?php

namespace Src\usecase\notificaciones;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\dao\mysql\NotificacionDao;
use Src\domain\Calendario;
use Src\domain\notificaciones\MensajeCursoExtension;
use Src\domain\notificaciones\ContenidoNotificacionDTO;
use Src\domain\notificaciones\Gmail;
use Src\infraestructure\util\FormatoFecha;
use Src\infraestructure\util\FormatoString;

class RecordatorioInicioDeClaseUseCase
{
    const BLOQUE_CORREOS = 10; 
    // const LIMITE_MUESTRA = 1; 

    /**
     * Envía recordatorios a los participantes legalizados.
     *
     * @param Calendario $periodo Periodo del curso.
     * @return void
     */
    public function Ejecutar(Calendario $periodo, $usuarioAutenticado=1): void
    {
        $formularios = FormularioInscripcionDao::listarFormulariosParaCorreo("pagado", $periodo->getId());

        // $formulariosMuestra = array_slice($formularios, 0, self::LIMITE_MUESTRA);

        if (empty($formulariosMuestra)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'La lista de participantes está vacía.',
            ]);
            return;
        }

        $mensaje = new MensajeCursoExtension();
        $contenidoBase = $mensaje->generarContenido('recordatorio_clases', [
            'ASUNTO' => 'Cursos de Extensión - Recordatorio de Inicio de Clases',
            'FECHA_INICIO' => FormatoFecha::fechaFormateadaA5DeAgostoDe2024($periodo->getFechaInicioClase()),
            'NOMBRE' => '{{NOMBRE}}',
        ]);

        ignore_user_abort(true);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'El envío de correos ha comenzado y continuará en segundo plano.',
        ]);
        flush(); 

        NotificacionDao::CrearNotificacion($periodo->getId(), 'INICIO_CLASE', $usuarioAutenticado);
        
        $this->procesarEnBloques($formulariosMuestra, $contenidoBase);
    }

    /**
     * Procesa el envío de correos en bloques.
     *
     * @param array $formularios Lista de formularios.
     * @param ContenidoNotificacionDTO $contenidoBase Mensaje base.
     */
    private function procesarEnBloques(array $formularios, ContenidoNotificacionDTO $contenidoBase)
    {
        $destinatarios = [];
        foreach ($formularios as $formulario) {
            $participante = $formulario['participante'];
            $curso = $formulario['curso'];

            if (!empty($participante['email'])) {
                $nombreParticipante = FormatoString::convertirACapitalCase($participante['nombre']);
                $txtMensaje = str_replace('{{NOMBRE}}', $nombreParticipante, $contenidoBase->getMensaje());
                $txtMensaje = str_replace('{{NOMBRE_CURSO}}', $curso['nombre'], $txtMensaje);

                $contenidoPersonalizado = new ContenidoNotificacionDTO(
                    $participante['email'],
                    $contenidoBase->getAsunto(),
                    $txtMensaje,
                    $contenidoBase->getAdjunto()
                );

                $destinatarios[] = $contenidoPersonalizado;

                if (count($destinatarios) >= self::BLOQUE_CORREOS) {
                    $this->enviarBloque($destinatarios);
                    $destinatarios = [];
                }
            }
        }

        if (!empty($destinatarios)) {
            $this->enviarBloque($destinatarios);
        }
    }

    /**
     * Envía un bloque de correos.
     *
     * @param array $destinatarios Lista de destinatarios.
     */
    private function enviarBloque(array $destinatarios)
    {
        // $medio = new Mailtrap();
        $medio = new Gmail();
    
        foreach ($destinatarios as $contenido) {
            try {
                $medio->enviar($contenido); 
                echo "Correo enviado a: {$contenido->getDestinatario()}\n";
            } catch (\Exception $e) {
                echo "Error al enviar el correo a {$contenido->getDestinatario()}: {$e->getMessage()}\n";
            }
    
            usleep(1000000);
        }
    }
    
}
