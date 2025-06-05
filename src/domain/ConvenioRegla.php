<?php

namespace Src\domain;

class ConvenioRegla
{
    private int $minParticipantes;
    private int $maxParticipantes;
    private float $descuento;

    public function __construct(int $minParticipantes, int $maxParticipantes, float $descuento)
    {
        $this->minParticipantes = $minParticipantes;
        $this->maxParticipantes = $maxParticipantes;
        $this->descuento = $descuento;
    }

    public function getMinParticipantes(): int
    {
        return $this->minParticipantes;
    }

    public function getMaxParticipantes(): int
    {
        return $this->maxParticipantes;
    }

    public function getDescuento(): float
    {
        return $this->descuento;
    }

    public function aplicaPara(int $cantidadInscritos): bool
    {
        return $cantidadInscritos >= $this->minParticipantes && $cantidadInscritos <= $this->maxParticipantes;
    }

    public function toArray(): array
    {
        return [
            'min_participantes' => $this->minParticipantes,
            'max_participantes' => $this->maxParticipantes,
            'descuento' => $this->descuento,
        ];
    }

}
