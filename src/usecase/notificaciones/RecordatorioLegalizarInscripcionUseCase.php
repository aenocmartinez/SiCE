<?php

namespace Src\usecase\notificaciones;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\Calendario;
use Src\domain\notificaciones\MensajeCursoExtension;
use Src\domain\notificaciones\Mailtrap;
use Src\domain\notificaciones\ContenidoNotificacionDTO;
use Src\infraestructure\util\FormatoFecha;
use Src\infraestructure\util\FormatoString;

class RecordatorioLegalizarInscripcionUseCase
{
    const BLOQUE_CORREOS = 1; // Tamaño del bloque de correos
    const LIMITE_MUESTRA = 3; // Límite de correos para prueba

    /**
     * Envía recordatorios a los participantes legalizados.
     *
     * @param Calendario $periodo Periodo del curso.
     * @return void
     */
    public function Ejecutar(Calendario $periodo): void
    {
        // Consultar formularios con el método optimizado
        $formularios = FormularioInscripcionDao::listarFormulariosParaCorreo("Pendiente de pago", $periodo->getId());

        // Limitar la muestra a 14 para las pruebas
        $formulariosMuestra = array_slice($formularios, 0, self::LIMITE_MUESTRA);

        if (empty($formulariosMuestra)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'La lista de participantes está vacía.',
            ]);
            return;
        }

        // Configuración del mensaje base
        $mensaje = new MensajeCursoExtension();
        $contenidoBase = $mensaje->generarContenido('inscripcion_no_legalizada', [
            'ASUNTO' => 'Cursos de Extensión - Recordatorio para legalizar inscripción',
            'FECHA_INICIO' => FormatoFecha::fechaFormateadaA5DeAgostoDe2024($periodo->getFechaInicioClase()),
            'NOMBRE' => '{{NOMBRE}}',
        ]);

        // Respuesta inmediata al cliente
        ignore_user_abort(true); // Permitir que el script siga ejecutándose incluso si el cliente cierra la conexión
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'El envío de correos ha comenzado y continuará en segundo plano.',
        ]);
        flush(); // Forzar el envío de la respuesta al cliente

        // Procesar los correos en segundo plano
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

            // Validar que el participante tenga un email válido
            if (!empty($participante['email'])) {
                // Personalizar mensaje para cada participante
                $nombreParticipante = FormatoString::convertirACapitalCase($participante['nombre']);
                $txtMensaje = str_replace('{{NOMBRE}}', $nombreParticipante, $contenidoBase->getMensaje());
                // $txtMensaje = str_replace('{{NOMBRE_CURSO}}', $curso['nombre'], $txtMensaje);

                // Crear contenido personalizado
                $contenidoPersonalizado = new ContenidoNotificacionDTO(
                    $participante['email'],
                    $contenidoBase->getAsunto(),
                    $txtMensaje,
                    $contenidoBase->getAdjunto()
                );

                $destinatarios[] = $contenidoPersonalizado;

                // Enviar cuando el bloque alcance el tamaño definido
                if (count($destinatarios) >= self::BLOQUE_CORREOS) {
                    $this->enviarBloque($destinatarios);
                    $destinatarios = [];
                }
            }
        }

        // Enviar el último bloque si quedó incompleto
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
        $medio = new Mailtrap();
    
        foreach ($destinatarios as $contenido) {
            try {
                $medio->enviar($contenido); // Enviar correo individual
                echo "Correo enviado a: {$contenido->getDestinatario()}\n";
            } catch (\Exception $e) {
                echo "Error al enviar el correo a {$contenido->getDestinatario()}: {$e->getMessage()}\n";
            }
    
            // Agregar un retraso de 1 segundo entre cada correo
            usleep(1000000); // 1 segundo (en microsegundos)
        }
    }
    
}
