<?php

namespace Src\domain\notificaciones;

class NotificacionIndividual
{
    private MedioNotificacion $medio;
    private ContenidoNotificacionDTO $contenido;

    public function __construct(MedioNotificacion $medio, ContenidoNotificacionDTO $contenido)
    {
        $this->medio = $medio;
        $this->contenido = $contenido;
    }

    public function enviar(): void
    {
        $this->medio->enviar($this->contenido);
    }
}
