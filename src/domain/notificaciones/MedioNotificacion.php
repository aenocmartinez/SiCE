<?php

namespace Src\domain\notificaciones;


interface MedioNotificacion
{
    public function enviar(ContenidoNotificacionDTO $contenido): void;
}
