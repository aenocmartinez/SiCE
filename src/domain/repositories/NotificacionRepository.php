<?php

namespace Src\domain\repositories;


interface NotificacionRepository {
    public static function CrearNotificacion($calendarioId, $tipoMensaje, $usuarioAutenticado);
    public static function NotificacionEnviadaHoy($calendarioId, $tipoMensaje): bool;
}