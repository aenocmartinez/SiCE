<?php

namespace Src\domain\notificaciones;

class ContenidoNotificacionDTO {

    private string $destinatario;
    private string $asunto;
    private string $mensaje;
    private string $adjunto;

    public function __construct(string $destinatario, string $asunto, string $mensaje, string $adjunto = "")
    {
        $this->destinatario = $destinatario;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->adjunto = $adjunto;
    }

    public function getDestinatario(): string {
        return $this->destinatario;
    }

    public function getAsunto(): string {
        return $this->asunto;
    }

    public function getMensaje(): string {
        return $this->mensaje;
    }

    public function getAdjunto(): ?string {
        return $this->adjunto;
    }
}