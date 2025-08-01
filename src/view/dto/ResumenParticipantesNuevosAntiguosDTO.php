<?php

namespace Src\view\dto;

class ResumenParticipantesNuevosAntiguosDTO
{
    private int $totalParticipantes;
    private int $totalNuevos;
    private int $totalAntiguos;
    
    public function __construct(int $totalParticipantes, int $totalNuevos, int $totalAntiguos)
    {
        $this->totalParticipantes = $totalParticipantes;
        $this->totalNuevos = $totalNuevos;
        $this->totalAntiguos = $totalAntiguos;
    }

    public function getTotalParticipantes(): int {
        return $this->totalParticipantes;
    }

    public function getTotalNuevos(): int {
        return $this->totalNuevos;
    }

    public function getTotalAntiguos(): int {
        return $this->totalAntiguos;
    }
}