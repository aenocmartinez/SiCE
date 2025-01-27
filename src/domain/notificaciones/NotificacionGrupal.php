<?php

namespace Src\domain\notificaciones;


class NotificacionGrupal
{
    private MedioNotificacion $medio;
    private array $destinatarios; 
    private ContenidoNotificacionDTO $contenido;

    public function __construct(MedioNotificacion $medio, array $destinatarios, ContenidoNotificacionDTO $contenido)
    {
        $this->medio = $medio;
        $this->destinatarios = $destinatarios;
        $this->contenido = $contenido;
    }

    public function enviar(): void
    {
        foreach ($this->destinatarios as $destinatario) {
            $contenidoPersonalizado = new ContenidoNotificacionDTO(
                $destinatario->getDestinatario(),
                $this->contenido->getAsunto(),
                $destinatario->getMensaje(),
                $this->contenido->getAdjunto()
            );

            $this->medio->enviar($contenidoPersonalizado);
        }
    }
}
