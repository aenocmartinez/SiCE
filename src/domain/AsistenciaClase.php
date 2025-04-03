<?php

namespace Src\domain;

class AsistenciaClase 
{
    private Participante $participante;
    private int $sesion;
    private bool $presente;

    public function __construct(Participante $participante, int $sesion, bool $presente)
    {
        $this->participante = $participante;
        $this->sesion = $sesion;
        $this->presente = $presente;
    }

    public function getParticipante(): Participante {
        return $this->participante;
    }

    public function getSesion(): int {
        return $this->sesion;
    }

    public function estaPresente(): bool {
        return $this->presente;
    }
}